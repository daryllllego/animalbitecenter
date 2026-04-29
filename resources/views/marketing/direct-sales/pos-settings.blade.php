<x-app-layout :title="$title" :role="$role" :sidebar="$sidebar">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="fs-20 mb-0 text-black">POS Configuration & Settings</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Tax Settings -->
                        <div class="col-xl-4 col-lg-6 mb-4">
                            <div class="card border">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Tax Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label font-w600">Default Tax Rate (%)</label>
                                        <input type="number" class="form-control" value="12" step="0.1">
                                    </div>
                                    <div class="form-check custom-checkbox check-xs mb-3">
                                        <input type="checkbox" class="form-check-input" id="taxInclusive" checked>
                                        <label class="form-check-label" for="taxInclusive">Prices are tax inclusive</label>
                                    </div>
                                    <button class="btn btn-primary btn-sm">Save Tax Settings</button>
                                </div>
                            </div>
                        </div>

                        <!-- Receipt Settings -->
                        <div class="col-xl-4 col-lg-6 mb-4">
                            <div class="card border">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Receipt Designer</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label font-w600">Store Header Name</label>
                                        <input type="text" class="form-control" value="Intracode Main Branch">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label font-w600">Footer Message</label>
                                        <textarea class="form-control" rows="2">Thank you for shopping with Intracode!</textarea>
                                    </div>
                                    <div class="form-check custom-checkbox check-xs mb-3">
                                        <input type="checkbox" class="form-check-input" id="printReceipt" checked>
                                        <label class="form-check-label" for="printReceipt">Auto-print receipt after checkout</label>
                                    </div>
                                    <button class="btn btn-primary btn-sm">Update Receipt</button>
                                </div>
                            </div>
                        </div>

                        <!-- Discount Settings -->
                        <div class="col-xl-4 col-lg-6 mb-4">
                            <div class="card border">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Item Discounts</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label font-w600">Max Manual Discount (%)</label>
                                        <input type="number" class="form-control" value="15">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label font-w600">Promo Code Eligibility</label>
                                        <select class="form-control">
                                            <option>All Items</option>
                                            <option>Products Only</option>
                                            <option>Non-Products Only</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-primary btn-sm">Configure Discounts</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Other Settings -->
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Advanced POS Options</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check custom-checkbox check-xs">
                                                <input type="checkbox" class="form-check-input" id="lowStockAlert" checked>
                                                <label class="form-check-label font-w600" for="lowStockAlert">Enable Low Stock Alerts on POS</label>
                                                <p class="text-muted small">Notify cashier when an item's stock is below the reorder point.</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check custom-checkbox check-xs">
                                                <input type="checkbox" class="form-check-input" id="allowWalking" disabled>
                                                <label class="form-check-label font-w600" for="allowWalking">Allow Walking Customers</label>
                                                <p class="text-muted small">Disable this to require customer registration for every transaction.</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check custom-checkbox check-xs">
                                                <input type="checkbox" class="form-check-input" id="dualDisplay">
                                                <label class="form-check-label font-w600" for="dualDisplay">Enable Customer-Facing Display</label>
                                                <p class="text-muted small">Output cart summary to a secondary monitor or tablet.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
