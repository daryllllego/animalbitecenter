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
    .badge-status-alive {
        background-color: #059669;
        color: white;
        border-radius: 20px;
        padding: 4px 12px;
    }
</style>
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
                    <table id="masterlistTable" class="table table-bordered table-hover table-masterlist text-center w-100">
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
                                <th>REMARKS</th>
                                <th>NURSE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($entry->time)->format('h:i A') }}</td>
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
                                <td class="text-start">{{ $entry->remarks }}</td>
                                <td class="font-weight-bold text-primary">{{ $entry->nurse ?? 'N/A' }}</td>
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('animal-bite.masterlist.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="patient_id" class="form-label">Select Patient</label>
                            <select class="form-control" id="patient_id" name="patient_id" required>
                                <option value="" selected disabled>-- Choose Patient --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->age }}y/o, {{ $patient->city }})</option>
                                @endforeach
                            </select>
                            <small class="text-muted">If patient is not listed, add them in Patient Management first.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" class="form-control" id="time" name="time" required value="{{ date('H:i') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dose_received" class="form-label">Dose Received</label>
                            <input type="text" class="form-control" id="dose_received" name="dose_received" required placeholder="e.g. 1st, 2nd, LB">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="animal_status" class="form-label">Status of Animal</label>
                            <input type="text" class="form-control" id="animal_status" name="animal_status" placeholder="e.g. Alive (optional)">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount_paid" class="form-label">Amount Paid</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" class="form-control" id="amount_paid" name="amount_paid" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="CASH">CASH</option>
                                <option value="GCASH">GCASH</option>
                                <option value="BPI">BPI</option>
                                <option value="BDO">BDO</option>
                            </select>
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
@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#masterlistTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            "language": {
                "paginate": {
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>' 
                }
            }
        });
    });
</script>
@endpush
