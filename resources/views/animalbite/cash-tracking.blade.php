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
    
    .last-child-no-border:last-child {
        border-right: none !important;
    }
    
    /* Ensure scrolling is enabled */
    body, html {
        overflow-y: auto !important;
        height: auto !important;
    }
    .content-body {
        overflow-y: auto !important;
    }
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

<div class="row g-2">
    <div class="col-xl-3 col-sm-6 mb-2">
        <div class="card tracking-card">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-primary mb-0 me-3" style="width: 40px; height: 40px;">
                        <i class="fa fa-door-open"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 10px;">Opening</div>
                        <div class="tracking-value text-primary" style="font-size: 18px;">₱ {{ number_format($openingAmount, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-2">
        <div class="card tracking-card">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-success mb-0 me-3" style="width: 40px; height: 40px;">
                        <i class="fa fa-plus-circle"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 10px;">Sales</div>
                        <div class="tracking-value text-success" style="font-size: 18px;">₱ {{ number_format($totalCashSales, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-2">
        <div class="card tracking-card">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-danger mb-0 me-3" style="width: 40px; height: 40px;">
                        <i class="fa fa-minus-circle"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 10px;">Deductions</div>
                        <div class="tracking-value text-danger" style="font-size: 18px;">₱ {{ number_format($totalDeductions, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-2">
        <div class="card tracking-card">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-warning mb-0 me-3" style="width: 40px; height: 40px;">
                        <i class="fa fa-equals"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 10px;">Expected</div>
                        <div class="tracking-value text-warning" style="font-size: 18px;">₱ {{ number_format($expectedCash, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card tracking-card mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 font-w700">DETAILED CASH FLOW</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table summary-table mb-0">
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

        <!-- Yellow Box: Daily Cash Tally -->
        <div class="card tracking-card" style="border-top: 4px solid #ffc107;">
            <div class="card-body p-4">
                <h5 class="font-w700 text-dark mb-4" style="font-size: 14px; letter-spacing: 1px;">
                    <i class="fa fa-list-ul text-warning me-2"></i>CASH DENOMINATION BREAKDOWN
                </h5>
                <div class="row g-4">
                    @php
                        $allDenoms = [
                            ['label' => '₱ 1,000s', 'value' => $tally->d1000 ?? 0, 'color' => 'primary'],
                            ['label' => '₱ 500s', 'value' => $tally->d500 ?? 0, 'color' => 'primary'],
                            ['label' => '₱ 200s', 'value' => $tally->d200 ?? 0, 'color' => 'primary'],
                            ['label' => '₱ 100s', 'value' => $tally->d100 ?? 0, 'color' => 'primary'],
                            ['label' => '₱ 50s', 'value' => $tally->d50 ?? 0, 'color' => 'primary'],
                            ['label' => '₱ 20 (Bill)', 'value' => $tally->d20 ?? 0, 'color' => 'primary'],
                            ['label' => '₱ 20 (Coin)', 'value' => $tally->c20 ?? 0, 'color' => 'success'],
                            ['label' => '₱ 10s', 'value' => $tally->c10 ?? 0, 'color' => 'success'],
                            ['label' => '₱ 5s', 'value' => $tally->c5 ?? 0, 'color' => 'success'],
                            ['label' => '₱ 1s', 'value' => $tally->c1 ?? 0, 'color' => 'success'],
                        ];
                        $chunks = array_chunk($allDenoms, 4); // 4 items per column
                    @endphp
                    
                    @foreach($chunks as $chunk)
                    <div class="col-md-4">
                        <div class="denomination-list">
                            @foreach($chunk as $d)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="text-muted fw-bold">{{ $d['label'] }}</span>
                                <div class="text-end">
                                    <span class="font-w800 text-{{ $d['color'] }} h5 mb-0">{{ number_format($d['value']) }}</span>
                                    <small class="text-muted ms-1">pcs</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card tracking-card bg-primary text-white overflow-hidden mb-3 shadow-lg">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-white mb-0 font-w700" style="letter-spacing: 1px;">DAILY SUMMARY</h5>
                    <i class="fa fa-chart-line text-white-50 fa-2x"></i>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="text-white-50 small fw-bold">OPENING CASH</span>
                        <h4 class="text-white mb-0 font-w700">₱ {{ number_format($openingAmount, 2) }}</h4>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="text-white-50 small fw-bold">TOTAL SALES</span>
                        <h4 class="text-white mb-0 font-w700 text-success">+ ₱ {{ number_format($totalCashSales, 2) }}</h4>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="text-white-50 small fw-bold">DEDUCTIONS</span>
                        <h4 class="text-white mb-0 font-w700 text-danger">- ₱ {{ number_format($totalDeductions, 2) }}</h4>
                    </div>
                </div>
                
                <hr style="background: rgba(255,255,255,0.3); height: 2px;" class="my-4">
                
                <div class="p-3 rounded bg-white-10" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                    <div class="text-center">
                        <span class="text-white-50 small fw-bold d-block mb-1">PROJECTED CLOSING CASH</span>
                        <h2 class="text-white mb-0 font-w800" style="font-size: 32px;">₱ {{ number_format($expectedCash, 2) }}</h2>
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
