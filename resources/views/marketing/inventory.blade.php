<x-app-layout :title="$title" :role="$role" :sidebar="$sidebar">
    @push('styles')
    <style>
        /* Modern Tabbed Form Styles */
        .Product-modal-header-info {
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .Product-tab-container {
            display: flex;
            min-height: 520px;
        }

        .Product-nav-tabs {
            width: 200px;
            border-right: 1px solid #dee2e6;
            background: #f1f1f1;
            padding-top: 1rem;
        }

        .Product-nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            text-align: left;
            padding: 12px 15px;
            color: #444;
            font-weight: 500;
            font-size: 0.85rem;
            border-left: 4px solid transparent;
            transition: all 0.2s;
        }

        .Product-nav-tabs .nav-link:hover {
            background: #e9e9e9;
        }

        .Product-nav-tabs .nav-link.active {
            background: #fff;
            color: #3065D0;
            border-left-color: #3065D0;
            margin-right: -1px;
            font-weight: 700;
        }

        .Product-tab-content {
            flex: 1;
            padding: 1.5rem;
            background: #fff;
            overflow-y: auto;
            max-height: 600px;
        }

        .form-row-custom {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            gap: 1rem;
        }

        .form-row-custom>label {
            width: 140px;
            text-align: right;
            font-size: 0.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        .form-row-custom .form-control-sm,
        .form-row-custom .form-select-sm {
            flex: 1;
            border-radius: 2px;
            border: 1px solid #ced4da;
        }

        .section-divider {
            border-bottom: 2px solid #f1f1f1;
            margin: 1.5rem 0 1rem;
            font-weight: 800;
            font-size: 0.8rem;
            color: #2c3e50;
            text-transform: uppercase;
            padding-bottom: 3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-divider::after {
            content: "";
            height: 1px;
            background: #eee;
            flex: 1;
        }

        /* Manage Categories Button & Modal */
        .btn-manage-cat {
            background-color: #3065D0;
            border-color: #3065D0;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(48, 101, 208, 0.2);
            height: 38px;
            min-height: 38px;
            box-sizing: border-box;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            padding: 0 1rem;
            font-size: 0.85rem;
            gap: 0.5rem;
        }

        .btn-manage-cat:hover {
            background-color: #e60000;
            box-shadow: 0 6px 8px rgba(48, 101, 208, 0.3);
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-manage-cat i {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            font-size: 1.1rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: auto !important;
            height: 1.1rem !important;
            line-height: 1 !important;
        }

        #manageCategoriesModal .nav-tabs {
            border-bottom: 2px solid #eee;
        }

        #manageCategoriesModal .nav-link {
            font-size: 0.85rem;
            font-weight: 600;
            color: #666;
            border: none;
            padding: 10px 20px;
        }

        #manageCategoriesModal .nav-link.active {
            color: #3065D0;
            border-bottom: 2px solid #3065D0;
            background: transparent;
        }

        .accordion-danger-solid .accordion-header {
            background-color: #f8f9fa;
            border: 1px solid #eee;
        }

        /* Fix for red border cutoff on invalid fields */
        .form-row-custom .is-invalid {
            border-right: 1px solid #dc3545 !important;
            margin-right: 2px; /* Pull it back slightly to ensure border visibility */
        }
    </style>
    @endpush

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-header border-0 d-block d-sm-flex">
                    <h4 class="card-title">Inventory (Master)</h4>
                    <div class="d-flex align-items-center mt-3 mt-sm-0 gap-2">
                        <a href="javascript:void(0);"
                            class="btn btn-primary rounded d-flex align-items-center" data-bs-toggle="modal"
                            data-bs-target="#addProductModal"
                            style="gap: 0.5rem; padding: 0.5rem 1rem; height: 38px; min-height: 38px; line-height: 1.5; box-sizing: border-box; border: none; background: #3065D0; color: #ffffff; font-weight: 500;">
                            <i class="las la-plus" style="font-size: 1rem;"></i>
                            <span>Add New Product</span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th>Cover</th>
                                    <th>SKU</th>
                                    <th>Product Title</th>
                                    <th>Author</th>
                                    <th>Price</th>
                                    <th>Cost</th>
                                    <th>Classification</th>
                                    <th>POS Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>
                                        <img src="{{ $product->image ?? asset('images/no-Product-cover.svg') }}" 
                                             class="rounded-circle" width="35" height="35" style="object-fit: cover; border: 1px solid #eee;">
                                    </td>
                                    <td><strong>#{{ $product->sku }}</strong></td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->author ?? 'N/A' }}</td>
                                    <td>₱{{ number_format($product->price, 2) }}</td>
                                    <td>₱{{ number_format($product->cost, 2) }}</td>
                                    <td>
                                        <span class="badge badge-outline-primary">{{ $product->Product_type ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($product->is_active)
                                            <span class="badge badge-success">Active on POS</span>
                                        @else
                                            <span class="badge badge-light">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="javascript:void(0);" class="btn btn-secondary shadow btn-xs sharp me-1 view-Product-btn" 
                                               data-id="{{ $product->id }}"><i class="far fa-eye"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-primary shadow btn-xs sharp me-1 edit-Product-btn" 
                                               data-id="{{ $product->id }}"><i class="fas fa-pencil-alt"></i></a>
                                            <a href="javascript:void(0);" class="btn btn-primary shadow btn-xs sharp delete-Product-btn"
                                               data-id="{{ $product->id }}"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No Products in the list.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modals')



    <!-- Delete Product Confirmation Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete this Product from the Master Registry?</p>
                    <p class="text-danger small fw-bold">This action cannot be undone and will free up the SKU.</p>
                    <input type="hidden" id="delete_Product_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Delete Permanently</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Master Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form id="addProductForm" class="modal-content" novalidate>
                @csrf
                <input type="hidden" name="Product_id" id="modal_Product_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalTitle">Product Details</h5>
                    <button type="submit" class="btn btn-primary btn-sm mx-3" id="saveProductBtn">Save Product</button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="Product-modal-header-info">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row-custom">
                                <label>Product TITLE</label>
                                <input type="text" class="form-control form-control-sm" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row-custom">
                                <label>SKU / CATALOG #</label>
                                <input type="text" class="form-control form-control-sm" name="sku" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="Product-tab-container">
                        <div class="nav flex-column nav-pills Product-nav-tabs" role="tablist">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#Product-general" type="button" role="tab">General Info</button>
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#Product-metadata" type="button" role="tab">Product Specs</button>
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#Product-inventory" type="button" role="tab">Inventory & Costing</button>
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#Product-extended" type="button" role="tab">Attributes</button>
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#Product-cover" type="button" role="tab">Product Image</button>
                        </div>

                        <div class="tab-content Product-tab-content">
                            <div class="tab-pane fade" id="Product-cover" role="tabpanel">
                                <div class="section-divider mt-0">Product COVER PREVIEW</div>
                                <div class="text-center p-4 border rounded bg-light">
                                    <img id="Product_image_preview" src="" class="img-fluid rounded shadow-sm mb-3 d-none" style="max-height: 300px; max-width: 100%; object-fit: contain;">
                                    <div class="mt-2">
                                        <label for="cover_image_input" id="choose_image_btn" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-camera me-2"></i>Choose Image
                                        </label>
                                        <input type="file" id="cover_image_input" class="d-none" name="image" accept="image/*" onchange="previewProductImage(this)">
                                        <p class="text-muted small mt-2">Recommended size: 600x900px (Portrait). Max 2MB.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show active" id="Product-general" role="tabpanel">
                                <div class="section-divider mt-0">IDENTIFICATION</div>
                                <div class="form-row-custom">
                                    <label>ITEM CODE (Auto)</label>
                                    <input type="text" class="form-control form-control-sm" name="item_code" readonly placeholder="Auto-generated">
                                </div>
                                <div class="form-row-custom">
                                    <label>BARCODE / UPC</label>
                                    <input type="text" class="form-control form-control-sm" name="barcode">
                                </div>
                                <div class="form-row-custom">
                                    <label>SELLING PRICE</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="price" step="0.01">
                                    </div>
                                </div>
                                <div class="form-row-custom">
                                    <label>POS STATUS</label>
                                    <div class="form-check custom-checkbox check-xs mb-0">
                                        <input type="checkbox" class="form-check-input" name="is_active" id="ProductActive" value="1" checked>
                                        <label class="form-check-label small" for="ProductActive">Active on POS</label>
                                    </div>
                                </div>
                                
                                <div class="section-divider">BRANDING & SOURCE</div>
                                <div class="form-row-custom">
                                    <label>BRAND / MANUFACTURER</label>
                                    <input type="text" class="form-control form-control-sm" name="author" placeholder="e.g., Royal Canin">
                                </div>
                                <div class="form-row-custom">
                                    <label>DISTRIBUTOR / SUPPLIER</label>
                                    <input type="text" class="form-control form-control-sm" name="publisher">
                                </div>

                            </div>

                            <div class="tab-pane fade" id="Product-metadata" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-row-custom">
                                            <label>SIZE (LXW)</label>
                                            <input type="text" class="form-control form-control-sm" name="size">
                                        </div>
                                        <div class="form-row-custom">
                                            <label>WEIGHT</label>
                                            <input type="text" class="form-control form-control-sm" name="weight">
                                        </div>
                                        <div class="form-row-custom">
                                            <label>NET WEIGHT / VOLUME</label>
                                            <input type="text" class="form-control form-control-sm" name="pages" placeholder="e.g., 5kg, 500ml">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-row-custom">
                                            <label>PACKAGING TYPE</label>
                                            <input type="text" class="form-control form-control-sm" name="cover_type" placeholder="e.g., Bag, Bottle">
                                        </div>
                                        <div class="form-row-custom">
                                            <label>CLASSIFICATION</label>
                                            <select class="form-select form-select-sm" name="Product_type">
                                                <option value="">Select Classification</option>
                                                <option value="Local">Local Product</option>
                                                <option value="Foreign">Foreign Product</option>
                                                <option value="Consignment">Consignment</option>
                                            </select>
                                        </div>
                                        <div class="form-row-custom">
                                            <label>PRODUCTION DATE</label>
                                            <input type="date" class="form-control form-control-sm" name="copyright">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="Product-inventory" role="tabpanel">
                                <div class="section-divider mt-0">COSTING</div>
                                <div class="form-row-custom">
                                    <label>UNIT COST</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">₱</span>
                                        <input type="number" class="form-control" name="cost" step="0.01">
                                    </div>
                                </div>
                                <div class="form-row-custom">
                                    <label>COGS ACCOUNT</label>
                                    <select class="form-select form-select-sm" name="cogs_account">
                                        <option value="Cost of Sales">Cost of Sales</option>
                                        <option value="Inventory Asset">Inventory Asset</option>
                                    </select>
                                </div>
                                <div class="section-divider">THRESHOLDS</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-row-custom">
                                            <label>MIN STOCK</label>
                                            <input type="number" class="form-control form-control-sm" name="reorder_point">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-row-custom">
                                            <label>MAX STOCK</label>
                                            <input type="number" class="form-control form-control-sm" name="max_stock">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="section-divider">LOCATION (WAREHOUSE)</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-row-custom">
                                            <label>SHELF NUMBER</label>
                                            <input type="text" class="form-control form-control-sm" name="shelf_number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-row-custom">
                                            <label>RACK NUMBER</label>
                                            <input type="text" class="form-control form-control-sm" name="rack_number">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="Product-extended" role="tabpanel">
                                <div class="section-divider mt-0">TAXONOMY & ATTRIBUTES</div>
                                <div class="form-row-custom">
                                    <label>CATEGORY</label>
                                    <select class="form-select form-select-sm" name="category_id" id="Product_category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row-custom">
                                    <label>SUB-CATEGORY</label>
                                    <select class="form-select form-select-sm" name="sub_category_id" id="Product_sub_category_id">
                                        <option value="">Select Sub-category</option>
                                    </select>
                                </div>
                                <div class="form-row-custom">
                                    <label>ARTICLE</label>
                                    <input type="text" class="form-control form-control-sm" name="article">
                                </div>
                                <div class="form-row-custom">
                                    <label>TAGS</label>
                                    <input type="text" class="form-control form-control-sm" name="royalty" placeholder="e.g., Organic, High Protein">
                                </div>
                                <div class="form-row-custom">
                                    <label>SUPPORT EMAIL</label>
                                    <input type="email" class="form-control form-control-sm" name="email">
                                </div>
                                <div class="form-row-custom">
                                    <label>CONTACT #</label>
                                    <input type="text" class="form-control form-control-sm" name="contact_number">
                                </div>
                                <div class="form-row-custom">
                                    <label>PURCHASE DESC</label>
                                    <textarea class="form-control form-control-sm" name="purchase_description" rows="3"></textarea>
                                </div>
                                <div class="form-row-custom">
                                    <label>NBS BARCODE</label>
                                    <input type="text" class="form-control form-control-sm" name="nbs_barcode">
                                </div>
                            </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Validation Errors Modal -->
    <div class="modal fade" id="validationErrorsModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold">The following errors were found:</p>
                    <ul id="modalErrorList" class="text-danger mb-0"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="goToFirstError()">Fix Errors</button>
                </div>
            </div>
        </div>
    </div>
    @endpush

    @push('scripts')
    <script>
        // Defensive Modal Initialization
        let ProductModal, deleteModal, validationErrorsModal;
        try {
            if (window.bootstrap && bootstrap.Modal) {
                ProductModal = new bootstrap.Modal(document.getElementById('addProductModal'));
                deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
                validationErrorsModal = new bootstrap.Modal(document.getElementById('validationErrorsModal'));
            }
        } catch (e) {
            console.warn("Bootstrap Modal JS not available, falling back to manual/jQuery methods", e);
        }

        // Helper to show/hide modals safely
        function safeModal(modalObj, elementId, action = 'show') {
            try {
                if (modalObj && typeof modalObj[action] === 'function') {
                    modalObj[action]();
                    return true;
                }
                if (window.jQuery && typeof $(elementId).modal === 'function') {
                    $(elementId).modal(action);
                    return true;
                }
                // Last resort toggle for simple CSS based visibility if any
                const el = document.getElementById(elementId);
                if (el) {
                    if (action === 'show') el.classList.add('show'), el.style.display = 'block';
                    else el.classList.remove('show'), el.style.display = 'none';
                    return true;
                }
            } catch (e) {
                console.error(`Failed to ${action} modal ${elementId}`, e);
            }
            return false;
        }

        const ProductForm = document.getElementById('addProductForm');
        let isFixingErrors = false;

        function previewProductImage(input) {
            const preview = document.getElementById('Product_image_preview');
            if (input.files && input.files[0]) {
                preview.src = window.URL.createObjectURL(input.files[0]);
                preview.classList.remove('d-none');
            } else {
                preview.src = "{{ asset('images/no-Product-cover.svg') }}";
            }
        }

        function populateProductModal(data, isReadOnly = false) {
            document.getElementById('modal_Product_id').value = data.id;
            ProductForm.querySelector('[name="name"]').value = data.name;
            ProductForm.querySelector('[name="sku"]').value = data.sku;
            ProductForm.querySelector('[name="item_code"]').value = data.item_code || '';
            ProductForm.querySelector('[name="category_id"]').value = data.category_id || '';
            
            const subCatSelect = document.getElementById('Product_sub_category_id');
            subCatSelect.innerHTML = '<option value="">Select Sub-category</option>';
            
            if (data.category_id) {
                fetch(`/marketing/Product-categories/${data.category_id}/subcategories`)
                    .then(response => response.json())
                    .then(subcats => {
                        subcats.forEach(sub => {
                            const opt = document.createElement('option');
                            opt.value = sub.id;
                            opt.textContent = sub.name;
                            if (sub.id == data.sub_category_id) opt.selected = true;
                            subCatSelect.appendChild(opt);
                        });
                    });
            }
            
            ProductForm.querySelector('[name="barcode"]').value = data.barcode || '';
            ProductForm.querySelector('[name="nbs_barcode"]').value = data.nbs_barcode || '';
            ProductForm.querySelector('[name="article"]').value = data.article || '';
            ProductForm.querySelector('[name="author"]').value = data.author || '';
            ProductForm.querySelector('[name="publisher"]').value = data.publisher || '';
            ProductForm.querySelector('[name="size"]').value = data.size || '';
            ProductForm.querySelector('[name="pages"]').value = data.pages || '';
            ProductForm.querySelector('[name="cover_type"]').value = data.cover_type || '';
            ProductForm.querySelector('[name="Product_type"]').value = data.Product_type || '';
            ProductForm.querySelector('[name="copyright"]').value = data.copyright || '';
            ProductForm.querySelector('[name="weight"]').value = data.weight || '';
            ProductForm.querySelector('[name="cost"]').value = data.cost;
            ProductForm.querySelector('[name="price"]').value = data.price;
            ProductForm.querySelector('[name="cogs_account"]').value = data.cogs_account || '';
            ProductForm.querySelector('[name="reorder_point"]').value = data.reorder_point;
            ProductForm.querySelector('[name="max_stock"]').value = data.max_stock;
            ProductForm.querySelector('[name="shelf_number"]').value = data.shelf_number || '';
            ProductForm.querySelector('[name="rack_number"]').value = data.rack_number || '';
            ProductForm.querySelector('[name="royalty"]').value = data.royalty || '';
            ProductForm.querySelector('[name="email"]').value = data.email || '';
            ProductForm.querySelector('[name="contact_number"]').value = data.contact_number || '';
            ProductForm.querySelector('[name="purchase_description"]').value = data.purchase_description || '';
            ProductForm.querySelector('[name="is_active"]').checked = data.is_active ? true : false;

            const preview = document.getElementById('Product_image_preview');
            preview.classList.remove('d-none');
            if (data.image) {
                preview.src = "/storage/" + data.image;
            } else {
                preview.src = "{{ asset('images/no-Product-cover.svg') }}";
            }

            // Mode Logic
            const titleEl = document.getElementById('addProductModalTitle');
            const saveBtn = document.getElementById('saveProductBtn');
            const inputs = ProductForm.querySelectorAll('input, select, textarea');

            if (isReadOnly) {
                titleEl.innerText = "View Product Details";
                saveBtn.classList.add('d-none');
                document.getElementById('choose_image_btn').classList.add('d-none');
                inputs.forEach(input => {
                    if (input.type !== 'hidden') {
                        input.disabled = true;
                    }
                });
            } else {
                titleEl.innerText = data.id ? "Edit Product Master Entry" : "Add New Product to Master Registry";
                saveBtn.classList.remove('d-none');
                document.getElementById('choose_image_btn').classList.remove('d-none');
                inputs.forEach(input => {
                    if (input.name !== 'item_code') {
                        input.disabled = false;
                    }
                });
            }
            
            safeModal(ProductModal, 'addProductModal', 'show');
        }

        document.querySelectorAll('.edit-Product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
            fetch('#')
                    .then(response => response.json())
                    .then(data => populateProductModal(data, false));
            });
        });

        document.querySelectorAll('.view-Product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
            fetch('#') // Reusing edit endpoint to fetch data
                    .then(response => response.json())
                    .then(data => populateProductModal(data, true));
            });
        });

        // Clear modal on close
        const addProductModalEl = document.getElementById('addProductModal');
        if (addProductModalEl) {
            addProductModalEl.addEventListener('hidden.bs.modal', function () {
                if (isFixingErrors) {
                    console.log("Skipping form reset - user is correcting validation errors.");
                    isFixingErrors = false;
                    return;
                }
                ProductForm.reset();
                if (typeof clearProductFormErrors === 'function') clearProductFormErrors();
                document.getElementById('modal_Product_id').value = '';
                
                // Reset Read-Only items
                document.getElementById('addProductModalTitle').innerText = "Add New Product to Master Registry";
                document.getElementById('saveProductBtn').classList.remove('d-none');
                document.getElementById('choose_image_btn').classList.remove('d-none');
                ProductForm.querySelectorAll('input, select, textarea').forEach(input => {
                    if (input.name !== 'item_code') {
                        input.disabled = false;
                    }
                });

                const preview = document.getElementById('Product_image_preview');
                preview.src = "{{ asset('images/no-Product-cover.svg') }}";
                preview.classList.remove('d-none');
            });
        }

        const validationErrorsModalEl = document.getElementById('validationErrorsModal');
        if (validationErrorsModalEl) {
            validationErrorsModalEl.addEventListener('show.bs.modal', function () {
                // Dim only the inner content of the registration modal
                const content = addProductModalEl.querySelector('.modal-content');
                if (content) content.style.filter = 'brightness(0.5)';
            });
            validationErrorsModalEl.addEventListener('hidden.bs.modal', function () {
                const content = addProductModalEl.querySelector('.modal-content');
                if (content) content.style.filter = 'brightness(1)';
            });
        }
        let firstErrorElement = null;

        function clearProductFormErrors() {
            if (!ProductForm) return;
            ProductForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            ProductForm.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            const errorList = document.getElementById('modalErrorList');
            if (errorList) errorList.innerHTML = '';
            firstErrorElement = null;
        }

        function goToFirstError() {
            isFixingErrors = true; // Signal that the next 'hide' is not a cancellation
            safeModal(validationErrorsModal, 'validationErrorsModal', 'hide');
            if (firstErrorElement) {
                const tabPane = firstErrorElement.closest('.tab-pane');
                let tabTrigger = null;
                
                if (tabPane) {
                    tabTrigger = document.querySelector(`.Product-nav-tabs [data-bs-target="#${tabPane.id}"], .Product-nav-tabs [href="#${tabPane.id}"]`);
                } else {
                    tabTrigger = document.querySelector('.Product-nav-tabs .nav-link:first-child');
                }

                if (tabTrigger) {
                    try {
                        if (window.bootstrap && bootstrap.Tab) {
                            bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
                        } else if (window.jQuery && typeof $(tabTrigger).tab === 'function') {
                            $(tabTrigger).tab('show');
                        } else {
                            tabTrigger.click();
                        }
                    } catch (e) {
                        tabTrigger.click();
                    }
                }
                
                setTimeout(() => {
                    firstErrorElement.focus();
                    firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 250);
            }
        }

        ProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearProductFormErrors();

            const id = document.getElementById('modal_Product_id').value;
            const url = id ? `/marketing/Product-list/${id}/update-Product` : "#";
            
            const formData = new FormData(this);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json();
                
                if (response.status === 422) {
                    // Validation Errors
                    const errorList = document.getElementById('modalErrorList');
                    let errorMessages = [];
                    firstErrorElement = null;
                    
                    for (const [key, messages] of Object.entries(data.errors)) {
                        const input = ProductForm.querySelector(`[name="${key}"]`);
                        if (input) {
                            if (!firstErrorElement) firstErrorElement = input;
                            input.classList.add('is-invalid');
                        }
                        messages.forEach(msg => {
                            errorMessages.push(msg);
                            if (errorList) {
                                const li = document.createElement('li');
                                li.innerText = msg;
                                errorList.appendChild(li);
                            }
                        });
                    }

                    // Attempt to show the modal
                    const shown = safeModal(validationErrorsModal, 'validationErrorsModal', 'show');
                    
                    // FALLBACK: If modal didn't show or isn't available, use alert
                    if (!shown || !document.getElementById('validationErrorsModal').classList.contains('show')) {
                        // Check after a tiny delay if the modal stayed hidden
                        setTimeout(() => {
                            const modal = document.getElementById('validationErrorsModal');
                            if (!modal || window.getComputedStyle(modal).display === 'none') {
                                window.alert("VALIDATION ERRORS:\n\n" + errorMessages.join("\n"));
                            }
                        }, 200);
                    }
                } else if (!response.ok) {
                    const msg = data.message || 'An unexpected error occurred.';
                    if (window.showAlert) window.showAlert(msg, 'danger');
                    else window.alert(msg);
                } else {
                    const msg = data.message || 'Success';
                    if (window.showAlert) window.showAlert(msg, 'success');
                    else window.alert(msg);
                    setTimeout(() => location.reload(), 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.alert('An unexpected error occurred during submission.');
            });
        });

        // Delete button opens modal
        document.querySelectorAll('.delete-Product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('delete_Product_id').value = this.dataset.id;
                deleteModal.show();
            });
        });

        // Confirm Delete Button Action
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            const id = document.getElementById('delete_Product_id').value;
            
            fetch(`/marketing/Product-list/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                deleteModal.hide();
                if (data.error) {
                    window.showAlert(data.error, 'danger');
                } else {
                    window.showAlert(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        });

        // Category Management JS
        const addCategoryFormOnly = document.getElementById('addCategoryFormOnly');
        const addSubCategoryForm = document.getElementById('addSubCategoryForm');
        const accordionContainer = document.getElementById('categoriesList');
        const parentCatSelect = document.querySelector('#addSubCategoryForm select[name="parent_id"]');
        const ProductCatSelect = document.getElementById('Product_category_id');

        function refreshCategoryUI() {
            fetch("{{ route('marketing.categories.index') }}")
                .then(response => response.json())
                .then(data => {
                    // Update dropdowns
                    let parentOpts = '<option value="" disabled selected>Select Parent Category...</option>';
                    let ProductOpts = '<option value="">Select Category</option>';
                    
                    data.forEach(cat => {
                        parentOpts += `<option value="${cat.id}">${cat.name}</option>`;
                        ProductOpts += `<option value="${cat.id}">${cat.name}</option>`;
                    });
                    
                    if(parentCatSelect) parentCatSelect.innerHTML = parentOpts;
                    if(ProductCatSelect) ProductCatSelect.innerHTML = ProductOpts;

                    // Update Accordion
                    let html = '<div class="accordion accordion-sm accordion-danger-solid" id="accordionCategories">';
                    data.forEach(cat => {
                        let subHtml = '';
                        if(cat.children && cat.children.length > 0) {
                            cat.children.forEach(sub => {
                                subHtml += `
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-1 border-0 ps-3">
                                        <span class="small text-muted">— ${sub.name}</span>
                                        <a href="javascript:void(0);" class="text-danger delete-category-btn" data-id="${sub.id}" title="Delete Sub-category">
                                            <i class="fas fa-times-circle"></i>
                                        </a>
                                    </li>`;
                            });
                        } else {
                            subHtml = '<li class="list-group-item py-1 border-0 ps-3 text-muted small">No sub-categories</li>';
                        }
                        
                        html += `
                            <div class="accordion-item">
                                <div class="accordion-header d-flex align-items-center justify-content-between pe-3">
                                    <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCat${cat.id}">
                                        ${cat.name}
                                    </button>
                                    <a href="javascript:void(0);" class="text-danger delete-category-btn" data-id="${cat.id}" title="Delete Category">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                <div id="collapseCat${cat.id}" class="accordion-collapse collapse" data-bs-parent="#accordionCategories">
                                    <div class="accordion-body py-2">
                                        <ul class="list-group list-group-flush mb-0">
                                            ${subHtml}
                                        </ul>
                                    </div>
                                </div>
                            </div>`;
                    });
                    html += '</div>';
                    accordionContainer.innerHTML = html;
                    bindDeleteEvents();
                });
        }

        function handleCategorySubmit(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            fetch("{{ route('marketing.categories.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                window.showAlert(data.message, 'success');
                form.reset();
                refreshCategoryUI();
            })
            .catch(err => {
                console.error('Save Error:', err);
                let msg = 'Failed to save.';
                if(err.errors) msg = Object.values(err.errors).flat().join(' ');
                window.showAlert(msg, 'danger');
            });
        }

        const deleteCategoryModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));

        function bindDeleteEvents() {
            document.querySelectorAll('.delete-category-btn').forEach(btn => {
                btn.onclick = function() {
                    document.getElementById('delete_cat_id').value = this.dataset.id;
                    deleteCategoryModal.show();
                };
            });
        }

        document.getElementById('confirmDeleteCategoryBtn').addEventListener('click', function() {
            const id = document.getElementById('delete_cat_id').value;
            fetch(`/marketing/Product-categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                deleteCategoryModal.hide();
                window.showAlert(data.message, 'success');
                refreshCategoryUI();
            });
        });

        if(addCategoryFormOnly) addCategoryFormOnly.addEventListener('submit', handleCategorySubmit);
        if(addSubCategoryForm) addSubCategoryForm.addEventListener('submit', handleCategorySubmit);
        bindDeleteEvents();

        // Dependent Dropdown Logic
        document.getElementById('Product_category_id').addEventListener('change', function() {
            const id = this.value;
            const subCatSelect = document.getElementById('Product_sub_category_id');
            subCatSelect.innerHTML = '<option value="">Select Sub-category</option>';
            
            if (!id) return;

            fetch(`/marketing/Product-categories/${id}/subcategories`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(sub => {
                        const opt = document.createElement('option');
                        opt.value = sub.id;
                        opt.textContent = sub.name;
                        subCatSelect.appendChild(opt);
                    });
                });
        });
    </script>
    @endpush
</x-app-layout>
