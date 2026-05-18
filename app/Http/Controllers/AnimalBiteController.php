<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\MasterlistEntry;
use App\Models\Deduction;
use App\Models\DailyRecord;
use App\Models\CashRecord;
use App\Models\Inventory;
use App\Models\InventoryEntry;
use App\Models\ApprovalRequest;
use Carbon\Carbon;

class AnimalBiteController extends Controller
{
    public function setDate(Request $request)
    {
        $request->validate([
            'selected_date' => 'required|date',
            'selected_branch' => 'nullable|string'
        ]);
        
        session(['selected_date' => $request->selected_date]);
        
        if (auth()->user()->is_super_admin && $request->has('selected_branch')) {
            session(['selected_branch' => $request->selected_branch]);
        }
        
        return redirect()->back();
    }

    public function dashboard()
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        $selectedBranch = auth()->user()->is_super_admin ? session('selected_branch', 'All Branches') : auth()->user()->branch;
        
        // Automated opening cash calculation:
        $historicalSales = MasterlistEntry::where('created_at', '<', $date)
            ->where(function($q) {
                $q->where('payment_method', 'CASH')
                  ->orWhere('payment_method', 'SPLIT');
            })
            ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN cash_amount ELSE amount_paid END'));
            
        $historicalDeductions = Deduction::where('date', '<', $date)
            ->sum('amount');
            
        $defaultOpeningCash = $historicalSales - $historicalDeductions;

        if ($selectedBranch !== 'All Branches') {
            $openingRecord = CashRecord::where('date', $date)->where('shift', 'opening')->first();
            if ($openingRecord) {
                $openingCash = $openingRecord->total_amount;
            } else {
                $prevDate = Carbon::parse($date)->subDay()->toDateString();
                $prevClosing = CashRecord::where('date', $prevDate)->where('shift', 'closing')->first();
                $openingCash = $prevClosing ? $prevClosing->total_amount : 0;
            }
            
            // Sync with DailyRecord for reporting compatibility
            $dailyRecord = DailyRecord::firstOrCreate(['date' => $date, 'branch' => $selectedBranch]);
            if ($dailyRecord->opening_cash != $openingCash) {
                $dailyRecord->update(['opening_cash' => $openingCash]);
            }
            $isEditable = false; // Set to false to remove starting cash edit modal button
        } else {
            // For All Branches mode, sum the opening cash across all branches
            $openingCash = CashRecord::where('date', $date)->where('shift', 'opening')->sum('total_amount');
            if ($openingCash == 0) {
                $prevDate = Carbon::parse($date)->subDay()->toDateString();
                $openingCash = CashRecord::where('date', $prevDate)->where('shift', 'closing')->sum('total_amount');
            }
            $isEditable = false;
        }
        
        $totalPatients = MasterlistEntry::whereDate('created_at', $date)->count();
        $totalSales = MasterlistEntry::whereDate('created_at', $date)->sum('amount_paid');
        $totalDeductions = Deduction::whereDate('date', $date)->sum('amount');
        
        // New Calculations
        $totalOnlineSales = MasterlistEntry::whereDate('created_at', $date)
            ->where(function($q) {
                $q->whereIn('payment_method', ['GCASH', 'GCASH1', 'GCASH2', 'GCASH3', 'GCASH4', 'BPI', 'BDO', 'GOTYME', 'MARIBANK', 'MAYA'])
                  ->orWhere('payment_method', 'SPLIT');
            })
            ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN online_amount ELSE amount_paid END'));

        $totalCashSales = MasterlistEntry::whereDate('created_at', $date)
            ->where(function($q) {
                $q->where('payment_method', 'CASH')
                  ->orWhere('payment_method', 'SPLIT');
            })
            ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN cash_amount ELSE amount_paid END'));
        // Daily Net Sales = (Total Cash Sales - Total Deductions) + Opening Cash
        $netSales = ($totalCashSales - $totalDeductions) + $openingCash;
        
        // Expected Cash in Drawer = (Opening Cash + Today's Cash Sales) - Today's Deductions
        $expectedCash = ($openingCash + $totalCashSales) - $totalDeductions;

