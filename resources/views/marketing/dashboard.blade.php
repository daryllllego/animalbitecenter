<x-app-layout :title="$title" :role="$role" :sidebar="$sidebar">
    @push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/chartist/css/chartist.min.css') }}">
    <style>
        .period-btn {
            border-radius: 0;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .period-btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .period-btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        .period-btn.active {
            background-color: #3065D0 !important;
            border-color: #3065D0 !important;
            color: white !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .period-btn:hover:not(.active) {
            background-color: rgba(48, 101, 208, 0.1);
            border-color: #3065D0;
            color: #3065D0;
        }
        
        .card-bd .card-body {
            padding: 1.5rem;
        }

        .num-text {
            font-size: 1.75rem;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .fs-14 {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .text-muted {
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
    @endpush

    <!-- Time Period Selector -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-black font-w600">View Sales Statistics</h5>
                        <div class="btn-group" role="group" aria-label="Time period selector">
                            <button type="button" class="btn btn-outline-primary period-btn" data-period="daily">Daily</button>
                            <button type="button" class="btn btn-outline-primary period-btn" data-period="weekly">Weekly</button>
                            <button type="button" class="btn btn-outline-primary period-btn active" data-period="monthly">Monthly</button>
                            <button type="button" class="btn btn-outline-primary period-btn" data-period="yearly">Yearly</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="card card-bd">
                <div class="bg-primary card-border"></div>
                <div class="card-body box-style">
                    <div class="media align-items-center">
                        <div class="media-body me-3">
                            <h2 class="num-text text-black font-w700" id="totalSalesAmount">₱1,850,000</h2>
                            <span class="fs-14">Total Sales</span>
                            <small class="text-muted d-block" id="salesPeriodLabel">This Month</small>
                        </div>
                        <i class="las la-peso-sign" style="font-size: 2rem; color: #3065D0;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="card card-bd">
                <div class="bg-success card-border"></div>
                <div class="card-body box-style">
                    <div class="media align-items-center">
                        <div class="media-body me-3">
                            <h2 class="num-text text-black font-w700" id="totalOrdersCount">245</h2>
                            <span class="fs-14">Total Orders</span>
                            <small class="text-muted d-block" id="ordersPeriodLabel">This Month</small>
                        </div>
                        <i class="las la-shopping-bag" style="font-size: 2rem; color: #68CF29;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="card card-bd">
                <div class="bg-warning card-border"></div>
                <div class="card-body box-style">
                    <div class="media align-items-center">
                        <div class="media-body me-3">
                            <h2 class="num-text text-black font-w700" id="averageOrderValue">₱7,551</h2>
                            <span class="fs-14">Average Order Value</span>
                            <small class="text-muted d-block" id="avgOrderPeriodLabel">This Month</small>
                        </div>
                        <i class="las la-chart-line" style="font-size: 2rem; color: #FFAC30;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-lg-6 col-sm-6">
            <div class="card card-bd">
                <div class="bg-info card-border"></div>
                <div class="card-body box-style">
                    <div class="media align-items-center">
                        <div class="media-body me-3">
                            <h2 class="num-text text-black font-w700" id="topChannelSales">₱850,000</h2>
                            <span class="fs-14">Top Channel</span>
                            <small class="text-muted d-block" id="topChannelLabel">Area Sales</small>
                        </div>
                        <i class="las la-store" style="font-size: 2rem; color: #51A6F5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-xxl-8">
            <div class="card" style="min-height: 500px; display: flex; flex-direction: column;">
                <div class="card-header border-0 pb-0">
                    <h4 class="fs-20 mb-0 text-black">Sales Overview <span id="chartPeriodLabel" class="text-muted fs-14">(This Month)</span></h4>
                </div>
                <div class="card-body" style="flex: 1; min-height: 450px; display: flex; flex-direction: column; padding: 1.5rem;">
                    <div id="salesChart" class="timeline-chart" style="flex: 1; min-height: 400px;"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-xxl-4">
            <div class="card" style="min-height: 500px; display: flex; flex-direction: column;">
                <div class="card-header border-0 pb-0">
                    <h4 class="fs-20 mb-0 text-black">Sales Channels</h4>
                </div>
                <div class="card-body" style="flex: 1;">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-black font-w600">Area Sales</span>
                            <span class="text-black font-w600">₱850,000</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-black font-w600">Direct POS</span>
                            <span class="text-black font-w600">₱620,000</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 34%" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-black font-w600">ECOM POS</span>
                            <span class="text-black font-w600">₱380,000</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('vendor/chartist/js/chartist.min.js') }}"></script>
    <script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize sales chart
            if (window.ApexCharts) {
                const salesChartElement = document.getElementById('salesChart');
                if (salesChartElement) {
                    const salesChart = new ApexCharts(salesChartElement, {
                        series: [{
                            name: 'Sales',
                            data: [45000, 52000, 48000, 61000, 55000, 67000, 72000, 68000, 75000, 82000, 78000, 85000]
                        }],
                        chart: {
                            type: 'area',
                            height: '100%',
                            toolbar: { show: false }
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 2 },
                        xaxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.7,
                                opacityTo: 0.3,
                                stops: [0, 90, 100]
                            }
                        },
                        colors: ['#3065D0'],
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return '₱' + val.toLocaleString();
                                }
                            }
                        }
                    });
                    salesChart.render();
                }
            }

            // Period Selector Logic
            const dashboardData = {
                daily: { totalSales: 125000, totalOrders: 18, avgValue: 6944 },
                weekly: { totalSales: 485000, totalOrders: 68, avgValue: 7132 },
                monthly: { totalSales: 1850000, totalOrders: 245, avgValue: 7551 },
                yearly: { totalSales: 22150000, totalOrders: 2940, avgValue: 7534 }
            };

            function updateStats(period) {
                const data = dashboardData[period];
                document.getElementById('totalSalesAmount').textContent = '₱' + data.totalSales.toLocaleString();
                document.getElementById('totalOrdersCount').textContent = data.totalOrders;
                document.getElementById('averageOrderValue').textContent = '₱' + data.avgValue.toLocaleString();
                
                const labels = { daily: 'Today', weekly: 'This Week', monthly: 'This Month', yearly: 'This Year' };
                document.getElementById('salesPeriodLabel').textContent = labels[period];
                document.getElementById('ordersPeriodLabel').textContent = labels[period];
                document.getElementById('avgOrderPeriodLabel').textContent = labels[period];
                document.getElementById('chartPeriodLabel').textContent = '(' + labels[period] + ')';
            }

            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    updateStats(this.dataset.period);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
