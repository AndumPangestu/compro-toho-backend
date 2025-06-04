<?php

use App\Models\Broadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ServiceController;
use App\Http\Middleware\EmailVerifiedCheck;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AnnualReportController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\MonthlyReportController;
use App\Http\Controllers\OfficeLocationController;
use App\Http\Middleware\TransactionAuthMiddleware;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\EmailSubscriberController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\DonationCategoryController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\MetaDataController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'userLogin']);
Route::post('/auth/social/{provider}', [AuthController::class, 'socialLogin']);

//password reset
Route::post('/auth/forgot-password', [PasswordController::class, 'sendResetLinkEmail'])->middleware(['throttle:6,1']);
Route::get('/auth/reset-password/{token}', [PasswordController::class, 'getToken'])->name('password.reset');
Route::post('/auth/reset-password', [PasswordController::class, 'resetPassword']);

Route::get('/auth/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed'])->name('verification.verify');
Route::post('/auth/email/verification-notification', [EmailVerificationController::class, 'resendVerificationEmail'])->middleware(['throttle:6,1']);

route::middleware([AuthMiddleware::class, EmailVerifiedCheck::class])->group(function () {

    Route::post('/auth/change-password', [PasswordController::class, 'changePassword']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::prefix('users')->group(function () {

        Route::prefix('current/transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'indexUser']);
            Route::get('/{transaction}', [TransactionController::class, 'show']);
        });

        Route::put('/current', [UserController::class, 'updateByUser']);
        Route::get('/current', [UserController::class, 'showByUser']);
    });
});

Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::get('/{banner}', [BannerController::class, 'show']);
});

Route::prefix('faqs')->group(function () {
    Route::get('/', [FaqController::class, 'index']);
    Route::get('/{faq}', [FaqController::class, 'show']);
});

Route::prefix('testimonials')->group(function () {
    Route::get('/', [TestimonialController::class, 'index']);
    Route::get('/{testimonial}', [TestimonialController::class, 'show']);
});

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'indexUser']);
    Route::get('/{article}', [ArticleController::class, 'show']);
});

Route::prefix('article-categories')->group(function () {
    Route::get('/', [ArticleCategoryController::class, 'index']);
    Route::get('/{articleCategory}', [ArticleCategoryController::class, 'show']);
});

Route::prefix('partners')->group(function () {
    Route::get('/', [PartnerController::class, 'index']);
    Route::get('/{partner}', [PartnerController::class, 'show']);
});

Route::prefix('donation-categories')->group(function () {
    Route::get('/', [DonationCategoryController::class, 'index']);
    Route::get('/{donationCategory}', [DonationCategoryController::class, 'show']);
});

Route::prefix('teams')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/{team}', [TeamController::class, 'show'])->name('teams.show');
});

Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/{service}', [ServiceController::class, 'show'])->name('services.show');
});

Route::prefix('office-locations')->group(function () {
    Route::get('/', [OfficeLocationController::class, 'index'])->name('office-locations.index');
    Route::get('/{id}', [OfficeLocationController::class, 'show'])->name('office-locations.show');
});

Route::prefix('social-media')->group(function () {
    Route::get('/', [SocialMediaController::class, 'index'])->name('social-media.index');
    Route::get('/{id}', [SocialMediaController::class, 'show'])->name('social-media.show');
});

Route::prefix('donations')->group(function () {
    Route::get('/', [DonationController::class, 'indexUser']);
    Route::get('/{donation}', [DonationController::class, 'show']);
    Route::get('/{donation}/transactions', [DonationController::class, 'transactions']);
});

Route::get('/profiles/{type}', [MetaDataController::class, 'getProfile']);

Route::post('/transactions/callback', [TransactionController::class, 'callback']);

Route::get('/financial-report', [FinancialReportController::class, 'getFinancialReport']);
Route::get('/annual-report', [AnnualReportController::class, 'getAnnualReport']);
Route::get('/monthly-report', [MonthlyReportController::class, 'getMonthlyReports']);

Route::post('/email-subscribe', [EmailSubscriberController::class, 'store']);

Route::post('/transactions', [TransactionController::class, 'store'])->middleware([TransactionAuthMiddleware::class]);

Route::get('/general-reports', [ReportController::class, 'index'])->name('reports.index');


Route::post('/fcm-tokens', [BroadcastController::class, 'storeUserFcmToken']);
