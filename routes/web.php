<?php

use App\Http\Middleware\RoleMiddleware;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Admin\LoginController;
use App\Http\Controllers\Auth\Customer\RegisterController as CustomerRegisterController;
use App\Http\Controllers\Auth\Customer\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Front\InformationController as FrontInformationController;
use App\Http\Controllers\Front\CatalogController as FrontCatalogController;
use App\Http\Controllers\Front\LandingPageController;
use App\Http\Controllers\Back\MasterData\MemberController;
use App\Http\Controllers\Back\Ecommerce\BookingController;
use App\Http\Controllers\Back\Cms\CustomerOrderController;
use App\Http\Controllers\Back\Cms\ContactController;
use App\Http\Controllers\Back\Cms\FounderController;
use App\Http\Controllers\Back\Cms\CustomerController;
use App\Http\Controllers\Back\Cms\NewsroomController;
use App\Http\Controllers\Back\Cms\ProductCategoryController;
use App\Http\Controllers\Back\Cms\TestimonialController;
use App\Http\Controllers\Back\Cms\BannerController;
use App\Http\Controllers\Back\Cms\InformationPagesController;
use App\Http\Controllers\Back\Cms\CatalogController;
use App\Http\Controllers\Back\Inventory\DashboardController;
use App\Http\Controllers\Back\Inventory\DashboardActionsController;
use App\Http\Controllers\Back\Inventory\ProductController;
use App\Http\Controllers\Back\Inventory\HistoryController;
use App\Http\Controllers\Back\PurchaseOrder\PurchaseOrderController;
use App\Http\Controllers\Back\Ecommerce\ScheduleController;
use App\Http\Controllers\Back\MasterData\ArtCategoryController;
use App\Http\Controllers\Back\MasterData\CandidateTalentController;
use App\Http\Controllers\Back\MasterData\CategoryController;
use App\Http\Controllers\Back\MasterData\ProfessionalCategoryController;
use App\Http\Controllers\Back\MasterData\TalentCategoryController;
use App\Http\Controllers\Back\MasterData\TalentController;
use App\Http\Controllers\Back\MasterData\TalentPhotoController;
use App\Http\Controllers\Back\MasterData\TalentPriceController;
use App\Http\Controllers\Back\MasterData\TalentRatingController;
use App\Http\Controllers\Back\MasterData\TalentSpotlightController;


// Tambahkan route ini untuk 'memonopoli' named route 'login'
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// ==================================================
// FRONTEND ROUTES
// ==================================================
Route::group(['as' => 'front.'], function () {
    Route::get('/', [LandingPageController::class, 'index'])->name('home.index');
    Route::get('/catalog', [FrontCatalogController::class, 'index'])->name('catalog.index');
    Route::get('/information/{slug}', [FrontInformationController::class, 'show'])->name('information.show');
});

// ==================================================
// CUSTOMER ROUTES
// ==================================================
Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
    // Public routes (auth)
    Route::group(['as' => 'auth.'], function () {
        Route::get('/registers', [CustomerRegisterController::class, 'index'])->name('registers.index');
        Route::post('/registers', [CustomerRegisterController::class, 'store'])->name('registers.store');
        Route::get('/logins', [CustomerLoginController::class, 'index'])->name('login.index');
        Route::post('/logins', [CustomerLoginController::class, 'login'])->name('login.authentication');
        Route::post('/logout', [CustomerLoginController::class, 'logout'])->name('logout');
    });

    // Protected routes
    Route::group(['middleware' => 'customer'], function () {
        Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
        Route::patch('/profiles', [ProfileController::class, 'update'])->name('profiles.update');

        Route::get('/carts', [CartController::class, 'index'])->name('carts.index');
        Route::post('/carts/{product}/add', [CartController::class, 'add'])->name('carts.add');
        Route::delete('/carts/{cart}/remove', [CartController::class, 'remove'])->name('carts.remove');
        Route::patch('/carts/{cart}/update', [CartController::class, 'updateQuantity'])->name('carts.update_quantity');
        Route::patch('/carts/update-selected', [CartController::class, 'updateSelected'])->name('carts.update_selected');

        Route::post('/checkout/prepare', [CheckoutController::class, 'prepare'])->name('checkout.prepare');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/upload', [OrderController::class, 'uploadPayment'])->name('orders.upload_payment');
        Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    });
});

// ==================================================
// ADMIN ROUTES
// ==================================================
// ... (existing imports and front-end/customer routes) ...

