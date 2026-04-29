@extends('layouts.app')

@push('styles')
<style>
    .kpi-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
        position: relative;
        height: 100%;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
    }
    .kpi-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 1px;
    }
    .kpi-value {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-top: 5px;
    }
    .kpi-input {
        border: 2px solid rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 18px;
        font-weight: 700;
        width: 100%;
        margin-top: 5px;
        background: rgba(255,255,255,0.5);
    }
    .kpi-input:focus {
        background: white;
        border-color: #2953e8;
        outline: none;
    }
    .bg-primary-light { background-color: #e3f2fd; }
    .bg-success-light { background-color: #e8f5e9; }
    .bg-danger-light { background-color: #ffebee; }
    .bg-warning-light { background-color: #fff8e1; }
    .bg-info-light { background-color: #e0f7fa; }
    .bg-secondary-light { background-color: #f3e5f5; }
    .bg-dark-light { background-color: #f1f5f9; }
    
    .table-header-blue {
        background-color: #2953e8 !important;
        color: white !important;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h2 class="text-primary font-w600">CEBU ANIMAL BITE CLINIC - MANDAUE BRANCH</h2>
        <p class="text-muted">Filtered Date: <span class="badge bg-primary px-3 py-2" style="font-size: 14px;">{{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</span></p>
    </div>
</div>

<div class="row">
    <!-- ROW 1 -->
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card kpi-card bg-primary-light border-start border-primary border-4">
            <div class="card-body">
                <div class="kpi-title">TOTAL PATIENTS FOR TODAY</div>
                <div class="kpi-value">{{ $stats['patients'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card kpi-card bg-success-light border-start border-success border-4">
            <div class="card-body">
                <div class="kpi-title">TOTAL SALES FOR TODAY</div>
                <div class="kpi-value">₱ {{ number_format($stats['sales'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card kpi-card bg-danger-light border-start border-danger border-4">
            <div class="card-body">
                <div class="kpi-title">TOTAL DEDUCTIONS</div>
                <div class="kpi-value">₱ {{ number_format($stats['deductions'], 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card kpi-card bg-warning-light border-start border-warning border-4">
            <div class="card-body">
                <div class="kpi-title">TOTAL CASH SALES</div>
                <div class="kpi-value text-warning">₱ {{ number_format($stats['cash_sales'], 2) }}</div>
                <small class="text-muted">(Total Sales - Online Sales)</small>
            </div>
        </div>
    </div>

    <!-- ROW 2 -->
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card kpi-card bg-info-light border-start border-info border-4">
            <div class="card-body">
                <div class="kpi-title">NET SALES</div>
                <div class="kpi-value text-info">₱ {{ number_format($stats['expected_cash'], 2) }}</div>
                <small class="text-muted">(Cash Sales - Deductions + Prev. Closing)</small>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card kpi-card bg-secondary-light border-start border-secondary border-4">
            <div class="card-body">
                <div class="kpi-title">TOTAL ONLINE SALES</div>
                <form action="{{ route('animal-bite.update-daily-stats') }}" method="POST">
                    @csrf
                    <div class="input-group mt-2">
                        <span class="input-group-text border-0 bg-transparent">₱</span>
                        <input type="number" step="0.01" name="online_sales" class="kpi-input" value="{{ $stats['online_sales'] }}" onchange="this.form.submit()">
                    </div>
                </form>
            </div>
        </div>
    </div>

    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0" style="letter-spacing: 5px; color: #2953e8; font-weight: 700;">DAILY DEDUCTIONS LOG</h4>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addDeductionModal" style="border-radius: 8px; padding: 8px 15px;">
                    <i class="fa fa-plus me-2"></i>ADD DEDUCTION
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="table-header-blue">
                            <tr>
                                <th>NAME OF ITEM</th>
                                <th>RELEASED BY</th>
                                <th>AMOUNT</th>
                                <th>RELEASED TO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $todayDeductions = \App\Models\Deduction::whereDate('date', $selectedDate)->get();
                            @endphp
                            @forelse($todayDeductions as $deduction)
                            <tr>
                                <td class="text-start ps-4">{{ $deduction->description }}</td>
                                <td>{{ $deduction->released_by }}</td>
                                <td class="font-weight-bold text-danger">₱ {{ number_format($deduction->amount, 2) }}</td>
                                <td>{{ $deduction->released_to }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-muted">No deductions recorded for this date.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Deduction Modal -->
<div class="modal fade" id="addDeductionModal" tabindex="-1" aria-labelledby="addDeductionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title text-white" id="addDeductionModalLabel">Add New Deduction</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('animal-bite.deductions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label font-weight-bold">Name of Item</label>
                        <input type="text" class="form-control" id="description" name="description" required placeholder="e.g. Office Supplies">
                    </div>
                    <div class="mb-3">
                        <label for="released_by" class="form-label font-weight-bold">Released By</label>
                        <input type="text" class="form-control" id="released_by" name="released_by" required placeholder="e.g. Nurse Jane">
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label font-weight-bold">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text bg-danger text-white border-danger">₱</span>
                            <input type="number" step="0.01" class="form-control border-danger" id="amount" name="amount" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="released_to" class="form-label font-weight-bold">Released To</label>
                        <input type="text" class="form-control" id="released_to" name="released_to" required placeholder="e.g. Messenger Service">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">Save Deduction</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
