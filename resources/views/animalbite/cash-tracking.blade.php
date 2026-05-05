@extends('layouts.app')

@push('styles')
<style>
    .tracking-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        background: white;
    }
    .tracking-card:hover {
        transform: translateY(-5px);
    }
    .tracking-title {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
    }
    .tracking-value {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
    }
    .tracking-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .bg-soft-primary { background: #e0e7ff; color: #4338ca; }
    .bg-soft-success { background: #dcfce7; color: #15803d; }
    .bg-soft-danger { background: #fee2e2; color: #b91c1c; }
    .bg-soft-warning { background: #fef3c7; color: #b45309; }
    .bg-soft-info { background: #e0f2fe; color: #0369a1; }

    .summary-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 12px;
        padding: 15px;
    }
    .summary-table td {
        padding: 15px;
        vertical-align: middle;
        font-weight: 500;
    }
    .variance-positive { color: #15803d; background: #dcfce7; }
    .variance-negative { color: #b91c1c; background: #fee2e2; }
    .variance-neutral { color: #475569; background: #f1f5f9; }
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="font-w600 text-primary mb-0">CASH ON HAND TRACKING</h2>
            <p class="text-muted">Daily Automated Cash Summary for {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</p>
        </div>
        <div class="badge bg-primary px-4 py-2" style="font-size: 16px; border-radius: 10px;">
            <i class="fa fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card tracking-card h-100">
            <div class="card-body">
                <div class="tracking-icon bg-soft-primary">
                    <i class="fa fa-door-open fa-lg"></i>
                </div>
                <div class="tracking-title">Opening Cash</div>
                <div class="tracking-value text-primary">₱ {{ number_format($openingAmount, 2) }}</div>
                <small class="text-muted">Actual count from denominations</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card tracking-card h-100">
            <div class="card-body">
                <div class="tracking-icon bg-soft-success">
                    <i class="fa fa-plus-circle fa-lg"></i>
                </div>
                <div class="tracking-title">Cash Sales</div>
                <div class="tracking-value text-success">₱ {{ number_format($totalCashSales, 2) }}</div>
                <small class="text-muted">Total from Masterlist (CASH)</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card tracking-card h-100">
            <div class="card-body">
                <div class="tracking-icon bg-soft-danger">
                    <i class="fa fa-minus-circle fa-lg"></i>
                </div>
                <div class="tracking-title">Total Deductions</div>
                <div class="tracking-value text-danger">₱ {{ number_format($totalDeductions, 2) }}</div>
                <small class="text-muted">Total expenses for today</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
        <div class="card tracking-card h-100">
            <div class="card-body">
                <div class="tracking-icon bg-soft-warning">
                    <i class="fa fa-equals fa-lg"></i>
                </div>
                <div class="tracking-title">Expected Cash</div>
                <div class="tracking-value text-warning">₱ {{ number_format($expectedCash, 2) }}</div>
                <small class="text-muted">(Opening + Sales - Deductions)</small>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card tracking-card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title font-w700 text-dark">DETAILED CASH FLOW</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table summary-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Running Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="badge bg-soft-primary me-2"><i class="fa fa-arrow-right"></i></span>
                                    Starting Balance (Opening)
                                </td>
                                <td class="text-end">₱ {{ number_format($openingAmount, 2) }}</td>
                                <td class="text-end font-w700">₱ {{ number_format($openingAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge bg-soft-success me-2"><i class="fa fa-plus"></i></span>
                                    Total Cash Sales Today
                                </td>
                                <td class="text-end text-success">+ ₱ {{ number_format($totalCashSales, 2) }}</td>
                                <td class="text-end font-w700">₱ {{ number_format($openingAmount + $totalCashSales, 2) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge bg-soft-danger me-2"><i class="fa fa-minus"></i></span>
                                    Total Deductions Today
                                </td>
                                <td class="text-end text-danger">- ₱ {{ number_format($totalDeductions, 2) }}</td>
                                <td class="text-end font-w700 text-primary">₱ {{ number_format($expectedCash, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card tracking-card bg-primary text-white overflow-hidden">
            <div class="card-body p-4 position-relative">
                <div style="position: absolute; right: -20px; bottom: -20px; opacity: 0.1;">
                    <i class="fa fa-peso-sign" style="font-size: 150px;"></i>
                </div>
                <h4 class="text-white mb-4 font-w600">DAILY SUMMARY</h4>
                <div class="mb-4">
                    <p class="mb-1 text-white-50">Opening Cash (from yesterday)</p>
                    <h3 class="text-white font-w700">₱ {{ number_format($openingAmount, 2) }}</h3>
                </div>
                <div class="mb-4">
                    <p class="mb-1 text-white-50">Total Cash Sales Today</p>
                    <h3 class="text-white font-w700">+ ₱ {{ number_format($totalCashSales, 2) }}</h3>
                </div>
                <div class="mb-4">
                    <p class="mb-1 text-white-50">Total Deductions Today</p>
                    <h3 class="text-white font-w700">- ₱ {{ number_format($totalDeductions, 2) }}</h3>
                </div>
                <hr style="background: rgba(255,255,255,0.2);">
                <div>
                    <p class="mb-1 text-white-50">Projected Closing Cash</p>
                    <div class="p-3 rounded bg-white text-primary" style="display: inline-block; min-width: 150px;">
                        <h2 class="mb-0 font-w800">₱ {{ number_format($expectedCash, 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card tracking-card mt-4">
            <div class="card-body text-center p-4">
                <div class="tracking-icon bg-soft-info mx-auto">
                    <i class="fa fa-sync"></i>
                </div>
                <h5 class="font-w600">Automated Carry-over</h5>
                <p class="text-muted small">This system automatically calculates your cash flow based on Masterlist entries and Deductions. Your closing balance today will be your opening balance tomorrow.</p>
            </div>
        </div>
    </div>
</div>
@endsection