        return view('animalbite.dashboard', [
            'title' => 'Dashboard - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'stats' => [
                'patients' => $totalPatients,
                'sales' => $totalSales,
                'deductions' => $totalDeductions,
                'cash_sales' => $totalCashSales,
                'net_sales' => $netSales,
                'expected_cash' => $expectedCash,
                'online_sales' => $totalOnlineSales,
                'opening_cash' => $openingCash
            ],
            'isEditable' => $isEditable,
            'selectedDate' => $date
        ]);
    }

    public function cashOnHand()
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        $branch = auth()->user()->is_super_admin ? session('selected_branch', 'All Branches') : auth()->user()->branch;
        
        $opening = CashRecord::where('date', $date)->where('shift', 'opening')->first();
        $closing = CashRecord::where('date', $date)->where('shift', 'closing')->first();
        
        // Carry-over logic for Opening if it doesn't exist
        if (!$opening) {
            $prevDate = Carbon::parse($date)->subDay()->toDateString();
            $prevClosing = CashRecord::where('date', $prevDate)->where('shift', 'closing')->first();
            if ($prevClosing) {
                // Pre-fill opening with previous closing values
                $opening = new CashRecord($prevClosing->toArray());
                $opening->date = $date;
                $opening->shift = 'opening';
                $opening->nurse_on_duty = null; // Reset so it auto-fills with the current logged-in nurse
                $opening->exists = false; // Don't save it yet
            }
        }

        $openingCash = $opening ? $opening->total_amount : 0;

        return view('animalbite.cash-on-hand', [
            'title' => 'Cash on Hand - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'opening' => $opening,
            'closing' => $closing,
            'openingCash' => $openingCash,
            'isEditable' => false,
            'selectedDate' => $date
        ]);
    }

    public function storeCashRecord(Request $request)
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        $validated = $request->validate([
            'shift' => 'required|in:opening,closing',
            'nurse_on_duty' => 'nullable|string',
            'denom_1000' => 'nullable|integer|min:0',
            'denom_500' => 'nullable|integer|min:0',
            'denom_200' => 'nullable|integer|min:0',
            'denom_100' => 'nullable|integer|min:0',
            'denom_50' => 'nullable|integer|min:0',
            'denom_20' => 'nullable|integer|min:0',
            'coin_20' => 'nullable|integer|min:0',
            'denom_10' => 'nullable|integer|min:0',
            'denom_5' => 'nullable|integer|min:0',
            'denom_1' => 'nullable|integer|min:0',
            'remarks' => 'nullable|string',
            'total_amount' => 'required|numeric',
        ]);

        $branch = auth()->user()->is_super_admin ? session('selected_branch', 'All Branches') : auth()->user()->branch;
        if ($branch === 'All Branches') {
            $branch = 'Mandaue Branch'; // Fallback
        }
        $validated['branch'] = $branch;

        // Default any null inputs to 0
        foreach (['denom_1000', 'denom_500', 'denom_200', 'denom_100', 'denom_50', 'denom_20', 'coin_20', 'denom_10', 'denom_5', 'denom_1'] as $denomField) {
            $validated[$denomField] = $validated[$denomField] ?? 0;
        }

        $cashRecord = CashRecord::updateOrCreate(
            ['date' => $date, 'shift' => $validated['shift'], 'branch' => $branch],
            $validated
        );

        // Sync with DailyRecord for reporting compatibility
        if ($validated['shift'] === 'opening') {
            $dailyRecord = DailyRecord::firstOrCreate(['date' => $date, 'branch' => $branch]);
            $dailyRecord->update(['opening_cash' => $cashRecord->total_amount]);
        }

        return redirect()->back()->with('success', 'Cash record saved successfully!');
    }

    public function cashTracking()
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        $branch = auth()->user()->is_super_admin ? session('selected_branch', 'All Branches') : auth()->user()->branch;
        
        if ($branch !== 'All Branches') {
            $openingRecord = CashRecord::where('date', $date)->where('shift', 'opening')->first();
            if ($openingRecord) {
                $openingAmount = $openingRecord->total_amount;
            } else {
                $prevDate = Carbon::parse($date)->subDay()->toDateString();
                $prevClosing = CashRecord::where('date', $prevDate)->where('shift', 'closing')->first();
                $openingAmount = $prevClosing ? $prevClosing->total_amount : 0;
            }
        } else {
            $openingAmount = CashRecord::where('date', $date)->where('shift', 'opening')->sum('total_amount');
            if ($openingAmount == 0) {
                $prevDate = Carbon::parse($date)->subDay()->toDateString();
                $openingAmount = CashRecord::where('date', $prevDate)->where('shift', 'closing')->sum('total_amount');
            }
        }
        
        // Today's Totals
        $totalCashSales = MasterlistEntry::whereDate('created_at', $date)
            ->where(function($q) {
                $q->where('payment_method', 'CASH')
                  ->orWhere('payment_method', 'SPLIT');
            })
            ->when($branch !== 'All Branches', function($q) use ($branch) {
                return $q->where('branch', $branch);
            })
            ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN cash_amount ELSE amount_paid END'));
            
        $totalDeductions = Deduction::whereDate('date', $date)
            ->when($branch !== 'All Branches', function($q) use ($branch) {
                return $q->where('branch', $branch);
            })
            ->sum('amount');
            
        $expectedCash = ($openingAmount + $totalCashSales) - $totalDeductions;
        
        if ($branch === 'All Branches') {
            $closingAmount = CashRecord::where('date', $date)->where('shift', 'closing')->sum('total_amount');
            $hasClosing = CashRecord::where('date', $date)->where('shift', 'closing')->where('total_amount', '>', 0)->exists();
            $closingAmountDisplay = $hasClosing ? $closingAmount : null;
        } else {
            $closingRecord = CashRecord::where('date', $date)->where('shift', 'closing')->first();
            $closingAmountDisplay = ($closingRecord && $closingRecord->total_amount > 0) ? $closingRecord->total_amount : null;
        }

        $variance = $closingAmountDisplay !== null ? ($closingAmountDisplay - $expectedCash) : 0;

        // Get actual opening and closing records to display their denominations
        if ($branch !== 'All Branches') {
            $openingTallyRecord = CashRecord::where('date', $date)->where('shift', 'opening')->first();
            $closingTallyRecord = CashRecord::where('date', $date)->where('shift', 'closing')->first();
            
            // Carry-over for opening tally if it doesn't exist
            if (!$openingTallyRecord) {
                $prevDate = Carbon::parse($date)->subDay()->toDateString();
                $openingTallyRecord = CashRecord::where('date', $prevDate)->where('shift', 'closing')->first();
            }
        } else {
            // For All Branches, we can sum them up to show a consolidated tally
            $openingTallies = CashRecord::where('date', $date)->where('shift', 'opening')->get();
            if ($openingTallies->isEmpty()) {
                $prevDate = Carbon::parse($date)->subDay()->toDateString();
                $openingTallies = CashRecord::where('date', $prevDate)->where('shift', 'closing')->get();
            }
            
            $closingTallies = CashRecord::where('date', $date)->where('shift', 'closing')->get();
            
            // Consolidate
            $openingTallyRecord = (object)[
                'denom_1000' => $openingTallies->sum('denom_1000'),
                'denom_500' => $openingTallies->sum('denom_500'),
                'denom_200' => $openingTallies->sum('denom_200'),
                'denom_100' => $openingTallies->sum('denom_100'),
                'denom_50' => $openingTallies->sum('denom_50'),
                'denom_20' => $openingTallies->sum('denom_20'),
                'coin_20' => $openingTallies->sum('coin_20'),
                'denom_10' => $openingTallies->sum('denom_10'),
                'denom_5' => $openingTallies->sum('denom_5'),
                'denom_1' => $openingTallies->sum('denom_1'),
            ];
            
            $closingTallyRecord = (object)[
                'denom_1000' => $closingTallies->sum('denom_1000'),
                'denom_500' => $closingTallies->sum('denom_500'),
                'denom_200' => $closingTallies->sum('denom_200'),
                'denom_100' => $closingTallies->sum('denom_100'),
                'denom_50' => $closingTallies->sum('denom_50'),
                'denom_20' => $closingTallies->sum('denom_20'),
                'coin_20' => $closingTallies->sum('coin_20'),
                'denom_10' => $closingTallies->sum('denom_10'),
                'denom_5' => $closingTallies->sum('denom_5'),
                'denom_1' => $closingTallies->sum('denom_1'),
            ];
        }

        if (!$openingTallyRecord) {
            $openingTallyRecord = (object)[
                'denom_1000' => 0, 'denom_500' => 0, 'denom_200' => 0, 'denom_100' => 0,
                'denom_50' => 0, 'denom_20' => 0, 'coin_20' => 0, 'denom_10' => 0, 'denom_5' => 0, 'denom_1' => 0
            ];
        }
        if (!$closingTallyRecord) {
            $closingTallyRecord = (object)[
                'denom_1000' => 0, 'denom_500' => 0, 'denom_200' => 0, 'denom_100' => 0,
                'denom_50' => 0, 'denom_20' => 0, 'coin_20' => 0, 'denom_10' => 0, 'denom_5' => 0, 'denom_1' => 0
            ];
        }

        return view('animalbite.cash-tracking', [
            'title' => 'Cash on Hand - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'date' => $date,
            'openingAmount' => $openingAmount,
            'totalCashSales' => $totalCashSales,
            'totalDeductions' => $totalDeductions,
            'expectedCash' => $expectedCash,
            'closingAmount' => $closingAmountDisplay,
            'variance' => $variance,
            'selectedDate' => $date,
            'openingTally' => $openingTallyRecord,
            'closingTally' => $closingTallyRecord,
            'selectedBranch' => $branch,
            'isEditable' => false
        ]);
    }

    public function patients()
    {
        $patients = Patient::latest()->get();
        return view('animalbite.patients', [
            'title' => 'Patient Management - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'patients' => $patients
        ]);
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);

        Patient::create($validated);

        return redirect()->back()->with('success', 'Patient added successfully!');
    }

    public function masterlist()
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        $entries = MasterlistEntry::with('patient')->whereDate('created_at', $date)->latest()->get();
        $patients = Patient::orderBy('name')->get();

        // Get IDs of entries with pending/approved requests for highlighting
        $entryIds = $entries->pluck('id')->toArray();
        $pendingRequests = ApprovalRequest::whereIn('model_id', $entryIds)
            ->where('model_type', MasterlistEntry::class)
            ->where('status', 'pending')
            ->pluck('model_id')
            ->toArray();
            
        $approvedRequests = ApprovalRequest::whereIn('model_id', $entryIds)
            ->where('model_type', MasterlistEntry::class)
            ->where('status', 'approved')
            ->where('updated_at', '>=', Carbon::now()->subHours(24))
            ->pluck('model_id')
            ->toArray();

        return view('animalbite.masterlist', [
            'title' => 'Masterlist - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'entries' => $entries,
            'patients' => $patients,
            'selectedDate' => $date,
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
        ]);
    }

    public function storeEntry(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'time' => 'required',
            'dose_received' => 'required|string',
            'animal_status' => 'nullable|string',
            'amount_paid' => 'required|numeric',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'remarks' => 'nullable|string',
            'is_discounted' => 'nullable|boolean',
            'discount_type' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric',
            'original_amount' => 'nullable|numeric',
            'is_split_payment' => 'nullable|boolean',
            'cash_amount' => 'nullable|numeric',
            'online_amount' => 'nullable|numeric',
            'online_payment_method' => 'nullable|string',
        ]);

        $validated['is_discounted'] = $request->has('is_discounted');
        $validated['is_split_payment'] = $request->has('is_split_payment');

        if ($validated['is_split_payment']) {
            $validated['payment_method'] = 'SPLIT';
        }

        // Use the session date for the entry creation if it's the current date,
        // but typically entries are for "Today".
        // However, if the user wants to record data for the selected date:
        $date = session('selected_date', Carbon::today()->toDateString());
        $validated['created_at'] = Carbon::parse($date)->setTimeFrom(Carbon::now());
        
        // Record the nurse who inputted the entry
        $validated['nurse'] = auth()->user()->display_name;
        $validated['branch'] = auth()->user()->branch;

        MasterlistEntry::create($validated);

        return redirect()->back()->with('success', 'Masterlist entry added successfully!');
    }

    public function updateEntry(Request $request, MasterlistEntry $entry)
    {
        $rules = [
            'patient_id' => 'required|exists:patients,id',
            'time' => 'required',
            'dose_received' => 'required|string',
            'animal_status' => 'nullable|string',
            'amount_paid' => 'required|numeric',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'remarks' => 'nullable|string',
            'is_discounted' => 'nullable|boolean',
            'discount_type' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric',
            'original_amount' => 'nullable|numeric',
            'is_split_payment' => 'nullable|boolean',
            'cash_amount' => 'nullable|numeric',
            'online_amount' => 'nullable|numeric',
            'online_payment_method' => 'nullable|string',
        ];

        $validated = $request->validate($rules);
        $validated['is_discounted'] = $request->has('is_discounted');
        $validated['is_split_payment'] = $request->has('is_split_payment');

        if ($validated['is_split_payment']) {
            $validated['payment_method'] = 'SPLIT';
        }

        if (auth()->user()->position !== 'Super Admin') {
            ApprovalRequest::create([
                'model_type' => MasterlistEntry::class,
                'model_id' => $entry->id,
                'action' => 'edit',
                'user_id' => auth()->id(),
                'nurse_name' => auth()->user()->display_name,
                'branch' => auth()->user()->branch,
                'reason' => $request->reason,
                'old_data' => $entry->load('patient')->toArray(),
                'new_data' => $validated,
                'status' => 'pending',
            ]);
            return redirect()->back()->with('info', 'Your edit request has been sent for approval.');
        }

        $entry->update($validated);
        return redirect()->back()->with('success', 'Masterlist entry updated successfully!');
    }

    public function destroyEntry(Request $request, MasterlistEntry $entry)
    {
        if (auth()->user()->position !== 'Super Admin') {
            $request->validate(['reason' => 'required|string']);
            
            ApprovalRequest::create([
                'model_type' => MasterlistEntry::class,
                'model_id' => $entry->id,
                'action' => 'delete',
                'user_id' => auth()->id(),
                'nurse_name' => auth()->user()->display_name,
                'branch' => auth()->user()->branch,
                'reason' => $request->reason,
                'old_data' => $entry->load('patient')->toArray(),
                'new_data' => null,
                'status' => 'pending',
            ]);
            return redirect()->back()->with('info', 'Your delete request has been sent for approval.');
        }

        $entry->delete();
        return redirect()->back()->with('success', 'Masterlist entry deleted successfully!');
    }

    public function updatePatient(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|string',
            'barangay' => 'required|string',
            'city' => 'required|string',
            'contact_number' => 'required|string',
        ]);

        $patient->update($validated);
        return redirect()->back()->with('success', 'Patient updated successfully!');
    }

    public function destroyPatient(Patient $patient)
    {
        $patient->delete();
        return redirect()->back()->with('success', 'Patient deleted successfully!');
    }

    public function storeDeduction(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'released_by' => 'required|string',
            'amount' => 'required|numeric',
            'released_to' => 'required|string'
        ]);

        $date = session('selected_date', Carbon::today()->toDateString());
        // Determine the correct branch
        if (auth()->user()->is_super_admin) {
            $branch = session('selected_branch', 'All Branches');
            if ($branch === 'All Branches') {
                $branch = 'Mandaue Branch'; // Fallback for Super Admin in All Branches mode
            }
        } else {
            $branch = auth()->user()->branch;
        }

        $data = [
            'description' => $validated['description'],
            'released_by' => $validated['released_by'],
            'amount' => $validated['amount'],
            'released_to' => $validated['released_to'],
            'date' => $date,
            'branch' => $branch
        ];


        Deduction::create($data);

        return redirect()->back()->with('success', 'Deduction added successfully!');
    }

    public function updateDeduction(Request $request, Deduction $deduction)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'released_by' => 'required|string',
            'amount' => 'required|numeric',
            'released_to' => 'required|string'
        ]);

        $data = $validated;
        


        $deduction->update($data);

        return redirect()->back()->with('success', 'Deduction updated successfully!');
    }

    public function deleteDeduction(Deduction $deduction)
    {
        $deduction->delete();
        return redirect()->back()->with('success', 'Deduction deleted successfully!');
    }

    public function updateDailyStats(Request $request)
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        $dailyRecord = DailyRecord::firstOrCreate(['date' => $date]);

        if ($request->has('online_sales')) {
            $dailyRecord->update(['online_sales' => $request->online_sales]);
        }

        return redirect()->back()->with('success', 'Daily stats updated!');
    }

    public function updateOpeningCash(Request $request)
    {
        $request->validate([
            'opening_cash' => 'required|numeric|min:0'
        ]);

        $date = session('selected_date', Carbon::today()->toDateString());
        $dailyRecord = DailyRecord::firstOrCreate(['date' => $date]);
        $dailyRecord->update(['opening_cash' => $request->opening_cash]);

        return redirect()->back()->with('success', 'Opening cash updated successfully!');
    }

    public function inventory()
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        
        $opening = Inventory::with('entries')->where('date', $date)->where('shift', 'opening')->first();
        $closing = Inventory::with('entries')->where('date', $date)->where('shift', 'closing')->first();
        $endorsement = Inventory::with('entries')->where('date', $date)->where('shift', 'endorsement')->first();
        
        // Carry-over logic for Opening if it doesn't exist
        if (!$opening) {
            $prevDate = Carbon::parse($date)->subDay()->toDateString();
            $prevClosing = Inventory::with('entries')->where('date', $prevDate)->where('shift', 'closing')->first();
            if ($prevClosing) {
                // Pre-fill opening with previous closing entries
                $opening = new Inventory(['date' => $date, 'shift' => 'opening']);
                $opening->exists = false;
                $opening->setRelation('entries', $prevClosing->entries->map(function(InventoryEntry $entry) {
                    return new InventoryEntry([
                        'vaccine_name' => $entry->vaccine_name,
                        'quantity' => $entry->quantity,
                        'received' => null,
                        'transferred' => 0,
                        'used' => 0
                    ]);
                }));
            }
        }

        return view('animalbite.inventory', [
            'title' => 'Vaccination Inventory - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'opening' => $opening,
            'closing' => $closing,
            'endorsement' => $endorsement,
            'selectedDate' => $date
        ]);
    }

    public function storeInventory(Request $request)
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        $validated = $request->validate([
            'shift' => 'required|in:opening,closing,endorsement',
            'entries' => 'required|array',
            'entries.*.vaccine_name' => 'required|string',
            'entries.*.quantity' => 'required|integer',
            'entries.*.received' => 'nullable|string',
            'entries.*.transferred' => 'required|integer',
            'entries.*.used' => 'required|integer',
        ]);

        $inventory = Inventory::updateOrCreate(
            ['date' => $date, 'shift' => $validated['shift']]
        );

        foreach ($validated['entries'] as $entryData) {
            $inventory->entries()->updateOrCreate(
                ['vaccine_name' => $entryData['vaccine_name']],
                $entryData
            );
        }

        return redirect()->back()->with('success', 'Inventory saved successfully!');
    }

    public function monthlyReport(Request $request)
    {
        if (!auth()->user()->is_super_admin) {
            abort(403);
        }

        $month = $request->get('month', Carbon::now()->format('m'));
        $year = $request->get('year', Carbon::now()->format('Y'));
        
        $selectedBranch = session('selected_branch', 'All Branches');

        $query = MasterlistEntry::query()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        $deductionsQuery = Deduction::query()
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        $dailyRecordsQuery = DailyRecord::query()
            ->whereMonth('date', $month)
            ->whereYear('date', $year);

        $results = [];

        if ($selectedBranch === 'All Branches') {
            // Group by branch
            $salesData = $query->selectRaw('branch, SUM(amount_paid) as total_sales, COUNT(*) as total_patients')
                ->groupBy('branch')
                ->get()
                ->keyBy('branch');

            $deductionsData = $deductionsQuery->selectRaw('branch, SUM(amount) as total_deductions')
                ->groupBy('branch')
                ->get()
                ->keyBy('branch');

            $onlineSalesData = MasterlistEntry::query()
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where(function($q) {
                    $q->whereIn('payment_method', ['GCASH', 'GCASH1', 'GCASH2', 'GCASH3', 'GCASH4', 'BPI', 'BDO', 'GOTYME', 'MARIBANK', 'MAYA'])
                      ->orWhere('payment_method', 'SPLIT');
                })
                ->selectRaw('branch, SUM(CASE WHEN payment_method = "SPLIT" THEN online_amount ELSE amount_paid END) as total_online_sales')
                ->groupBy('branch')
                ->get()
                ->keyBy('branch');

            $branches = [
                'Mandaue Branch', 'Lapu-Lapu Branch', 'Balamban Branch', 'Talisay Branch', 
                'Bogo Branch', 'Tubigon Branch', 'Guadalupe Branch', 'Inabanga Branch', 
                'Tagbilaran Branch', 'Talibon Branch', 'Camotes Branch', 'Consolacion Branch', 
                'Carmen Branch', 'Panglao Branch', 'Liloan Branch', 'Jagna Branch', 'Ubay Branch',
                'Carreta Branch'
            ];

            foreach ($branches as $branch) {
                if ($salesData->has($branch) || $deductionsData->has($branch) || $onlineSalesData->has($branch)) {
                    $results[$branch] = [
                        'sales' => $salesData->get($branch)->total_sales ?? 0,
                        'patients' => $salesData->get($branch)->total_patients ?? 0,
                        'deductions' => $deductionsData->get($branch)->total_deductions ?? 0,
                        'online_sales' => $onlineSalesData->get($branch)->total_online_sales ?? 0,
                    ];
                }
            }
            
            $totalSales = $salesData->sum('total_sales');
            $totalPatients = $salesData->sum('total_patients');
            $totalDeductions = $deductionsData->sum('total_deductions');
            $totalOnlineSales = $onlineSalesData->sum('total_online_sales');

        } else {
            // Single branch (already filtered by scope)
            $totalSales = $query->sum('amount_paid');
            $totalPatients = $query->count();
            $totalDeductions = $deductionsQuery->sum('amount');
            
            $totalOnlineSales = MasterlistEntry::query()
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where(function($q) {
                    $q->whereIn('payment_method', ['GCASH', 'GCASH1', 'GCASH2', 'GCASH3', 'GCASH4', 'BPI', 'BDO', 'GOTYME', 'MARIBANK', 'MAYA'])
                      ->orWhere('payment_method', 'SPLIT');
                })
                ->when($selectedBranch !== 'All Branches', function($q) use ($selectedBranch) {
                    return $q->where('branch', $selectedBranch);
                })
                ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN online_amount ELSE amount_paid END'));
        }

        $totalCashSales = MasterlistEntry::query()
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where(function($q) {
                $q->where('payment_method', 'CASH')
                  ->orWhere('payment_method', 'SPLIT');
            })
            ->when($selectedBranch !== 'All Branches', function($q) use ($selectedBranch) {
                return $q->where('branch', $selectedBranch);
            })
            ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN cash_amount ELSE amount_paid END'));

        $netSales = ($totalCashSales - $totalDeductions);

        return view('animalbite.monthly-report', [
            'title' => 'Monthly Report - ' . Carbon::create()->month($month)->format('F') . ' ' . $year,
            'role' => auth()->user()->position,
            'sidebar' => 'animal-bite',
            'month' => $month,
            'year' => $year,
            'results' => $results,
            'totalSales' => $totalSales,
            'totalPatients' => $totalPatients,
            'totalDeductions' => $totalDeductions,
            'totalOnlineSales' => $totalOnlineSales,
            'totalCashSales' => $totalCashSales,
            'netSales' => $netSales,
            'selectedBranch' => $selectedBranch
        ]);
    }

    public function exportDailyReport()
    {
        if (!auth()->user()->is_super_admin) {
            abort(403);
        }

        $date = session('selected_date', Carbon::today()->toDateString());
        $branch = session('selected_branch', 'All Branches');
        $fileName = "Daily_Report_{$branch}_{$date}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($date, $branch) {
            $file = fopen('php://output', 'w');
            
            // Financial Summary Section
            fputcsv($file, ["DAILY REPORT SUMMARY - {$branch} - {$date}"]);
            fputcsv($file, []);

            $query = MasterlistEntry::whereDate('created_at', $date);
            $deductionsQuery = Deduction::whereDate('date', $date);
            $dailyRecordsQuery = DailyRecord::whereDate('date', $date);

            if ($branch !== 'All Branches') {
                $query->where('branch', $branch);
                $deductionsQuery->where('branch', $branch);
                $dailyRecordsQuery->where('branch', $branch);
            }

            $totalSales = $query->sum('amount_paid');
            $totalPatients = $query->count();
            $totalDeductions = $deductionsQuery->sum('amount');
            
            $totalOnlineSales = MasterlistEntry::whereDate('created_at', $date)
                ->where(function($q) {
                    $q->whereIn('payment_method', ['GCASH', 'GCASH1', 'GCASH2', 'GCASH3', 'GCASH4', 'BPI', 'BDO', 'GOTYME', 'MARIBANK', 'MAYA'])
                      ->orWhere('payment_method', 'SPLIT');
                })
                ->when($branch !== 'All Branches', function($q) use ($branch) {
                    return $q->where('branch', $branch);
                })
                ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN online_amount ELSE amount_paid END'));
            
            $totalCashSales = MasterlistEntry::whereDate('created_at', $date)
                ->where(function($q) {
                    $q->where('payment_method', 'CASH')
                      ->orWhere('payment_method', 'SPLIT');
                })
                ->when($branch !== 'All Branches', function($q) use ($branch) {
                    return $q->where('branch', $branch);
                })
                ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN cash_amount ELSE amount_paid END'));
            
            if ($branch !== 'All Branches') {
                $dailyRecord = DailyRecord::firstOrCreate(['date' => $date]);
                if ($dailyRecord->wasRecentlyCreated && $dailyRecord->opening_cash == 0) {
                    $historicalSales = MasterlistEntry::where('created_at', '<', $date)
                        ->where(function($q) {
                            $q->where('payment_method', 'CASH')
                              ->orWhere('payment_method', 'SPLIT');
                        })
                        ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN cash_amount ELSE amount_paid END'));
                        
                    $historicalDeductions = Deduction::where('date', '<', $date)
                        ->sum('amount');
                        
                    $dailyRecord->update(['opening_cash' => $historicalSales - $historicalDeductions]);
                }
                $openingCash = $dailyRecord->opening_cash;
            } else {
                $historicalSales = MasterlistEntry::where('created_at', '<', $date)
                    ->where(function($q) {
                        $q->where('payment_method', 'CASH')
                          ->orWhere('payment_method', 'SPLIT');
                    })
                    ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN cash_amount ELSE amount_paid END'));
                    
                $historicalDeductions = Deduction::where('date', '<', $date)
                    ->sum('amount');
                    
                $openingCash = $historicalSales - $historicalDeductions;
            }
            
            $netSales = ($totalCashSales - $totalDeductions) + $openingCash;
            $expectedCash = ($openingCash + $totalCashSales) - $totalDeductions;

            fputcsv($file, ["Metric", "Value"]);
            fputcsv($file, ["Total Patients", $totalPatients]);
            fputcsv($file, ["Total Sales", "P " . number_format($totalSales, 2)]);
            fputcsv($file, ["Total Online Sales", "P " . number_format($totalOnlineSales, 2)]);
            fputcsv($file, ["Total Cash Sales (Cash Payment only)", "P " . number_format($totalCashSales, 2)]);
            fputcsv($file, ["Opening Cash Today", "P " . number_format($openingCash, 2)]);
            fputcsv($file, ["Total Deductions", "P " . number_format($totalDeductions, 2)]);
            fputcsv($file, ["Net Sales (Cash Sales - Deductions + Opening)", "P " . number_format($netSales, 2)]);
            fputcsv($file, ["Expected Cash on Hand (Cash Sales - Deductions + Opening)", "P " . number_format($expectedCash, 2)]);
            fputcsv($file, []);
            fputcsv($file, []);

            // Detailed Transactions Section
            fputcsv($file, ["DETAILED TRANSACTIONS"]);
            fputcsv($file, ["Patient Name", "Time", "Dose", "Amount Paid", "Payment Method", "Reference Number", "Nurse", "Branch"]);

            $entriesQuery = MasterlistEntry::with('patient')->whereDate('created_at', $date);
            if ($branch !== 'All Branches') {
                $entriesQuery->where('branch', $branch);
            }
            
            foreach ($entriesQuery->get() as $entry) {
                fputcsv($file, [
                    $entry->patient->name ?? 'N/A',
                    $entry->time,
                    $entry->dose_received,
                    $entry->amount_paid,
                    $entry->payment_method,
                    $entry->reference_number ?? 'N/A',
                    $entry->nurse ?? 'N/A',
                    $entry->branch
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportMonthlyReport(Request $request)
    {
        if (!auth()->user()->is_super_admin) {
            abort(403);
        }

        $month = $request->get('month', Carbon::now()->format('m'));
        $year = $request->get('year', Carbon::now()->format('Y'));
        $branch = session('selected_branch', 'All Branches');
        $fileName = "Monthly_Report_{$branch}_{$month}_{$year}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($month, $year, $branch) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ["MONTHLY REPORT - {$branch} - Month: {$month} Year: {$year}"]);
            fputcsv($file, []);

            $query = MasterlistEntry::whereMonth('created_at', $month)->whereYear('created_at', $year);
            $deductionsQuery = Deduction::whereMonth('date', $month)->whereYear('date', $year);
            $dailyRecordsQuery = DailyRecord::whereMonth('date', $month)->whereYear('date', $year);

            if ($branch !== 'All Branches') {
                $query->where('branch', $branch);
                $deductionsQuery->where('branch', $branch);
                $dailyRecordsQuery->where('branch', $branch);
            }

            // Summary Totals
            $totalSales = $query->sum('amount_paid');
            $totalPatients = $query->count();
            $totalDeductions = $deductionsQuery->sum('amount');
            
            $totalOnlineSales = MasterlistEntry::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where(function($q) {
                    $q->whereIn('payment_method', ['GCASH', 'GCASH1', 'GCASH2', 'GCASH3', 'GCASH4', 'BPI', 'BDO', 'GOTYME', 'MARIBANK', 'MAYA'])
                      ->orWhere('payment_method', 'SPLIT');
                })
                ->when($branch !== 'All Branches', function($q) use ($branch) {
                    return $q->where('branch', $branch);
                })
                ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN online_amount ELSE amount_paid END'));
            
            $totalCashSales = MasterlistEntry::whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->where(function($q) {
                    $q->where('payment_method', 'CASH')
                      ->orWhere('payment_method', 'SPLIT');
                })
                ->when($branch !== 'All Branches', function($q) use ($branch) {
                    return $q->where('branch', $branch);
                })
                ->sum(\DB::raw('CASE WHEN payment_method = "SPLIT" THEN cash_amount ELSE amount_paid END'));
                
            $netSales = $totalCashSales - $totalDeductions;

            fputcsv($file, ["MONTHLY SUMMARY"]);
            fputcsv($file, ["Metric", "Value"]);
            fputcsv($file, ["Total Patients", $totalPatients]);
            fputcsv($file, ["Total Sales", "P " . number_format($totalSales, 2)]);
            fputcsv($file, ["Total Online Sales", "P " . number_format($totalOnlineSales, 2)]);
            fputcsv($file, ["Total Cash Sales", "P " . number_format($totalCashSales, 2)]);
            fputcsv($file, ["Total Deductions", "P " . number_format($totalDeductions, 2)]);
            fputcsv($file, ["Net Monthly Sales", "P " . number_format($netSales, 2)]);
            fputcsv($file, []);
            fputcsv($file, []);

            if ($branch === 'All Branches') {
                fputcsv($file, ["BRANCH BREAKDOWN"]);
                fputcsv($file, ["Branch Name", "Total Patients", "Total Sales", "Online Sales", "Deductions", "Net Sales"]);

                $salesData = $query->selectRaw('branch, SUM(amount_paid) as total_sales, COUNT(*) as total_patients')->groupBy('branch')->get()->keyBy('branch');
                $deductionsData = $deductionsQuery->selectRaw('branch, SUM(amount) as total_deductions')->groupBy('branch')->get()->keyBy('branch');
                $onlineSalesData = $dailyRecordsQuery->selectRaw('branch, SUM(online_sales) as total_online_sales')->groupBy('branch')->get()->keyBy('branch');

                $branches = ['Mandaue Branch', 'Lapu-Lapu Branch', 'Balamban Branch', 'Talisay Branch', 'Bogo Branch', 'Tubigon Branch', 'Guadalupe Branch', 'Inabanga Branch', 'Tagbilaran Branch', 'Talibon Branch', 'Camotes Branch', 'Consolacion Branch', 'Carmen Branch', 'Panglao Branch', 'Liloan Branch', 'Jagna Branch', 'Ubay Branch', 'Carreta Branch'];
                foreach ($branches as $b) {
                    if ($salesData->has($b) || $deductionsData->has($b)) {
                        $s = $salesData->get($b)->total_sales ?? 0;
                        $d = $deductionsData->get($b)->total_deductions ?? 0;
                        fputcsv($file, [
                            $b,
                            $salesData->get($b)->total_patients ?? 0,
                            $s,
                            $onlineSalesData->get($b)->total_online_sales ?? 0,
                            $d,
                            $s - $d
                        ]);
                    }
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function approvalQueue()
    {
        if (auth()->user()->position !== 'Super Admin') {
            abort(403);
        }

        $pending = ApprovalRequest::where('status', 'pending')->latest()->get();
        $history = ApprovalRequest::where('status', '!=', 'pending')->latest()->limit(50)->get();

        return view('animalbite.approval-queue', [
            'title' => 'Approval Queue - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'pending' => $pending,
            'history' => $history,
        ]);
    }

    public function approveRequest(ApprovalRequest $approvalRequest)
    {
        if (auth()->user()->position !== 'Super Admin') {
            abort(403);
        }

        if ($approvalRequest->model_type === MasterlistEntry::class) {
            $entry = \App\Models\MasterlistEntry::withoutGlobalScopes()->find($approvalRequest->model_id);
            if ($entry) {
                if ($approvalRequest->action === 'edit') {
                    $entry->update($approvalRequest->new_data);
                } else if ($approvalRequest->action === 'delete') {
                    $entry->delete();
                }
            }
        }

        $approvalRequest->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Request approved and applied.');
    }

    public function rejectRequest(ApprovalRequest $approvalRequest)
    {
        if (auth()->user()->position !== 'Super Admin') {
            abort(403);
        }

        $approvalRequest->update(['status' => 'rejected']);
        return redirect()->back()->with('warning', 'Request rejected.');
    }
}

