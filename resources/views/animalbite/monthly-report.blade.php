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
    .bg-primary-light { background-color: #e3f2fd; }
    .bg-success-light { background-color: #e8f5e9; }
    .bg-danger-light { background-color: #ffebee; }
    .bg-warning-light { background-color: #fff8e1; }
    .bg-info-light { background-color: #e0f7fa; }
    .bg-secondary-light { background-color: #f3e5f5; }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-primary font-w600">MONTHLY SALES & PATIENT REPORT</h2>
                <p class="text-muted">Viewing: <span class="badge bg-primary px-3 py-2" style="font-size: 14px;">{{ $selectedBranch }}</span> for <span class="badge bg-info px-3 py-2" style="font-size: 14px;">{{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</span></p>
            </div>
            <div>
                <a href="{{ route('animal-bite.export-monthly', ['month' => $month, 'year' => $year]) }}" class="btn btn-success btn-rounded shadow">
                    <i class="fas fa-file-excel me-2"></i>Export to Excel
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                <div class="card-body">
                    <form action="{{ route('animal-bite.monthly-report') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label font-weight-bold">Select Month</label>
                            <select name="month" class="form-control default-select" onchange="this.form.submit()">
                                @for($m=1; $m<=12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Select Year</label>
                            <select name="year" class="form-control default-select" onchange="this.form.submit()">
                                @for($y=date('Y'); $y>=2024; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row 1 -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card kpi-card bg-primary-light border-start border-primary border-4">
                <div class="card-body">
                    <div class="kpi-title">TOTAL PATIENTS THIS MONTH</div>
                    <div class="kpi-value">{{ number_format($totalPatients) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card kpi-card bg-success-light border-start border-success border-4">
                <div class="card-body">
                    <div class="kpi-title">TOTAL SALES THIS MONTH</div>
                    <div class="kpi-value">₱ {{ number_format($totalSales, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card kpi-card bg-info-light border-start border-info border-4">
                <div class="card-body">
                    <div class="kpi-title">TOTAL ONLINE SALES</div>
                    <div class="kpi-value">₱ {{ number_format($totalOnlineSales, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card kpi-card bg-warning-light border-start border-warning border-4">
                <div class="card-body">
                    <div class="kpi-title">TOTAL CASH SALES</div>
                    <div class="kpi-value text-warning">₱ {{ number_format($totalCashSales, 2) }}</div>
                    <small class="text-muted">(Sales - Online)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row 2 -->
    <div class="row mb-4">
        <div class="col-xl-6 col-sm-6 mb-4">
            <div class="card kpi-card bg-danger-light border-start border-danger border-4">
                <div class="card-body">
                    <div class="kpi-title">TOTAL DEDUCTIONS THIS MONTH</div>
                    <div class="kpi-value">₱ {{ number_format($totalDeductions, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-sm-6 mb-4">
            <div class="card kpi-card bg-secondary-light border-start border-secondary border-4">
                <div class="card-body">
                    <div class="kpi-title">NET SALES (BEFORE DEDUCTIONS)</div>
                    <div class="kpi-value" style="color: #6a0dad;">₱ {{ number_format($netSales, 2) }}</div>
                    <small class="text-muted">(Total Sales - Total Deductions)</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-12">
            <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                <div class="card-header bg-white border-0 py-4">
                    <h4 class="card-title text-primary mb-0"><i class="fas fa-list me-2"></i>Branch Breakdown</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr class="text-muted">
                                    <th>Branch Name</th>
                                    <th class="text-center">Total Patients</th>
                                    <th class="text-end">Total Sales</th>
                                    <th class="text-end">Online Sales</th>
                                    <th class="text-end">Deductions</th>
                                    <th class="text-end">Net Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $branch => $data)
                                <tr>
                                    <td><strong>{{ $branch }}</strong></td>
                                    <td class="text-center">{{ number_format($data['patients']) }}</td>
                                    <td class="text-end text-success font-weight-bold">₱{{ number_format($data['sales'], 2) }}</td>
                                    <td class="text-end text-info font-weight-bold">₱{{ number_format($data['online_sales'], 2) }}</td>
                                    <td class="text-end text-danger font-weight-bold">₱{{ number_format($data['deductions'], 2) }}</td>
                                    <td class="text-end font-weight-bold" style="color: #2953e8;">₱{{ number_format($data['sales'] - $data['deductions'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr class="font-weight-bold">
                                    <td>GRAND TOTAL</td>
                                    <td class="text-center">{{ number_format($totalPatients) }}</td>
                                    <td class="text-end text-success">₱{{ number_format($totalSales, 2) }}</td>
                                    <td class="text-end text-info">₱{{ number_format($totalOnlineSales, 2) }}</td>
                                    <td class="text-end text-danger">₱{{ number_format($totalDeductions, 2) }}</td>
                                    <td class="text-end" style="color: #2953e8;">₱{{ number_format($totalSales - $totalDeductions, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
