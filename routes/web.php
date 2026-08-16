<?php

use App\Http\Controllers\LanguageController;
use App\Livewire\Dashboard\Admin\AdminData;
use App\Livewire\Dashboard\Banner\BannerData;
use App\Livewire\Dashboard\Category\CategoryData;
use App\Livewire\Dashboard\Coupon\CouponData;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\Faq\FaqData;
use App\Livewire\Dashboard\Instructor\InstructorData;
use App\Livewire\Dashboard\PaymentGateway\CreatePaymentGateway;
use App\Livewire\Dashboard\PaymentGateway\PaymentGatewayData;
use App\Livewire\Dashboard\PaymentGateway\UpdatePaymentGateway;
use App\Livewire\Dashboard\Profile\Profile;
use App\Livewire\Dashboard\Role\CreateRole;
use App\Livewire\Dashboard\Role\RoleData;
use App\Livewire\Dashboard\Role\UpdateRole;
use App\Livewire\Dashboard\SiteSetting\UpdateSiteSetting;
use App\Livewire\Dashboard\SubCategory\SubCategoryData;
use App\Livewire\Dashboard\University\UniversityData;
use App\Livewire\Dashboard\User\UserData;
use Illuminate\Support\Facades\Route;

// getFirstMediaUrl('image')
Route::middleware(['web-language'])->group(function () {
    Route::get('web-language/{lang}', LanguageController::class)->name('web-language');
    Route::redirect('/', 'login')->name('home');
    // authentication routes
    Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
        Route::livewire('profile', Profile::class)->name('profile'); // profile
        Route::livewire('dashboard', Dashboard::class)->name('dashboard'); // dashboard
        // roles
        Route::prefix('roles')->middleware('permission:show_role')->group(function () {
            Route::livewire('/', RoleData::class)->name('roles');
            Route::livewire('/create', CreateRole::class)->name('roles.create')->middleware('permission:create_role');
            Route::livewire('/{role}/edit', UpdateRole::class)->name('roles.edit')->middleware('permission:edit_role');
        });
        Route::livewire('users', UserData::class)->name('users')->middleware('permission:show_user'); // users
        Route::livewire('admins', AdminData::class)->name('admins')->middleware('permission:show_admin'); // users
        Route::livewire('instructors', InstructorData::class)->name('instructors')->middleware('permission:show_instructor'); // users
        Route::livewire('categories', CategoryData::class)->name('dashboard.categories')->middleware('permission:show_category'); // categories
        Route::livewire('subcategories', SubCategoryData::class)->name('subcategories')->middleware('permission:show_subcategory'); // subcategories
        Route::livewire('universities', UniversityData::class)->name('universities')->middleware('permission:show_university'); // universities
        Route::livewire('payment-gateways', PaymentGatewayData::class)->name('dashboard.payment-gateways')->middleware('permission:show_payment_gateway'); // payment gateways
        Route::livewire('payment-gateways/create', CreatePaymentGateway::class)->name('dashboard.payment-gateways.create')->middleware('permission:create_payment_gateway');
        Route::livewire('payment-gateways/{gateway}/edit', UpdatePaymentGateway::class)->name('dashboard.payment-gateways.edit')->middleware('permission:edit_payment_gateway');
        Route::livewire('banners', BannerData::class)->name('banners')->middleware('permission:show_banner'); // banners
        Route::livewire('faqs', FaqData::class)->name('faqs')->middleware('permission:show_faq'); // faqs
        Route::livewire('coupons', CouponData::class)->name('coupons')->middleware('permission:show_coupon'); // coupons
        Route::livewire('site-settings', UpdateSiteSetting::class)->name('site-settings')->middleware('permission:show_site_setting'); // site settings

    });

    // guest routes
    require __DIR__.'/auth.php';
});
