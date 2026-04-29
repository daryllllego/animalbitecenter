<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function dashboard()
    {
        return view('marketing.dashboard', [
            'title' => 'Intracode Dashboard',
            'role' => auth()->user()->position ?? 'Marketing',
            'sidebar' => 'marketing'
        ]);
    }

    // Stubbed methods for "Starting Over" - Clean UI Templates
    public function approvalQueue() { 
        return view('marketing.approval-queue', [
            'title' => 'Approval Queue',
            'role' => 'Marketing Manager',
            'sidebar' => 'marketing',
            'salesOrders' => collect(),
            'pendingCashAdvances' => collect(),
            'mySubmissions' => collect(),
            'myApprovedRequests' => collect()
        ]); 
    }

    public function myRequests() { 
        return view('marketing.my-requests', [
            'title' => 'My Requests',
            'role' => auth()->user()->position ?? 'Marketing',
            'sidebar' => 'marketing',
            'cashAdvances' => collect()
        ]); 
    }

    public function inventory() { 
        $products = collect([
            (object)['id' => 1, 'image' => asset('images/products/download (6).jfif'), 'sku' => 'PRD-001', 'name' => 'Premium Product A', 'author' => 'Author A', 'price' => 250.00, 'cost' => 180.00, 'Product_type' => 'Local', 'is_active' => true],
            (object)['id' => 2, 'image' => asset('images/products/download (7).jfif'), 'sku' => 'PRD-002', 'name' => 'Deluxe Product B', 'author' => 'Author B', 'price' => 450.00, 'cost' => 320.00, 'Product_type' => 'Foreign', 'is_active' => true],
            (object)['id' => 3, 'image' => asset('images/products/download (8).jfif'), 'sku' => 'PRD-003', 'name' => 'Standard Product C', 'author' => 'Author C', 'price' => 150.00, 'cost' => 110.00, 'Product_type' => 'Local', 'is_active' => false],
            (object)['id' => 4, 'image' => asset('images/products/download (9).jfif'), 'sku' => 'PRD-004', 'name' => 'Essential Product D', 'author' => 'Author D', 'price' => 320.00, 'cost' => 240.00, 'Product_type' => 'Consignment', 'is_active' => true],
        ]);
        return view('marketing.inventory', [
            'products' => $products,
            'categories' => collect(),
            'title' => 'Inventory (Master Registry)',
            'role' => 'Marketing Manager',
            'sidebar' => 'marketing'
        ]); 
    }

    public function salesOrdersList() { 
        return view('marketing.sales-orders.list', [
            'title' => 'Sales Orders List',
            'role' => 'Marketing Manager',
            'sidebar' => 'marketing',
            'orders' => collect()->paginate(10)
        ]); 
    }

    public function salesOrderDetail($id = null) { 
        return view('marketing.sales-orders.detail', [
            'title' => 'Sales Order',
            'role' => 'Marketing Manager',
            'sidebar' => 'marketing',
            'order' => null
        ]); 
    }

    public function directInvoiceWebsite() { 
        return view('marketing.direct-invoice-website', [
            'title' => 'Direct Invoice (Website)',
            'role' => auth()->user()->position ?? 'Marketing Staff',
            'sidebar' => 'marketing',
            'customers' => collect(),
            'products' => collect(),
            'invoices' => collect(),
        ]); 
    }

    public function directInvoiceList() { 
        return view('marketing.direct-invoice-list', [
            'title' => 'Website Invoices',
            'role' => auth()->user()->position ?? 'Marketing Staff',
            'sidebar' => 'marketing',
            'invoices' => collect(),
        ]); 
    }

    public function directInvoiceEcom() { 
        return view('marketing.direct-invoice-ecom', [
            'title' => 'Direct Invoice (E-com)',
            'role' => auth()->user()->position ?? 'Marketing Staff',
            'sidebar' => 'marketing',
            'customers' => collect(),
            'products' => collect(),
            'invoices' => collect(),
        ]); 
    }

    public function acknowledgementReceipt() { return view('marketing.acknowledgement-receipt', ['title' => 'AR', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function creditMemo() { return view('marketing.credit-memo', ['title' => 'Credit Memo', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function proofOfPayment() { return view('marketing.proof-of-payment', ['title' => 'POP', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function salesInvoice() { 
        return view('marketing.sales-invoice', [
            'title' => 'Sales Invoice', 
            'role' => 'Marketing', 
            'sidebar' => 'marketing',
            'invoices' => collect()
        ]); 
    }
    public function pickListManagement() { return view('marketing.pick-list-management', ['title' => 'Pick List', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function pickLists() { return view('marketing.pick-lists', ['title' => 'Pick Lists', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function deliveryReceipt() { return view('marketing.delivery-receipt', ['title' => 'DR', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function deliveryReceiptList() { return view('marketing.delivery-receipt-list', ['title' => 'DR List', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function orderFulfillment() { return view('marketing.order-fulfillment', ['title' => 'Fulfillment', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function packingScheduling() { return view('marketing.packing-scheduling', ['title' => 'Packing', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function deliveryScheduling() { return view('marketing.delivery-scheduling', ['title' => 'Delivery', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function deliveryTracking() { return view('marketing.delivery-tracking', ['title' => 'Tracking', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function salesReports() { return view('marketing.sales-reports', ['title' => 'Reports', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function territoryManagement() { return view('marketing.territory-management', ['title' => 'Territory', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function posSale() { 
        $products = collect([
            (object)['id' => 1, 'image' => asset('images/products/download (6).jfif'), 'name' => 'Premium Product A', 'price' => 250.00, 'stock' => 45, 'category' => 'Category 1'],
            (object)['id' => 2, 'image' => asset('images/products/download (7).jfif'), 'name' => 'Deluxe Product B', 'price' => 450.00, 'stock' => 12, 'category' => 'Category 2'],
            (object)['id' => 3, 'image' => asset('images/products/download (8).jfif'), 'name' => 'Standard Product C', 'price' => 150.00, 'stock' => 88, 'category' => 'Category 1'],
            (object)['id' => 4, 'image' => asset('images/products/download (9).jfif'), 'name' => 'Essential Product D', 'price' => 320.00, 'stock' => 5, 'category' => 'Category 3'],
        ]);
        return view('marketing.direct-sales.pos', [
            'title' => 'POS', 
            'role' => 'Marketing', 
            'sidebar' => 'marketing',
            'products' => $products,
            'customers' => collect(),
            'invoices' => collect()
        ]); 
    }
    public function posProducts() { return view('marketing.direct-sales.products', ['title' => 'POS Products', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    
    public function processOrder() { 
        return response()->json([
            'success' => true, 
            'message' => 'Stub: Order processed successfully',
            'order' => ['order_number' => 'ORD-' . strtoupper(uniqid())]
        ]); 
    }
    
    public function paymentSettings() { 
        return response()->json([
            'success' => true, 
            'settings' => [
                'posConfig' => ['taxRate' => 12],
                'gcash' => ['number' => '09123456789', 'qr' => asset('images/sample-qr.png')],
                'paymaya' => ['number' => '09123456789', 'qr' => asset('images/sample-qr.png')],
                'bank' => ['name' => 'BPI', 'accountName' => 'Intracode Corp', 'accountNumber' => '1234-5678-90']
            ]
        ]); 
    }
    public function posSettings() { 
        return view('marketing.direct-sales.pos-settings', [
            'title' => 'POS Settings', 
            'role' => 'Marketing', 
            'sidebar' => 'marketing'
        ]); 
    }
    
    public function ecomPos() { return view('marketing.ecom.pos', ['title' => 'Ecom POS', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function adsPromoCampaigns() { return view('marketing.ads-promo.campaigns', ['title' => 'Campaigns', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function adsPromoPromotions() { return view('marketing.ads-promo.promotions', ['title' => 'Promotions', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function crpr() { return view('marketing.ads-promo.crpr', ['title' => 'CRPR', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function sponsors() { return view('marketing.ads-promo.sponsors', ['title' => 'Sponsors', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function nbsImport() { return view('marketing.direct-sales.nbs-import', ['title' => 'NBS PO Import', 'role' => 'Marketing', 'sidebar' => 'marketing']); }
    public function suppliers() { return view('marketing.suppliers', ['title' => 'Suppliers', 'role' => 'Marketing', 'sidebar' => 'marketing', 'suppliers' => collect()]); }
    public function purchaseOrders() { return view('marketing.purchase-orders', ['title' => 'Purchase Orders', 'role' => 'Marketing', 'sidebar' => 'marketing', 'purchaseOrders' => collect()]); }
    public function storeCashAdvance() { return back()->with('success', 'Stub: Cash advance request submitted.'); }
    public function updateCashAdvance() { return back()->with('success', 'Stub: Cash advance request updated.'); }
}
