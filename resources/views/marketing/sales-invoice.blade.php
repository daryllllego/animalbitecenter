<x-app-layout :title="$title" :role="$role" :sidebar="$sidebar">
    @push('styles')
    <style>
        .invoice-form-modal .modal-body {
            background: #f4f7fa;
            padding: 2rem;
        }

        .invoice-card {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            max-width: 1000px;
            margin: 0 auto;
        }

        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .form-header .company-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-header .company-logo {
            width: 60px;
            height: 60px;
            background: #3065D0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        .form-header .company-details {
            flex: 1;
        }

        .form-header .company-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }

        .form-header .document-title {
            text-align: center;
            font-size: 1.75rem;
            font-weight: 700;
            color: #333;
            margin-top: 1rem;
            letter-spacing: 1px;
        }

        .form-header .invoice-number {
            text-align: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: #3065D0;
            margin-top: 0.5rem;
        }

        .customer-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .customer-details,
        .transaction-details {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .invoice-table thead {
            background: #3065D0;
            color: #fff;
        }

        .invoice-table th {
            padding: 0.75rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid #ddd;
        }

        .invoice-table td {
            padding: 0.5rem;
            border: 1px solid #ddd;
        }

        .invoice-table input,
        .invoice-table textarea {
            width: 100%;
            border: none;
            padding: 0.5rem;
            background: transparent;
        }

        .summary-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .btn-add-row {
            background: #3065D0;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            cursor: pointer;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
    @endpush

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Sales Invoices</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                        <i class="las la-plus me-2"></i>Add New Invoice
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                <tr>
                                    <td><strong>#{{ $invoice->invoice_number }}</strong></td>
                                    <td>{{ $invoice->customer_name }}</td>
                                    <td>{{ $invoice->date }}</td>
                                    <td>₱{{ number_format($invoice->total, 2) }}</td>
                                    <td><span class="badge badge-success">{{ $invoice->status }}</span></td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="#" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                            <a href="#" class="btn btn-primary shadow btn-xs sharp"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="las la-file-invoice-dollar" style="font-size: 3rem; opacity: 0.2;"></i>
                                        <p class="mt-2">No invoices found. Click "Add New Invoice" to create one.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Invoice Modal -->
    <div class="modal fade invoice-form-modal" id="addInvoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-primary py-2">
                    <h5 class="modal-title text-white">Create New Sales Invoice</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="invoice-card">
                        <!-- Form Header -->
                        <div class="form-header">
                            <div class="company-info">
                                <div class="company-logo">C</div>
                                <div class="company-details">
                                    <div class="company-name">CLARETIAN COMMUNICATIONS FOUNDATION INC.</div>
                                    <div class="company-address">8 Mayumi St., UP Village, Diliman, Quezon City</div>
                                    <div class="company-contact">Tel. No.: 921-3984</div>
                                </div>
                            </div>
                            <div class="document-title">SALES INVOICE</div>
                            <div class="text-center text-muted small fw-bold mb-1">NON-VAT REGISTERED</div>
                            <div class="invoice-number" id="invoiceNumber">No. SI-XXXXXX</div>
                        </div>

                        <!-- Customer and Transaction Details -->
                        <div class="customer-section">
                            <div class="customer-details">
                                <h6 class="fw-bold mb-3">Customer Information</h6>
                                <div class="mb-3">
                                    <label class="form-label font-w600 small">Sold to:</label>
                                    <input type="text" class="form-control form-control-sm" id="soldTo" placeholder="Customer name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label font-w600 small">Address:</label>
                                    <textarea class="form-control form-control-sm" id="customerAddress" rows="2" placeholder="Address"></textarea>
                                </div>
                            </div>
                            <div class="transaction-details">
                                <h6 class="fw-bold mb-3">Transaction Details</h6>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label font-w600 small">Date:</label>
                                        <input type="date" class="form-control form-control-sm" id="invoiceDate">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label font-w600 small">Terms:</label>
                                        <input type="text" class="form-control form-control-sm" id="terms" placeholder="COD, Net 30">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label font-w600 small">Due Date:</label>
                                        <input type="date" class="form-control form-control-sm" id="dueDate">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <button type="button" class="btn-add-row btn-sm mb-3" onclick="addRow()">
                            <i class="las la-plus"></i> Add Row
                        </button>

                        <div class="table-responsive">
                            <table class="invoice-table">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th style="width: 80px;">QTY</th>
                                        <th>DESCRIPTION</th>
                                        <th style="width: 150px; text-align: right;">UNIT PRICE</th>
                                        <th style="width: 150px; text-align: right;">AMOUNT</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="invoiceTableBody">
                                    <tr>
                                        <td><input type="number" class="qty-input form-control form-control-sm" value="1"></td>
                                        <td><input type="text" class="form-control form-control-sm" placeholder="Description"></td>
                                        <td><input type="number" class="unit-price-input form-control form-control-sm text-end" placeholder="0.00"></td>
                                        <td><input type="number" class="amount-input form-control form-control-sm text-end" readonly placeholder="0.00"></td>
                                        <td><button class="btn btn-primary btn-xs sharp" onclick="removeRow(this)"><i class="fa fa-times"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="summary-section mt-4">
                            <div>
                                <h6 class="fw-bold mb-2">Payment Mode</h6>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payMode" id="cashMode" value="cash" checked>
                                    <label class="form-check-label" for="cashMode">Cash</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payMode" id="chargeMode" value="charge">
                                    <label class="form-check-label" for="chargeMode">Charge</label>
                                </div>
                            </div>
                            <div class="financial-summary">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="font-w600">Total Sales:</span>
                                    <span id="totalSales" class="font-w700">₱0.00</span>
                                </div>
                                <div class="d-flex justify-content-between pt-2 border-top">
                                    <h5 class="mb-0">TOTAL DUE:</h5>
                                    <h5 id="totalAmountDue" class="mb-0 text-primary">₱0.00</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveInvoice()">
                        <i class="las la-save me-2"></i>Generate Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('invoiceDate');
            if(dateInput) dateInput.value = today;

            const invoiceNo = 'SI-' + Date.now().toString().slice(-6);
            const invoiceNoEl = document.getElementById('invoiceNumber');
            if(invoiceNoEl) invoiceNoEl.textContent = 'No. ' + invoiceNo;

            document.getElementById('invoiceTableBody').addEventListener('input', function(e) {
                if (e.target.classList.contains('qty-input') || e.target.classList.contains('unit-price-input')) {
                    calculateRowAmount(e.target.closest('tr'));
                }
            });
        });

        function calculateRowAmount(row) {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
            const amountInput = row.querySelector('.amount-input');
            amountInput.value = (qty * unitPrice).toFixed(2);
            calculateTotals();
        }

        function calculateTotals() {
            let totalSales = 0;
            document.querySelectorAll('.amount-input').forEach(input => {
                totalSales += parseFloat(input.value) || 0;
            });
            document.getElementById('totalSales').textContent = '₱' + totalSales.toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('totalAmountDue').textContent = '₱' + totalSales.toLocaleString(undefined, {minimumFractionDigits: 2});
        }

        function addRow() {
            const tbody = document.getElementById('invoiceTableBody');
            const newRow = document.createElement('tr');
            newRow.innerHTML = `
                <td><input type="number" class="qty-input form-control form-control-sm" value="1"></td>
                <td><input type="text" class="form-control form-control-sm" placeholder="Description"></td>
                <td><input type="number" class="unit-price-input form-control form-control-sm text-end" placeholder="0.00"></td>
                <td><input type="number" class="amount-input form-control form-control-sm text-end" readonly placeholder="0.00"></td>
                <td><button class="btn btn-primary btn-xs sharp" onclick="removeRow(this)"><i class="fa fa-times"></i></button></td>
            `;
            tbody.appendChild(newRow);
        }

        function removeRow(button) {
            button.closest('tr').remove();
            calculateTotals();
        }

        function saveInvoice() {
            alert('Sales invoice generated and added to list!');
            bootstrap.Modal.getInstance(document.getElementById('addInvoiceModal')).hide();
        }
    </script>
    @endpush
</x-app-layout>
