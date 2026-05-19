@extends('layouts.app')

@push('styles')
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .masterlist-header {
            color: #2953e8;
            letter-spacing: 5px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .table-masterlist th {
            background-color: #f1f5f9;
            color: #334155;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-masterlist td {
            font-size: 13px;
            vertical-align: middle;
        }

        .btn-add-entry {
            background-color: #2953e8;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-add-entry:hover {
            background-color: #1a45a1;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(41, 83, 232, 0.3);
        }

        .row-pending td {
            background: #fff9c4 !important;
            /* Solid Yellow */
            color: #5d4037 !important;
            border-top: 1px solid #fbc02d !important;
            border-bottom: 1px solid #fbc02d !important;
        }

        .row-pending td:first-child {
            border-left: 10px solid #fbc02d !important;
        }

        .row-approved td {
            background: #e8f5e9 !important;
            /* Solid Green */
            color: #1b5e20 !important;
            border-top: 1px solid #4caf50 !important;
            border-bottom: 1px solid #4caf50 !important;
        }

        .row-approved td:first-child {
            border-left: 10px solid #4caf50 !important;
        }

        .badge-status-alive {
            background-color: #059669;
            color: white;
            border-radius: 20px;
            padding: 4px 12px;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                    <h2 class="masterlist-header mb-0">MASTERLIST</h2>
                    <button type="button" class="btn btn-add-entry" data-bs-toggle="modal" data-bs-target="#addEntryModal">
                        <i class="fa fa-plus me-2"></i>ADD ENTRY
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="masterlistTable"
                            class="table table-bordered table-hover table-masterlist text-center w-100">
                            <thead>
                                <tr>
                                    <th>TIME</th>
                                    <th>NAME</th>
                                    <th>AGE</th>
                                    <th>GENDER</th>
                                    <th>BARANGGAY</th>
                                    <th>CITY</th>
                                    <th>CONTACT NUMBER</th>
                                    <th>DOSE RECEIVED</th>
                                    <th>STATUS OF ANIMAL</th>
                                    <th>AMOUNT PAID</th>
                                    <th>PAYMENT METHOD</th>
                                    <th>REFERENCE NUMBER</th>
                                    <th>REMARKS</th>
                                    <th>NURSE</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entries as $entry)
                                    <tr
                                        class="{{ in_array($entry->id, $pendingRequests) ? 'row-pending' : (in_array($entry->id, $approvedRequests) ? 'row-approved' : '') }}">
                                        <td data-order="{{ $entry->time }}">{{ \Carbon\Carbon::parse($entry->time)->format('h:i A') }}</td>
                                        <td class="text-start font-weight-bold">{{ $entry->patient->name }}</td>
                                        <td>{{ $entry->patient->age }}</td>
                                        <td>{{ $entry->patient->gender }}</td>
                                        <td>{{ $entry->patient->barangay }}</td>
                                        <td>{{ $entry->patient->city }}</td>
                                        <td>{{ $entry->patient->contact_number }}</td>
                                        <td>{{ $entry->dose_received }}</td>
                                        <td>
                                            @if($entry->animal_status)
                                                <span class="badge-status-alive">{{ strtoupper($entry->animal_status) }}</span>
                                            @endif
                                        </td>
                                        <td>₱ {{ number_format($entry->amount_paid, 2) }}</td>
                                        <td>{{ $entry->payment_method }}</td>
                                        <td class="font-weight-bold text-success">{{ $entry->reference_number ?? 'N/A' }}</td>
                                        <td class="text-start">{{ $entry->remarks }}</td>
                                        <td class="font-weight-bold text-primary">{{ $entry->nurse ?? 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-primary btn-sm rounded-circle edit-entry"
                                                data-bs-toggle="modal" data-bs-target="#editEntryModal"
                                                data-id="{{ $entry->id }}" data-patient-id="{{ $entry->patient_id }}"
                                                data-time="{{ \Carbon\Carbon::parse($entry->time)->format('H:i') }}"
                                                data-dose="{{ $entry->dose_received }}"
                                                data-animal-status="{{ $entry->animal_status }}"
                                                data-amount="{{ $entry->amount_paid }}"
                                                data-payment="{{ $entry->payment_method }}"
                                                data-reference="{{ $entry->reference_number }}"
                                                data-remarks="{{ $entry->remarks }}"
                                                data-is-discounted="{{ $entry->is_discounted }}"
                                                data-discount-type="{{ $entry->discount_type }}"
                                                data-discount-percentage="{{ $entry->discount_percentage }}"
                                                data-original-amount="{{ $entry->original_amount }}"
                                                data-is-split-payment="{{ $entry->is_split_payment }}"
                                                data-cash-amount="{{ $entry->cash_amount }}"
                                                data-online-amount="{{ $entry->online_amount }}"
                                                data-online-payment-method="{{ $entry->online_payment_method }}">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            @if(auth()->user()->position === 'Super Admin')
                                                <form action="{{ route('animal-bite.masterlist.destroy', $entry->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle"
                                                        onclick="return confirm('Delete this entry?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-danger btn-sm rounded-circle request-delete"
                                                    data-bs-toggle="modal" data-bs-target="#deleteRequestModal"
                                                    data-id="{{ $entry->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Entry Modal -->
    <div class="modal fade" id="addEntryModal" tabindex="-1" aria-labelledby="addEntryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="addEntryModalLabel">Add Masterlist Entry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('animal-bite.masterlist.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="patient_id" class="form-label">Select Patient</label>
                                <select class="form-control searchable-select" id="patient_id" name="patient_id" required>
                                    <option value="" selected disabled>-- Choose Patient --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->age }}y/o,
                                            {{ $patient->city }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">If patient is not listed, add them in Patient Management
                                    first.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="time" class="form-label">Time</label>
                                <input type="time" class="form-control" id="time" name="time" required
                                    value="{{ date('H:i') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dose_received" class="form-label">Dose Received</label>
                                <input type="text" class="form-control" id="dose_received" name="dose_received" required
                                    placeholder="e.g. 1st, 2nd, LB">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="animal_status" class="form-label">Status of Animal</label>
                                <input type="text" class="form-control" id="animal_status" name="animal_status"
                                    placeholder="e.g. Alive (optional)">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input split-payment-toggle" type="checkbox"
                                        id="is_split_payment" name="is_split_payment" value="1">
                                    <label class="form-check-label font-weight-bold" for="is_split_payment">Split Payment
                                        (Cash & Online)</label>
                                </div>
                            </div>
                        </div>

                        <div class="split-payment-fields-container" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="cash_amount" class="form-label">Cash Payment</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" class="form-control split-cash-input"
                                            id="cash_amount" name="cash_amount">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="online_amount" class="form-label">Online Payment</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" class="form-control split-online-input"
                                            id="online_amount" name="online_amount">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="online_payment_method" class="form-label">Online Method</label>
                                    <select class="form-control" id="online_payment_method" name="online_payment_method">
                                        <option value="GCASH">GCASH</option>
                                        <option value="GCASH1">GCASH1</option>
                                        <option value="GCASH2">GCASH2</option>
                                        <option value="GCASH3">GCASH3</option>
                                        <option value="GCASH4">GCASH4</option>
                                        <option value="BPI">BPI</option>
                                        <option value="BDO">BDO</option>
                                        <option value="GOTYME">GOTYME</option>
                                        <option value="MARIBANK">MARIBANK</option>
                                        <option value="MAYA">MAYA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount_paid" class="form-label">Amount Paid</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" class="form-control" id="amount_paid"
                                        name="amount_paid" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-control payment-method-select" id="payment_method" name="payment_method"
                                    required>
                                    <option value="CASH">CASH</option>
                                    <option value="GCASH">GCASH</option>
                                    <option value="GCASH1">GCASH1</option>
                                    <option value="GCASH2">GCASH2</option>
                                    <option value="GCASH3">GCASH3</option>
                                    <option value="GCASH4">GCASH4</option>
                                    <option value="BPI">BPI</option>
                                    <option value="BDO">BDO</option>
                                    <option value="GOTYME">GOTYME</option>
                                    <option value="MARIBANK">MARIBANK</option>
                                    <option value="MAYA">MAYA</option>
                                    <option value="SPLIT">SPLIT (CASH & ONLINE)</option>
                                </select>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input discount-toggle" type="checkbox" id="is_discounted"
                                        name="is_discounted" value="1">
                                    <label class="form-check-label font-weight-bold" for="is_discounted">Apply
                                        Discount</label>
                                </div>
                            </div>
                        </div>

                        <div class="discount-fields-container" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="discount_type" class="form-label">Discount For</label>
                                    <select class="form-control" id="discount_type" name="discount_type">
                                        <option value="Senior Citizen">Senior Citizen</option>
                                        <option value="PWD">PWD</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="discount_percentage" class="form-label">Discount Percentage (%)</label>
                                    <input type="number" step="0.01" class="form-control discount-percentage"
                                        id="discount_percentage" name="discount_percentage" value="20">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="discounted_price" class="form-label">Discounted Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" class="form-control discounted-price"
                                            id="discounted_price" readonly>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="original_amount" id="original_amount">
                        </div>
                        <div class="row reference-number-row" style="display: none;">
                            <div class="col-12 mb-3">
                                <label for="reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="reference_number" name="reference_number"
                                    placeholder="Enter reference number">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Entry Modal -->
    <div class="modal fade" id="editEntryModal" tabindex="-1" aria-labelledby="editEntryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="editEntryModalLabel">Edit Masterlist Entry</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editEntryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_patient_id" class="form-label">Select Patient</label>
                                <select class="form-control searchable-select" id="edit_patient_id" name="patient_id"
                                    required>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->age }}y/o,
                                            {{ $patient->city }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_time" class="form-label">Time</label>
                                <input type="time" class="form-control" id="edit_time" name="time" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_dose_received" class="form-label">Dose Received</label>
                                <input type="text" class="form-control" id="edit_dose_received" name="dose_received"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_animal_status" class="form-label">Status of Animal</label>
                                <input type="text" class="form-control" id="edit_animal_status" name="animal_status">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input split-payment-toggle" type="checkbox"
                                        id="edit_is_split_payment" name="is_split_payment" value="1">
                                    <label class="form-check-label font-weight-bold" for="edit_is_split_payment">Split
                                        Payment (Cash & Online)</label>
                                </div>
                            </div>
                        </div>

                        <div class="split-payment-fields-container" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="edit_cash_amount" class="form-label">Cash Payment</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" class="form-control split-cash-input"
                                            id="edit_cash_amount" name="cash_amount">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_online_amount" class="form-label">Online Payment</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" step="0.01" class="form-control split-online-input"
                                            id="edit_online_amount" name="online_amount">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="edit_online_payment_method" class="form-label">Online Method</label>
                                    <select class="form-control" id="edit_online_payment_method"
                                        name="online_payment_method">
                                        <option value="GCASH">GCASH</option>
                                        <option value="GCASH1">GCASH1</option>
                                        <option value="GCASH2">GCASH2</option>
                                        <option value="GCASH3">GCASH3</option>
                                        <option value="GCASH4">GCASH4</option>
                                        <option value="BPI">BPI</option>
                                        <option value="BDO">BDO</option>
                                        <option value="GOTYME">GOTYME</option>
                                        <option value="MARIBANK">MARIBANK</option>
                                        <option value="MAYA">MAYA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_amount_paid" class="form-label">Amount Paid</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" class="form-control" id="edit_amount_paid"
                                        name="amount_paid" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_payment_method" class="form-label">Payment Method</label>
                                <select class="form-control payment-method-select" id="edit_payment_method"
                                    name="payment_method" required>
                                    <option value="CASH">CASH</option>
                                    <option value="GCASH">GCASH</option>
                                    <option value="GCASH1">GCASH1</option>
                                    <option value="GCASH2">GCASH2</option>
                                    <option value="GCASH3">GCASH3</option>
                                    <option value="GCASH4">GCASH4</option>
                                    <option value="BPI">BPI</option>
                                    <option value="BDO">BDO</option>
                                    <option value="GOTYME">GOTYME</option>
                                    <option value="MARIBANK">MARIBANK</option>
                                    <option value="MAYA">MAYA</option>
                                    <option value="SPLIT">SPLIT (CASH & ONLINE)</option>
                                </select>
                            </div>



                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input discount-toggle" type="checkbox"
                                            id="edit_is_discounted" name="is_discounted" value="1">
                                        <label class="form-check-label font-weight-bold" for="edit_is_discounted">Apply
                                            Discount</label>
                                    </div>
                                </div>
                            </div>

                            <div class="discount-fields-container" style="display: none;">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="edit_discount_type" class="form-label">Discount For</label>
                                        <select class="form-control" id="edit_discount_type" name="discount_type">
                                            <option value="Senior Citizen">Senior Citizen</option>
                                            <option value="PWD">PWD</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="edit_discount_percentage" class="form-label">Discount Percentage
                                            (%)</label>
                                        <input type="number" step="0.01" class="form-control discount-percentage"
                                            id="edit_discount_percentage" name="discount_percentage" value="20">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="edit_discounted_price" class="form-label">Discounted Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" step="0.01" class="form-control discounted-price"
                                                id="edit_discounted_price" readonly>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="original_amount" id="edit_original_amount">
                            </div>
                        </div>
                        <div class="row reference-number-row" style="display: none;">
                            <div class="col-12 mb-3">
                                <label for="edit_reference_number" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" id="edit_reference_number" name="reference_number"
                                    placeholder="Enter reference number">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="edit_remarks" name="remarks" rows="2"></textarea>
                        </div>

                        @if(auth()->user()->position !== 'Super Admin')
                            <div class="mb-3">
                                <label for="edit_reason" class="form-label text-danger font-weight-bold">Reason for Edit
                                    (Required for Approval)</label>
                                <textarea class="form-control border-danger" id="edit_reason" name="reason" rows="2" required
                                    placeholder="Explain why you are editing this entry..."></textarea>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Request Modal (Staff) -->
    <div class="modal fade" id="deleteRequestModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title text-white">Request Deletion</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="deleteRequestForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="text-dark">You are about to request the deletion of this entry. This action requires
                            approval from a Super Admin.</p>
                        <div class="mb-3">
                            <label for="delete_reason" class="form-label font-weight-bold">Reason for Deletion</label>
                            <textarea class="form-control border-danger" id="delete_reason" name="reason" rows="3" required
                                placeholder="Explain why this entry should be deleted..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Submit Deletion Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#masterlistTable').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "searching": true,
                "order": [[0, 'desc']],
                "language": {
                    "paginate": {
                        "next": '<i class="fa fa-angle-right"></i>',
                        "previous": '<i class="fa fa-angle-left"></i>'
                    }
                }
            });



            // Initialize Select2
            $('.searchable-select').select2({
                dropdownParent: $('#addEntryModal'),
                width: '100%'
            });

            // Fix Select2 in Edit Modal
            $('#editEntryModal').on('shown.bs.modal', function () {
                $('#edit_patient_id').select2({
                    dropdownParent: $('#editEntryModal'),
                    width: '100%'
                });
            });

            // Discount Logic
            function calculateDiscount(modal) {
                const amountInput = modal.find('input[name="amount_paid"]');
                const isDiscounted = modal.find('.discount-toggle').is(':checked');
                const percentage = parseFloat(modal.find('.discount-percentage').val()) || 0;
                const originalAmountInput = modal.find('input[name="original_amount"]');
                const discountedPriceInput = modal.find('.discounted-price');

                if (isDiscounted) {
                    let baseAmount = parseFloat(originalAmountInput.val()) || parseFloat(amountInput.val()) || 0;

                    if (!originalAmountInput.val()) {
                        originalAmountInput.val(amountInput.val());
                        baseAmount = parseFloat(amountInput.val()) || 0;
                    }

                    const discountedPrice = baseAmount * (1 - (percentage / 100));
                    discountedPriceInput.val(discountedPrice.toFixed(2));
                } else {
                    if (originalAmountInput.val()) {
                        amountInput.val(originalAmountInput.val());
                        originalAmountInput.val('');
                    }
                    discountedPriceInput.val('');
                }
            }

            // Split Payment Logic
            $('.split-payment-toggle').on('change', function () {
                const modal = $(this).closest('.modal-content');
                const container = modal.find('.split-payment-fields-container');
                const paymentSelect = modal.find('.payment-method-select');
                const referenceRow = modal.find('.reference-number-row');

                if ($(this).is(':checked')) {
                    container.slideDown();
                    paymentSelect.val('SPLIT').trigger('change').prop('disabled', true);
                    referenceRow.show().find('input').attr('required', true);

                    // Add hidden input to form if it doesn't exist to ensure payment_method is sent
                    if (modal.find('.payment-method-hidden').length === 0) {
                        modal.find('form').append('<input type="hidden" name="payment_method" value="SPLIT" class="payment-method-hidden">');
                    }
                } else {
                    container.slideUp();
                    paymentSelect.prop('disabled', false).val('CASH').trigger('change');
                    referenceRow.hide().find('input').attr('required', false).val('');
                    modal.find('.payment-method-hidden').remove();
                }
            });

            $('.split-cash-input, .split-online-input').on('input', function () {
                const modal = $(this).closest('.modal-content');
                const cash = parseFloat(modal.find('.split-cash-input').val()) || 0;
                const online = parseFloat(modal.find('.split-online-input').val()) || 0;
                const total = cash + online;

                const amountInput = modal.find('input[name="amount_paid"]');
                amountInput.val(total.toFixed(2)).trigger('input');
            });

            $('.discount-toggle').on('change', function () {
                const modal = $(this).closest('.modal-content');
                const amountInput = modal.find('input[name="amount_paid"]');
                const container = modal.find('.discount-fields-container');

                if ($(this).is(':checked')) {
                    if (!amountInput.val()) {
                        alert('Please enter an amount paid before applying a discount.');
                        $(this).prop('checked', false);
                        return;
                    }
                    container.slideDown();
                } else {
                    container.slideUp();
                }
                calculateDiscount(modal);
            });

            $('.discount-percentage').on('input', function () {
                const modal = $(this).closest('.modal-content');
                calculateDiscount(modal);
            });

            $('input[name="amount_paid"]').on('input', function () {
                const modal = $(this).closest('.modal-content');
                const isDiscounted = modal.find('.discount-toggle').is(':checked');
                if (isDiscounted) {
                    modal.find('input[name="original_amount"]').val($(this).val());
                    const percentage = parseFloat(modal.find('.discount-percentage').val()) || 0;
                    const baseAmount = parseFloat($(this).val()) || 0;
                    const discountedPrice = baseAmount * (1 - (percentage / 100));
                    modal.find('.discounted-price').val(discountedPrice.toFixed(2));
                }
            });

            $('form').on('submit', function () {
                const modal = $(this).closest('.modal-content');
                const isDiscounted = modal.find('.discount-toggle').is(':checked');
                if (isDiscounted) {
                    const discountedPrice = modal.find('.discounted-price').val();
                    modal.find('input[name="amount_paid"]').val(discountedPrice);
                }
            });

            $(document).on('click', '.edit-entry', function () {
                const id = $(this).data('id');
                const patientId = $(this).data('patient-id');
                const time = $(this).data('time');
                const dose = $(this).data('dose');
                const animalStatus = $(this).data('animal-status');
                const amount = $(this).data('amount');
                const payment = $(this).data('payment');
                const reference = $(this).data('reference');
                const remarks = $(this).data('remarks');
                const isDiscounted = $(this).data('is-discounted');
                const discountType = $(this).data('discount-type');
                const discountPercentage = $(this).data('discount-percentage');
                const originalAmount = $(this).data('original-amount');
                const isSplit = $(this).data('is-split-payment');
                const cashAmount = $(this).data('cash-amount');
                const onlineAmount = $(this).data('online-amount');
                const onlineMethod = $(this).data('online-payment-method');


                $('#edit_patient_id').val(patientId).trigger('change');
                $('#edit_time').val(time);
                $('#edit_dose_received').val(dose);
                $('#edit_animal_status').val(animalStatus);
                $('#edit_amount_paid').val(amount);
                $('#edit_payment_method').val(payment);
                $('#edit_reference_number').val(reference);
                $('#edit_remarks').val(remarks);



                if (isSplit) {
                    $('#edit_is_split_payment').prop('checked', true).trigger('change');
                    $('#edit_cash_amount').val(cashAmount);
                    $('#edit_online_amount').val(onlineAmount);
                    $('#edit_online_payment_method').val(onlineMethod);
                } else {
                    $('#edit_is_split_payment').prop('checked', false).trigger('change');
                    $('#edit_cash_amount').val('');
                    $('#edit_online_amount').val('');
                    $('#edit_online_payment_method').val('GCASH');
                }

                if (isDiscounted) {
                    $('#edit_is_discounted').prop('checked', true);
                    $('#edit_discount_type').val(discountType);
                    $('#edit_discount_percentage').val(discountPercentage);
                    $('#edit_original_amount').val(originalAmount);
                    $('#editEntryModal .discount-fields-container').show();
                    $('#edit_amount_paid').val(originalAmount);
                    const discountedPrice = originalAmount * (1 - (discountPercentage / 100));
                    $('#edit_discounted_price').val(discountedPrice.toFixed(2));
                } else {
                    $('#edit_is_discounted').prop('checked', false);
                    $('#edit_original_amount').val('');
                    $('#editEntryModal .discount-fields-container').hide();
                    $('#edit_discounted_price').val('');
                }

                $('#edit_payment_method').trigger('change');
                $('#editEntryForm').attr('action', `/animal-bite/masterlist/${id}`);
            });

            $(document).on('click', '.request-delete', function () {
                const id = $(this).data('id');
                $('#deleteRequestForm').attr('action', `/animal-bite/masterlist/${id}`);
            });

            $('.payment-method-select').on('change', function () {
                const method = $(this).val();
                const row = $(this).closest('.modal-content').find('.reference-number-row');
                if (['GCASH', 'GCASH1', 'GCASH2', 'GCASH3', 'GCASH4', 'BPI', 'BDO', 'GOTYME', 'MARIBANK', 'MAYA'].includes(method)) {
                    row.show();
                    row.find('input').attr('required', true);
                } else {
                    row.hide();
                    row.find('input').attr('required', false).val('');
                }
            });
        });
    </script>
@endpush