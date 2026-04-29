<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Redirect
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

    // Universal Storage Fallback
    Route::get('/storage/{path}', [FileController::class, 'serve'])->where('path', '.*');

    // Marketing Dashboard
    Route::get('/marketing', [MarketingController::class, 'dashboard'])->name('marketing.dashboard');

    // Marketing UI Templates (Stubbed)
    Route::prefix('marketing')->name('marketing.')->group(function () {
        Route::get('/approval-queue', [MarketingController::class, 'approvalQueue'])->name('approval-queue');
        Route::get('/my-requests', [MarketingController::class, 'myRequests'])->name('my-requests');
        Route::get('/customers', [MarketingController::class, 'customers'])->name('customers');
        Route::get('/inventory', [MarketingController::class, 'inventory'])->name('products');
        
        // Sales Orders
        Route::get('/sales-orders/list', [MarketingController::class, 'salesOrdersList'])->name('sales-orders.list');
        Route::get('/sales-orders/create', [MarketingController::class, 'salesOrdersList'])->name('sales-orders.create'); // Added missing route
        Route::get('/sales-order/{id?}', [MarketingController::class, 'salesOrderDetail'])->name('sales-orders.detail');
        
        // Invoices
        Route::get('/direct-invoice-website', [MarketingController::class, 'directInvoiceWebsite'])->name('direct-invoice.website');
        Route::get('/direct-invoice-website/list', [MarketingController::class, 'directInvoiceList'])->name('direct-invoice.website.list');
        Route::get('/direct-invoice-ecom', [MarketingController::class, 'directInvoiceEcom'])->name('direct-invoice.ecom');
        Route::post('/direct-invoice-ecom/store', [MarketingController::class, 'directInvoiceEcom'])->name('direct-invoice.ecom.store');
        Route::post('/direct-invoice-ecom/approve/{id}', [MarketingController::class, 'directInvoiceEcom'])->name('direct-invoice.ecom.approve');
        
        Route::post('/direct-invoice-website/store', [MarketingController::class, 'directInvoiceWebsite'])->name('direct-invoice.website.store');
        Route::post('/direct-invoice-website/approve/{id}', [MarketingController::class, 'directInvoiceWebsite'])->name('direct-invoice.website.approve');

        // Categories (Stubbed)
        Route::get('/book-categories', [MarketingController::class, 'products'])->name('categories.index');
        Route::post('/book-categories', [MarketingController::class, 'products'])->name('categories.store');
        
        // Forms
        Route::get('/acknowledgement-receipt', [MarketingController::class, 'acknowledgementReceipt'])->name('acknowledgement-receipt');
        Route::get('/credit-memo', [MarketingController::class, 'creditMemo'])->name('credit-memo');
        Route::get('/proof-of-payment', [MarketingController::class, 'proofOfPayment'])->name('proof-of-payment');
        Route::get('/sales-invoice', [MarketingController::class, 'salesInvoice'])->name('sales-invoice');
        
        // Logistics
        Route::get('/pick-list-management', [MarketingController::class, 'pickListManagement'])->name('pick-list.management');
        Route::get('/pick-lists', [MarketingController::class, 'pickLists'])->name('pick-lists');
        Route::get('/delivery-receipt', [MarketingController::class, 'deliveryReceipt'])->name('delivery-receipt');
        Route::get('/delivery-receipt-list', [MarketingController::class, 'deliveryReceiptList'])->name('delivery-receipt.list');
        Route::get('/order-fulfillment', [MarketingController::class, 'orderFulfillment'])->name('order-fulfillment');
        Route::get('/packing-scheduling', [MarketingController::class, 'packingScheduling'])->name('packing-scheduling');
        Route::get('/delivery-scheduling', [MarketingController::class, 'deliveryScheduling'])->name('delivery-scheduling');
        Route::get('/delivery-tracking', [MarketingController::class, 'deliveryTracking'])->name('delivery-tracking');
        
        // Management
        Route::get('/sales-reports', [MarketingController::class, 'salesReports'])->name('sales-reports');
        Route::get('/territory-management', [MarketingController::class, 'territoryManagement'])->name('territory-management');
        Route::get('/suppliers', [MarketingController::class, 'suppliers'])->name('suppliers');

        // Direct Sales / POS
        Route::get('/pos-sale', [MarketingController::class, 'posSale'])->name('pos.sale');
        Route::get('/pos-settings', [MarketingController::class, 'posSettings'])->name('pos.settings');
        Route::post('/pos-sale/process', [MarketingController::class, 'processOrder'])->name('pos.process-order');
        Route::get('/pos-payment-settings', [MarketingController::class, 'paymentSettings'])->name('pos.payment-settings');
        Route::get('/pos-products', [MarketingController::class, 'posProducts'])->name('pos.products');
        Route::get('/ecom-pos', [MarketingController::class, 'ecomPos'])->name('ecom.pos');
        
        // NBS Import (Point to stub)
        Route::get('/nbs-import', [MarketingController::class, 'nbsImport'])->name('nbs-import.index');

        // Sales Orders (Extensive Stubbing)
        Route::get('/sales-orders/edit/{id}', [MarketingController::class, 'salesOrdersList'])->name('sales-orders.edit');
        Route::post('/sales-orders/destroy/{id}', [MarketingController::class, 'salesOrdersList'])->name('sales-orders.destroy');
        Route::post('/sales-orders/update/{id}', [MarketingController::class, 'salesOrdersList'])->name('sales-orders.update');
        Route::post('/sales-orders/store', [MarketingController::class, 'salesOrdersList'])->name('sales-orders.store');
        Route::post('/sales-orders/approve/{id}', [MarketingController::class, 'salesOrdersList'])->name('sales-orders.approve');
        Route::get('/sales-orders/shipping-label/{id}', [MarketingController::class, 'salesOrdersList'])->name('sales-orders.shipping-label');
        
        // Ads & Promo Sponsors
        Route::post('/ads-promo/sponsors/store', [MarketingController::class, 'sponsors'])->name('ads-promo.sponsors.store');
        Route::post('/ads-promo/sponsors/update/{id}', [MarketingController::class, 'sponsors'])->name('ads-promo.sponsors.update');
        Route::post('/ads-promo/sponsors/delete/{id}', [MarketingController::class, 'sponsors'])->name('ads-promo.sponsors.destroy');

        // Misc
        Route::get('/nbs-consignment-receipt/{id}', [MarketingController::class, 'nbsImport'])->name('nbs-consignment-receipt');
        Route::post('/nbs-import/process', [MarketingController::class, 'nbsImport'])->name('nbs-import.process');
        Route::post('/pos-process-ecom-order', [MarketingController::class, 'processOrder'])->name('pos.process-ecom-order');

        // Ads & Promo
        Route::get('/ads-promo/campaigns', [MarketingController::class, 'adsPromoCampaigns'])->name('ads-promo.campaigns');
        Route::get('/ads-promo/promotions', [MarketingController::class, 'adsPromoPromotions'])->name('ads-promo.promotions');
        Route::get('/ads-promo/crpr', [MarketingController::class, 'crpr'])->name('ads-promo.crpr');
        Route::get('/ads-promo/sponsors', [MarketingController::class, 'sponsors'])->name('ads-promo.sponsors');
    });

    // Employee Handlers (Stubbed)
    Route::post('/employee/cash-advance/store', [MarketingController::class, 'storeCashAdvance'])->name('employee.cash-advance.store');
    Route::put('/employee/cash-advance/{id}', [MarketingController::class, 'updateCashAdvance'])->name('employee.cash-advance.update');
});
