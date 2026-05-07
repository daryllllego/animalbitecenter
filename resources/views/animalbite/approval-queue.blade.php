@extends('layouts.app')

@push('styles')
<style>
    .approval-header {
        color: #2953e8;
        letter-spacing: 2px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .nav-tabs .nav-link {
        color: #64748b;
        font-weight: 600;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
    }
    .nav-tabs .nav-link.active {
        color: #2953e8;
        background: transparent;
        border-bottom: 3px solid #2953e8;
    }
    .request-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .request-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .diff-table {
        font-size: 13px;
        background: #f8fafc;
        border-radius: 8px;
    }
    .diff-old {
        text-decoration: line-through;
        color: #ef4444;
        background: #fee2e2;
        padding: 2px 4px;
        border-radius: 4px;
    }
    .diff-new {
        color: #10b981;
        background: #d1fae5;
        padding: 2px 4px;
        border-radius: 4px;
        font-weight: 600;
    }
    .badge-edit { background-color: #3b82f6; color: white; }
    .badge-delete { background-color: #ef4444; color: white; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="approval-header mb-0">Approval Queue</h2>
        </div>

        <ul class="nav nav-tabs mb-4" id="approvalTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab">
                    Pending Requests <span class="badge bg-primary ms-2">{{ $pending->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="history-tab" data-bs-toggle="tab" href="#history" role="tab">
                    History
                </a>
            </li>
        </ul>

        <div class="tab-content" id="approvalTabsContent">
            <!-- Pending Tab -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                @if($pending->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa fa-check-circle fa-4x text-success mb-3"></i>
                        <h4 class="text-muted">No pending requests!</h4>
                        <p>Everything is up to date.</p>
                    </div>
                @else
                    @foreach($pending as $request)
                        <div class="card request-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge {{ $request->action === 'edit' ? 'badge-edit' : 'badge-delete' }} mb-2">
                                            {{ strtoupper($request->action) }} REQUEST
                                        </span>
                                        <h5 class="mb-1">
                                            @if($request->model_type === 'App\Models\MasterlistEntry')
                                                Masterlist Entry: {{ $request->old_data['patient']['name'] ?? 'Unknown Patient' }}
                                            @endif
                                        </h5>
                                        <p class="text-muted small mb-0">
                                            <i class="fa fa-building me-1"></i> {{ $request->branch }} | 
                                            <i class="fa fa-user-md me-1"></i> {{ $request->nurse_name }} | 
                                            <i class="fa fa-clock me-1"></i> {{ $request->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('animal-bite.approval-queue.approve', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm px-3">
                                                <i class="fa fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('animal-bite.approval-queue.reject', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                                <i class="fa fa-times me-1"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="bg-light p-3 rounded mb-4">
                                    <h6 class="small font-weight-bold mb-2 text-primary">REASON FOR REQUEST:</h6>
                                    <p class="mb-0 italic text-dark">{{ $request->reason }}</p>
                                </div>

                                @php
                                    $old = $request->old_data;
                                    $new = $request->new_data ?? $old; // Use old as default for deletions
                                    $isDelete = $request->action === 'delete';
                                    
                                    $fields = [
                                        ['key' => 'patient_id', 'label' => 'Patient', 'value' => $old['patient']['name'] ?? 'N/A'],
                                        ['key' => 'time', 'label' => 'Time', 'value' => $old['time'] ?? 'N/A'],
                                        ['key' => 'dose_received', 'label' => 'Dose Received', 'value' => $old['dose_received'] ?? 'N/A'],
                                        ['key' => 'animal_status', 'label' => 'Status of Animal', 'value' => $old['animal_status'] ?? 'N/A'],
                                        ['key' => 'amount_paid', 'label' => 'Amount Paid', 'value' => '₱ ' . number_format($old['amount_paid'] ?? 0, 2)],
                                        ['key' => 'payment_method', 'label' => 'Payment Method', 'value' => $old['payment_method'] ?? 'N/A'],
                                        ['key' => 'reference_number', 'label' => 'Reference Number', 'value' => $old['reference_number'] ?? 'N/A'],
                                        ['key' => 'remarks', 'label' => 'Remarks', 'value' => $old['remarks'] ?? 'N/A'],
                                    ];
                                @endphp

                                <div class="row g-3">
                                    <div class="{{ $isDelete ? 'col-12' : 'col-md-6' }}">
                                        <h6 class="text-center text-muted small mb-3 uppercase tracking-wider font-weight-bold">
                                            {{ $isDelete ? 'RECORD TO BE DELETED' : 'ORIGINAL DATA' }}
                                        </h6>
                                        <div class="row g-2">
                                            @foreach($fields as $field)
                                                <div class="col-6">
                                                    <div class="p-2 border rounded bg-white" style="min-height: 60px;">
                                                        <label class="d-block small text-muted mb-0 uppercase" style="font-size: 10px;">{{ $field['label'] }}</label>
                                                        <span class="font-weight-bold small">{{ $field['value'] }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if(!$isDelete)
                                    <div class="col-md-6 border-start ps-4">
                                        <h6 class="text-center text-primary small mb-3 uppercase tracking-wider font-weight-bold">PROPOSED CHANGES</h6>
                                        <div class="row g-2">
                                            @foreach($fields as $field)
                                                @php
                                                    $key = $field['key'];
                                                    $newValue = $new[$key] ?? null;
                                                    $oldValue = $old[$key] ?? null;
                                                    
                                                    // Special handling for labels/formatting
                                                    if ($key === 'amount_paid') {
                                                        $displayNew = '₱ ' . number_format($newValue, 2);
                                                    } else if ($key === 'patient_id') {
                                                        // Since we don't easily have the new patient name if ID changed, 
                                                        // we show ID or just the name if it didn't change.
                                                        $displayNew = ($newValue == $oldValue) ? ($old['patient']['name'] ?? 'N/A') : 'Patient ID: ' . $newValue;
                                                    } else {
                                                        $displayNew = $newValue ?? 'N/A';
                                                    }

                                                    $hasChanged = ($newValue != $oldValue) && !in_array($key, ['time']); // Keep time exclusion as per user request
                                                @endphp
                                                <div class="col-6">
                                                    <div class="p-2 border rounded {{ $hasChanged ? 'border-warning bg-warning-subtle' : 'bg-white' }}" style="min-height: 60px;">
                                                        <label class="d-block small text-muted mb-0 uppercase" style="font-size: 10px;">{{ $field['label'] }}</label>
                                                        <span class="font-weight-bold small {{ $hasChanged ? 'text-danger' : '' }}">{{ $displayNew }}</span>
                                                        @if($hasChanged)
                                                            <div class="position-absolute top-0 end-0 p-1">
                                                                <i class="fa fa-exclamation-circle text-warning" style="font-size: 10px;"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- History Tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Branch</th>
                                        <th>Nurse</th>
                                        <th>Action</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $h)
                                        <tr>
                                            <td class="small">{{ $h->created_at->format('M d, Y h:i A') }}</td>
                                            <td>{{ $h->branch }}</td>
                                            <td>{{ $h->nurse_name }}</td>
                                            <td>
                                                <span class="badge {{ $h->action === 'edit' ? 'badge-edit' : 'badge-delete' }}">
                                                    {{ strtoupper($h->action) }}
                                                </span>
                                            </td>
                                            <td class="text-start small" style="max-width: 200px;">{{ $h->reason }}</td>
                                            <td>
                                                @if($h->status === 'approved')
                                                    <span class="badge bg-success">APPROVED</span>
                                                @else
                                                    <span class="badge bg-danger">REJECTED</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-sm rounded-circle view-history-details" 
                                                    data-bs-toggle="modal" data-bs-target="#historyDetailsModal"
                                                    data-details='{!! json_encode([
                                                        "nurse" => $h->nurse_name,
                                                        "branch" => $h->branch,
                                                        "action" => strtoupper($h->action),
                                                        "reason" => $h->reason,
                                                        "date" => $h->created_at->format("M d, Y h:i A"),
                                                        "old" => $h->old_data,
                                                        "new" => $h->new_data
                                                    ]) !!}'>
                                                    <i class="fa fa-eye"></i>
                                                </button>
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
    </div>
</div>

<!-- History Details Modal -->
<div class="modal fade" id="historyDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Request Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1 text-muted small">NURSE / BRANCH</p>
                        <p class="font-weight-bold mb-0"><span id="hist_nurse"></span> | <span id="hist_branch"></span></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1 text-muted small">DATE / ACTION</p>
                        <p class="mb-0"><span id="hist_date" class="font-weight-bold"></span> | <span id="hist_action" class="badge"></span></p>
                    </div>
                </div>
                <div class="bg-light p-3 rounded mb-4">
                    <h6 class="small font-weight-bold mb-2 text-primary">REASON FOR REQUEST:</h6>
                    <p id="hist_reason" class="mb-0 italic"></p>
                </div>

                <div id="hist_comparison_view">
                    <div class="row g-3">
                        <div id="hist_col_old" class="col-md-6">
                            <h6 class="text-center text-muted small mb-3 uppercase tracking-wider font-weight-bold">ORIGINAL DATA</h6>
                            <div id="hist_grid_old" class="row g-2"></div>
                        </div>
                        <div id="hist_col_new" class="col-md-6 border-start ps-4">
                            <h6 class="text-center text-primary small mb-3 uppercase tracking-wider font-weight-bold">PROPOSED CHANGES</h6>
                            <div id="hist_grid_new" class="row g-2"></div>
                        </div>
                    </div>
                </div>
                <div id="hist_delete_info" class="text-center py-5" style="display: none;">
                    <i class="fa fa-trash-alt fa-4x text-danger mb-3"></i>
                    <h4 class="text-danger">DELETION REQUEST</h4>
                    <p class="text-muted">This record was requested for permanent removal from the system.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.view-history-details').on('click', function() {
            const data = $(this).data('details');
            
            $('#hist_nurse').text(data.nurse);
            $('#hist_branch').text(data.branch);
            $('#hist_date').text(data.date);
            $('#hist_reason').text(data.reason);
            $('#hist_action').text(data.action).removeClass('bg-primary bg-danger').addClass(data.action === 'EDIT' ? 'bg-primary' : 'bg-danger');

            const gridOld = $('#hist_grid_old');
            const gridNew = $('#hist_grid_new');
            gridOld.empty();
            gridNew.empty();

            if (data.action === 'EDIT' && data.new) {
                $('#hist_comparison_view').show();
                $('#hist_col_new').show();
                $('#hist_col_old').removeClass('col-12').addClass('col-md-6');
                $('#hist_delete_info').hide();
                
                const old = data.old || {};
                const newD = data.new;
                
                const fields = [
                    { key: 'patient_id', label: 'Patient', value: old.patient ? old.patient.name : 'N/A' },
                    { key: 'time', label: 'Time', value: old.time || 'N/A' },
                    { key: 'dose_received', label: 'Dose Received', value: old.dose_received || 'N/A' },
                    { key: 'animal_status', label: 'Status of Animal', value: old.animal_status || 'N/A' },
                    { key: 'amount_paid', label: 'Amount Paid', value: old.amount_paid, isCurrency: true },
                    { key: 'payment_method', label: 'Payment Method', value: old.payment_method || 'N/A' },
                    { key: 'reference_number', label: 'Reference Number', value: old.reference_number || 'N/A' },
                    { key: 'remarks', label: 'Remarks', value: old.remarks || 'N/A' }
                ];

                fields.forEach(f => {
                    const oldVal = f.value;
                    let newVal = newD[f.key];
                    
                    let displayOld = oldVal;
                    let displayNew = newVal;

                    if (f.isCurrency) {
                        displayOld = '₱ ' + parseFloat(oldVal || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
                        displayNew = '₱ ' + parseFloat(newVal || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
                    }

                    if (f.key === 'patient_id') {
                        displayNew = (newVal == old.patient_id) ? (old.patient ? old.patient.name : 'N/A') : 'Patient ID: ' + newVal;
                    }

                    const hasChanged = (newVal != (f.isCurrency ? oldVal : (f.key === 'patient_id' ? old.patient_id : oldVal))) && !['time'].includes(f.key);

                    gridOld.append(`
                        <div class="col-6">
                            <div class="p-2 border rounded bg-white" style="min-height: 60px;">
                                <label class="d-block small text-muted mb-0 uppercase" style="font-size: 10px;">${f.label}</label>
                                <span class="font-weight-bold small">${displayOld}</span>
                            </div>
                        </div>
                    `);

                    gridNew.append(`
                        <div class="col-6">
                            <div class="p-2 border rounded ${hasChanged ? 'border-warning bg-warning-subtle' : 'bg-white'}" style="min-height: 60px; position: relative;">
                                <label class="d-block small text-muted mb-0 uppercase" style="font-size: 10px;">${f.label}</label>
                                <span class="font-weight-bold small ${hasChanged ? 'text-danger' : ''}">${displayNew || 'N/A'}</span>
                                ${hasChanged ? '<div class="position-absolute top-0 end-0 p-1"><i class="fa fa-exclamation-circle text-warning" style="font-size: 10px;"></i></div>' : ''}
                            </div>
                        </div>
                    `);
                });
            } else {
                // DELETE logic
                $('#hist_comparison_view').show();
                $('#hist_col_new').hide();
                $('#hist_col_old').removeClass('col-md-6').addClass('col-12');
                $('#hist_delete_info').show();
                
                const old = data.old || {};
                const fields = [
                    { label: 'Patient', value: old.patient ? old.patient.name : 'N/A' },
                    { label: 'Time', value: old.time || 'N/A' },
                    { label: 'Dose Received', value: old.dose_received || 'N/A' },
                    { label: 'Status of Animal', value: old.animal_status || 'N/A' },
                    { label: 'Amount Paid', value: '₱ ' + parseFloat(old.amount_paid || 0).toLocaleString(undefined, {minimumFractionDigits: 2}) },
                    { label: 'Payment Method', value: old.payment_method || 'N/A' },
                    { label: 'Reference Number', value: old.reference_number || 'N/A' },
                    { label: 'Remarks', value: old.remarks || 'N/A' }
                ];

                fields.forEach(f => {
                    gridOld.append(`
                        <div class="col-3">
                            <div class="p-2 border rounded bg-white" style="min-height: 60px;">
                                <label class="d-block small text-muted mb-0 uppercase" style="font-size: 10px;">${f.label}</label>
                                <span class="font-weight-bold small">${f.value}</span>
                            </div>
                        </div>
                    `);
                });
            }
        });
    });
</script>
@endpush
