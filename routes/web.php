<?php

use App\Models\ArticleCategory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AnnualReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\DonationCategoryController;
use App\Http\Controllers\MetaDataController;
use App\Http\Controllers\OfficeLocationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\TeamController;

Route::group(['middleware' => ['guest']], function () {

    Route::get('/', [AuthController::class, 'index']);
    Route::get('/login', [AuthController::class, 'index']);
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login');
});


Route::group(['middleware' => ['auth', 'admin']], function () {

    Route::get('/dashboard', [MainController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);

    Route::prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'index'])->name('banners.index');
        Route::get('/data', [BannerController::class, 'getBanners'])->name('banners.data');
        Route::get('/create', [BannerController::class, 'create'])->name('banners.create');
        Route::get('/{banner}', [BannerController::class, 'show'])->name('banners.show');
        Route::get('/{banner}/edit', [BannerController::class, 'edit'])->name('banners.edit');

        Route::post('/', [BannerController::class, 'store'])->name('banners.store');
        Route::put('/{banner}', [BannerController::class, 'update'])->name('banners.update');
        Route::delete('/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
    });


    Route::prefix('faqs')->group(function () {
        Route::get('/', [FaqController::class, 'index'])->name('faqs.index');
        Route::get('/data', [FaqController::class, 'getFaqs'])->name('faqs.data');
        Route::get('/create', [FaqController::class, 'create'])->name('faqs.create');
        Route::get('/{faq}', [FaqController::class, 'show'])->name('faqs.show');
        Route::get('/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');

        Route::post('/', [FaqController::class, 'store'])->name('faqs.store');
        Route::put('/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::delete('/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');
    });


    Route::prefix('broadcasts')->group(function () {
        Route::get('/', [BroadcastController::class, 'index'])->name('broadcasts.index');
        Route::get('/data', [BroadcastController::class, 'getBroadcasts'])->name('broadcasts.data');
        Route::get('/create', [BroadcastController::class, 'create'])->name('broadcasts.create');
        Route::get('/{broadcast}', [BroadcastController::class, 'show'])->name('broadcasts.show');
        Route::get('/{broadcast}/edit', [BroadcastController::class, 'edit'])->name('broadcasts.edit');

        Route::post('/', [BroadcastController::class, 'store'])->name('broadcasts.store');
        Route::put('/{broadcast}', [BroadcastController::class, 'update'])->name('broadcasts.update');
        Route::delete('/{broadcast}', [BroadcastController::class, 'destroy'])->name('broadcasts.destroy');
    });

    Route::prefix('article-categories')->group(function () {
        Route::get('/', [ArticleCategoryController::class, 'index'])->name('article-categories.index');
        Route::get('/data', [ArticleCategoryController::class, 'getArticleCategories'])->name('article-categories.data');
        Route::get('/create', [ArticleCategoryController::class, 'create'])->name('article-categories.create');
        Route::get('/{category}', [ArticleCategoryController::class, 'show'])->name('article-categories.show');
        Route::get('/{category}/edit', [ArticleCategoryController::class, 'edit'])->name('article-categories.edit');

        Route::post('/', [ArticleCategoryController::class, 'store'])->name('article-categories.store');
        Route::put('/{category}', [ArticleCategoryController::class, 'update'])->name('article-categories.update');
        Route::delete('/{category}', [ArticleCategoryController::class, 'destroy'])->name('article-categories.destroy');
    });


    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'indexAdmin'])->name('articles.index');
        Route::get('/data', [ArticleController::class, 'getArticles'])->name('articles.data');
        Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::get('/{article}', [ArticleController::class, 'show'])->name('articles.show');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');

        Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    });


    Route::prefix('donation-categories')->group(function () {
        Route::get('/', [DonationCategoryController::class, 'index'])->name('donation-categories.index');
        Route::get('/data', [DonationCategoryController::class, 'getDonationCategories'])->name('donation-categories.data');
        Route::get('/create', [DonationCategoryController::class, 'create'])->name('donation-categories.create');
        Route::get('/{category}', [DonationCategoryController::class, 'show'])->name('donation-categories.show');
        Route::get('/{category}/edit', [DonationCategoryController::class, 'edit'])->name('donation-categories.edit');

        Route::post('/', [DonationCategoryController::class, 'store'])->name('donation-categories.store');
        Route::put('/{category}', [DonationCategoryController::class, 'update'])->name('donation-categories.update');
        Route::delete('/{category}', [DonationCategoryController::class, 'destroy'])->name('donation-categories.destroy');
    });


    Route::prefix('partners')->group(function () {
        Route::get('/', [PartnerController::class, 'index'])->name('partners.index');
        Route::get('/data', [PartnerController::class, 'getPartners'])->name('partners.data');
        Route::get('/create', [PartnerController::class, 'create'])->name('partners.create');
        Route::get('/{partner}', [PartnerController::class, 'show'])->name('partners.show');
        Route::get('/{partner}/edit', [PartnerController::class, 'edit'])->name('partners.edit');

        Route::post('/', [PartnerController::class, 'store'])->name('partners.store');
        Route::put('/{partner}', [PartnerController::class, 'update'])->name('partners.update');
        Route::delete('/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');
    });


    Route::prefix('testimonials')->group(function () {
        Route::get('/', [TestimonialController::class, 'index'])->name('testimonials.index');
        Route::get('/data', [TestimonialController::class, 'getTestimonials'])->name('testimonials.data');
        Route::get('/create', [TestimonialController::class, 'create'])->name('testimonials.create');
        Route::get('/{testimonial}', [TestimonialController::class, 'show'])->name('testimonials.show');
        Route::get('/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');

        Route::post('/', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::put('/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
        Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    });


    Route::prefix('donations')->group(function () {
        Route::get('/', [DonationController::class, 'indexAdmin'])->name('donations.index');
        Route::get('/data', [DonationController::class, 'getDonations'])->name('donations.data');
        Route::get('/create', [DonationController::class, 'create'])->name('donations.create');
        Route::get('/{donation}', [AnnualReportController::class, 'show'])->name('annual-reports.show');
        Route::get('/{donation}', [DonationController::class, 'show'])->name('donations.show');
        Route::get('/{donation}/edit', [DonationController::class, 'edit'])->name('donations.edit');

        Route::post('/', [DonationController::class, 'store'])->name('donations.store');
        Route::put('/{donation}', [DonationController::class, 'update'])->name('donations.update');
        Route::delete('/{donation}', [DonationController::class, 'destroy'])->name('donations.destroy');
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'indexAdmin'])->name('transactions.index');
        Route::get('/data', [TransactionController::class, 'getTransactions'])->name('transactions.data');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    });



    Route::prefix('users')->group(function () {
        Route::get('/role/user', [UserController::class, 'index'])->name('users.index');
        Route::get('/role/admin', [UserController::class, 'index'])->name('admins.index');
        Route::get('/role/superadmin', [UserController::class, 'index'])->name('superadmins.index');

        Route::get('/data', [UserController::class, 'getUsers'])->name('users.data');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::put('/{user}', [UserController::class, 'updateByAdmin'])->name('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });


    Route::prefix('profiles')->group(function () {
        Route::get('/indonesia', [MetaDataController::class, 'index'])->name('profiles.indonesia.index');
        Route::get('/japan', [MetaDataController::class, 'index'])->name('profiles.japan.index');

        Route::post('/indonesia', [MetaDataController::class, 'storeIndonesiaProfile'])->name('profiles.indonesia.store');
        Route::post('/japan', [MetaDataController::class, 'storeJapanProfile'])->name('profiles.japan.store');
    });


    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/', [ReportController::class, 'store'])->name('reports.store');
        Route::put('/{report}', [ReportController::class, 'update'])->name('reports.update'); // Memperbaiki route update
    });


    Route::prefix('annual-reports')->group(function () {

        Route::get('/', [AnnualReportController::class, 'index'])->name('annual-reports.index');
        Route::get('/data', [AnnualReportController::class, 'getReports'])->name('annual-reports.data');
        Route::get('/create', [AnnualReportController::class, 'create'])->name('annual-reports.create');
        Route::get('/{report}', [AnnualReportController::class, 'show'])->name('annual-reports.show');
        Route::get('/{report}/edit', [AnnualReportController::class, 'edit'])->name('annual-reports.edit');


        Route::post('/', [AnnualReportController::class, 'store'])->name('annual-reports.store');
        Route::put('/{report}', [AnnualReportController::class, 'update'])->name('annual-reports.update');
        Route::delete('/{report}', [AnnualReportController::class, 'destroy'])->name('annual-reports.destroy');
    });


    Route::prefix('financial-reports')->group(function () {

        Route::get('/', [FinancialReportController::class, 'index'])->name('financial-reports.index');
        Route::get('/data', [FinancialReportController::class, 'getReports'])->name('financial-reports.data');
        Route::get('/create', [FinancialReportController::class, 'create'])->name('financial-reports.create');
        Route::get('/{report}', [FinancialReportController::class, 'show'])->name('financial-reports.show');
        Route::get('/{report}/edit', [FinancialReportController::class, 'edit'])->name('financial-reports.edit');


        Route::post('/', [FinancialReportController::class, 'store'])->name('financial-reports.store');
        Route::put('/{report}', [FinancialReportController::class, 'update'])->name('financial-reports.update');
        Route::delete('/{report}', [FinancialReportController::class, 'destroy'])->name('financial-reports.destroy');
    });


    Route::prefix('monthly-reports')->group(function () {

        Route::get('/', [MonthlyReportController::class, 'index'])->name('monthly-reports.index');
        Route::get('/data', [MonthlyReportController::class, 'getReports'])->name('monthly-reports.data');
        Route::get('/create', [MonthlyReportController::class, 'create'])->name('monthly-reports.create');
        Route::get('/{report}', [MonthlyReportController::class, 'show'])->name('monthly-reports.show');
        Route::get('/{report}/edit', [MonthlyReportController::class, 'edit'])->name('monthly-reports.edit');


        Route::post('/', [MonthlyReportController::class, 'store'])->name('monthly-reports.store');
        Route::put('/{report}', [MonthlyReportController::class, 'update'])->name('monthly-reports.update');
        Route::delete('/{report}', [MonthlyReportController::class, 'destroy'])->name('monthly-reports.destroy');
    });

    // teams
    Route::prefix('teams')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('teams.index');
        Route::get('/data', [TeamController::class, 'getTeams'])->name('teams.data');
        Route::get('/create', [TeamController::class, 'create'])->name('teams.create');
        Route::get('/{team}', [TeamController::class, 'show'])->name('teams.show');
        Route::get('/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');

        Route::post('/', [TeamController::class, 'store'])->name('teams.store');
        Route::put('/{team}', [TeamController::class, 'update'])->name('teams.update');
        Route::delete('/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    });

    // services
    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('services.index');
        Route::get('/data', [ServiceController::class, 'getServices'])->name('services.data');
        Route::get('/create', [ServiceController::class, 'create'])->name('services.create');
        Route::get('/{service}', [ServiceController::class, 'show'])->name('services.show');
        Route::get('/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');

        Route::post('/', [ServiceController::class, 'store'])->name('services.store');
        Route::put('/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    });

    // office-locations
    Route::prefix('office-locations')->group(function () {
        Route::get('/', [OfficeLocationController::class, 'index'])->name('office-locations.index');
        Route::get('/data', [OfficeLocationController::class, 'getOfficeLocations'])->name('office-locations.data');
        Route::get('/create', [OfficeLocationController::class, 'create'])->name('office-locations.create');
        Route::get('/{id}', [OfficeLocationController::class, 'show'])->name('office-locations.show');
        Route::get('/{id}/edit', [OfficeLocationController::class, 'edit'])->name('office-locations.edit');

        Route::post('/', [OfficeLocationController::class, 'store'])->name('office-locations.store');
        Route::put('/{id}', [OfficeLocationController::class, 'update'])->name('office-locations.update');
        Route::delete('/{id}', [OfficeLocationController::class, 'destroy'])->name('office-locations.destroy');
    });

    // social media
    Route::prefix('social-media')->group(function () {
        Route::get('/', [SocialMediaController::class, 'index'])->name('social-media.index');
        Route::get('/data', [SocialMediaController::class, 'getSocialMedia'])->name('social-media.data');
        Route::get('/create', [SocialMediaController::class, 'create'])->name('social-media.create');
        Route::get('/{id}', [SocialMediaController::class, 'show'])->name('social-media.show');
        Route::get('/{id}/edit', [SocialMediaController::class, 'edit'])->name('social-media.edit');

        Route::post('/', [SocialMediaController::class, 'store'])->name('social-media.store');
        Route::put('/{id}', [SocialMediaController::class, 'update'])->name('social-media.update');
        Route::delete('/{id}', [SocialMediaController::class, 'destroy'])->name('social-media.destroy');
    });
});
