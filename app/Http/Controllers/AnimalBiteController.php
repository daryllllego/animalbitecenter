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
        
        $dailyRecord = DailyRecord::firstOrCreate(['date' => $date]);
        
        // Fetch Yesterday's Closing Cash
        $yesterday = Carbon::parse($date)->subDay()->toDateString();
        $prevClosingRecord = CashRecord::where('date', $yesterday)->where('shift', 'closing')->first();
        $openingCash = $prevClosingRecord ? $prevClosingRecord->total_amount : 0;
        
        $totalPatients = MasterlistEntry::whereDate('created_at', $date)->count();
        $totalSales = MasterlistEntry::whereDate('created_at', $date)->sum('amount_paid');
        $totalDeductions = Deduction::whereDate('date', $date)->sum('amount');
        
        // New Calculations
        $totalCashSales = $totalSales - $dailyRecord->online_sales;
        $netSales = ($totalCashSales - $totalDeductions) + $openingCash;

        return view('animalbite.dashboard', [
            'title' => 'Dashboard - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'stats' => [
                'patients' => $totalPatients,
                'sales' => $totalSales,
                'deductions' => $totalDeductions,
                'cash_sales' => $totalCashSales,
                'expected_cash' => $netSales,
                'online_sales' => $dailyRecord->online_sales,
                'opening_cash' => $openingCash
            ],
            'selectedDate' => $date
        ]);
    }

    public function cashOnHand()
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        
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
                $opening->exists = false; // Don't save it yet
            }
        }

        return view('animalbite.cash-on-hand', [
            'title' => 'Cash on Hand - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'opening' => $opening,
            'closing' => $closing,
            'selectedDate' => $date
        ]);
    }

    public function storeCashRecord(Request $request)
    {
        $date = session('selected_date', Carbon::today()->toDateString());
        $validated = $request->validate([
            'shift' => 'required|in:opening,closing',
            'nurse_on_duty' => 'nullable|string',
            'denom_1000' => 'integer|min:0',
            'denom_500' => 'integer|min:0',
            'denom_200' => 'integer|min:0',
            'denom_100' => 'integer|min:0',
            'denom_50' => 'integer|min:0',
            'denom_20' => 'integer|min:0',
            'denom_10' => 'integer|min:0',
            'denom_5' => 'integer|min:0',
            'denom_1' => 'integer|min:0',
            'remarks' => 'nullable|string',
            'total_amount' => 'required|numeric',
        ]);

        CashRecord::updateOrCreate(
            ['date' => $date, 'shift' => $validated['shift']],
            $validated
        );

        return redirect()->back()->with('success', 'Cash record saved successfully!');
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

        return view('animalbite.masterlist', [
            'title' => 'Masterlist - Animal Bite Center',
            'role' => auth()->user()->position ?? 'Administrator',
            'sidebar' => 'animal-bite',
            'entries' => $entries,
            'patients' => $patients,
            'selectedDate' => $date
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
            'remarks' => 'nullable|string',
        ]);

        // Use the session date for the entry creation if it's the current date,
        // but typically entries are for "Today".
        // However, if the user wants to record data for the selected date:
        $date = session('selected_date', Carbon::today()->toDateString());
        $validated['created_at'] = Carbon::parse($date)->setTimeFrom(Carbon::now());

        MasterlistEntry::create($validated);

        return redirect()->back()->with('success', 'Masterlist entry added successfully!');
    }

    public function storeDeduction(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255',
            'released_by' => 'required|string|max:255',
            'released_to' => 'required|string|max:255',
        ]);

        $validated['date'] = session('selected_date', Carbon::today()->toDateString());

        Deduction::create($validated);

        return redirect()->back()->with('success', 'Deduction added successfully!');
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
                $opening->setRelation('entries', $prevClosing->entries->map(function($entry) {
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

            $onlineSalesData = $dailyRecordsQuery->selectRaw('branch, SUM(online_sales) as total_online_sales')
                ->groupBy('branch')
                ->get()
                ->keyBy('branch');

            $branches = [
                'Mandaue Branch', 'Lapu-Lapu Branch', 'Balamban Branch', 'Talisay Branch', 
                'Bogo Branch', 'Tubigon Branch', 'Guadalupe Branch', 'Inabanga Branch', 
                'Tagbilaran Branch', 'Talibon Branch', 'Camotes Branch', 'Consolacion Branch', 
                'Carmen Branch', 'Panglao Branch', 'Liloan Branch', 'Jagna Branch', 'Ubay Branch'
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
            $totalOnlineSales = $dailyRecordsQuery->sum('online_sales');
            
            $results[$selectedBranch] = [
                'sales' => $totalSales,
                'patients' => $totalPatients,
                'deductions' => $totalDeductions,
                'online_sales' => $totalOnlineSales,
            ];
        }

        $totalCashSales = $totalSales - $totalOnlineSales;
        $netSales = $totalSales - $totalDeductions;

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
            $totalOnlineSales = $dailyRecordsQuery->sum('online_sales');
            $totalCashSales = $totalSales - $totalOnlineSales;
            $netSales = $totalSales - $totalDeductions;

            fputcsv($file, ["Metric", "Value"]);
            fputcsv($file, ["Total Patients", $totalPatients]);
            fputcsv($file, ["Total Sales", "P " . number_format($totalSales, 2)]);
            fputcsv($file, ["Total Online Sales", "P " . number_format($totalOnlineSales, 2)]);
            fputcsv($file, ["Total Cash Sales", "P " . number_format($totalCashSales, 2)]);
            fputcsv($file, ["Total Deductions", "P " . number_format($totalDeductions, 2)]);
            fputcsv($file, ["Net Sales (After Deductions)", "P " . number_format($netSales, 2)]);
            fputcsv($file, []);
            fputcsv($file, []);

            // Detailed Transactions Section
            fputcsv($file, ["DETAILED TRANSACTIONS"]);
            fputcsv($file, ["Patient Name", "Time", "Dose", "Amount Paid", "Payment Method", "Branch"]);

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
            $totalOnlineSales = $dailyRecordsQuery->sum('online_sales');
            $totalCashSales = $totalSales - $totalOnlineSales;
            $netSales = $totalSales - $totalDeductions;

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

                $branches = ['Mandaue Branch', 'Lapu-Lapu Branch', 'Balamban Branch', 'Talisay Branch', 'Bogo Branch', 'Tubigon Branch', 'Guadalupe Branch', 'Inabanga Branch', 'Tagbilaran Branch', 'Talibon Branch', 'Camotes Branch', 'Consolacion Branch', 'Carmen Branch', 'Panglao Branch', 'Liloan Branch', 'Jagna Branch', 'Ubay Branch'];
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
}