// ==================================================
// ADMIN ROUTES
// ==================================================
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Auth Admin
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'authenticate')->name('authenticate');
        Route::post('logout', 'logout')->name('logout');
    });

    // Protected Routes (hanya untuk yang sudah login sebagai admin)
    Route::group(['middleware' => ['auth:web']], function () {

        // ========== OWNER FULL ACCESS ==========
        Route::group(['middleware' => 'role:owner'], function () {
            // CMS Routes (all of them)
            Route::group(['prefix' => 'cms', 'as' => 'cms.'], function () {
                Route::resource('product_categories', ProductCategoryController::class)->except('show');
                Route::post('product_categories/data', [ProductCategoryController::class, 'data'])->name('product_categories.data');
                Route::resource('testimonials', TestimonialController::class)->except('show');
                Route::post('testimonials-data', [TestimonialController::class, 'data'])->name('testimonials.data');
                Route::resource('banners', BannerController::class)->except('show');
                Route::post('banners/data', [BannerController::class, 'data'])->name('banners.data');
                Route::get('information-pages', [InformationPagesController::class, 'index'])->name('information-pages.index');
                Route::get('information-pages/{slug}/edit', [InformationPagesController::class, 'edit'])->name('information-pages.edit');
                Route::put('information-pages/{slug}', [InformationPagesController::class, 'update'])->name('information-pages.update');
                Route::get('catalog', [CatalogController::class, 'index'])->name('catalog.index');
                Route::get('catalog/data', [CatalogController::class, 'data'])->name('catalog.data');
                Route::post('catalog/{product}/toggle-display', [CatalogController::class, 'toggleDisplay'])->name('catalog.toggle-display');
            });

            // Inventory Routes (all of them)
            Route::group(['prefix' => 'inventory', 'as' => 'inventory.'], function () {
                Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
                Route::get('products/create', [DashboardActionsController::class, 'createProduct'])->name('products.create');
                Route::post('products/store', [DashboardActionsController::class, 'storeProduct'])->name('products.store');
                Route::get('opname', [DashboardActionsController::class, 'showStockOpname'])->name('opname.index');
                Route::post('opname/store', [DashboardActionsController::class, 'storeStockOpname'])->name('opname.store');
                Route::get('products', [ProductController::class, 'index'])->name('products.index');
                Route::get('products/data', [ProductController::class, 'data'])->name('products.data');
                Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
                Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
                Route::post('products/{product}/add-stock', [ProductController::class, 'addStock'])->name('products.add-stock');
                Route::post('products/{product}/correct-stock', [ProductController::class, 'correctStock'])->name('products.correct-stock');
                Route::get('history', [HistoryController::class, 'index'])->name('history.index');
            });

            // Purchase Orders Routes (all of them)
            Route::group(['prefix' => 'purchase-orders', 'as' => 'purchase-orders.'], function () {
                Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
                Route::get('create', [PurchaseOrderController::class, 'create'])->name('create');
                Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
                Route::get('{purchase_order}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
                Route::put('{purchase_order}', [PurchaseOrderController::class, 'update'])->name('update');
                Route::delete('{purchase_order}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
                Route::get('data', [PurchaseOrderController::class, 'data'])->name('data');
                Route::post('{purchase_order}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('update-status');
                Route::get('{purchase_order}/pdf', [PurchaseOrderController::class, 'exportPdf'])->name('pdf');
                Route::get('{purchase_order}', [PurchaseOrderController::class, 'show'])->name('show');
            });

            // Master Data Routes (all of them)
            Route::group(['prefix' => 'master-data', 'as' => 'master-data.'], function() {
                });
            });

        // ========== ADMIN STAFF (LIMITED) & OWNER ==========
        // These routes are accessible by both 'admin' and 'owner' roles
        Route::group(['middleware' => 'role:owner|admin'], function () {
            Route::group(['prefix' => 'cms', 'as' => 'cms.'], function () {
                Route::resource('customers', CustomerController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
                Route::post('customers/data', [CustomerController::class, 'data'])->name('customers.data');

                Route::resource('customer-orders', CustomerOrderController::class)->except(['show']);
                Route::get('customer-orders/{order}', [CustomerOrderController::class, 'show'])->name('customer-orders.show');
                Route::post('customer-orders/data', [CustomerOrderController::class, 'data'])->name('customer-orders.data');
                Route::get('customer-orders/{order}/update-status', [CustomerOrderController::class, 'updateStatus'])->name('customer-orders.updateStatus');
                Route::get('customer-orders/{order}/export-pdf', [CustomerOrderController::class, 'exportPdf'])->name('customer-orders.export-pdf');
            });
        });

    });
});
