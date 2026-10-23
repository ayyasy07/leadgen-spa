<?php

use Illuminate\Support\Facades\Route;

// ============================================
// ADMIN AUTH
// ============================================
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NotificationController;

// ============================================
// ADMIN DASHBOARD
// ============================================
use App\Http\Controllers\Admin\DashboardController;

// ============================================
// ADMIN GLOBAL SEARCH
// ============================================
use App\Http\Controllers\Admin\GlobalSearchController;

// ============================================
// COLOR SCHEME + DARK MODE
// ============================================
use App\Http\Controllers\ColorSchemeController;
use App\Http\Controllers\DarkModeController;

use App\Http\Controllers\Admin\CalendarController;

// ============================================
// ADMIN CRUD
// ============================================
use App\Http\Controllers\Admin\TreatmentController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\GalleryCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\TherapistController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\CkeditorUploadController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Admin Routes — Prefix: /admin  |  Name: admin.*
|--------------------------------------------------------------------------
*/

// ============================================
// ADMIN AUTH
// ============================================
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('loggedin')->group(function () {
        Route::get('login', [AuthController::class, 'loginView'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });
    Route::get('preview/{page}', function ($page) {
    $view = 'pages.' . $page;
    if (!view()->exists($view)) abort(404);
    return view($view, ['layout' => 'side-menu']);
})->name('preview');
});

// ============================================
// ADMIN PANEL (Auth Required)
// ============================================
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    
    // ==========================================
    // NOTIFICATIONS (AJAX POLLING)
    // ==========================================
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // ==========================================
    // COLOR SCHEME + DARK MODE SWITCHER
    // ==========================================
    Route::get('color-scheme-switcher/{color_scheme}', [ColorSchemeController::class, 'switch'])->name('color-scheme-switcher');
    Route::get('dark-mode-switcher', [DarkModeController::class, 'switch'])->name('dark-mode-switcher');

    // ==========================================
    // CKEDITOR UPLOAD (IMAGE)
    // ==========================================
    Route::post('ckeditor/upload', [CkeditorUploadController::class, 'upload'])->name('ckeditor.upload');

    // ==========================================
    // DASHBOARD
    // ==========================================
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('dashboard-overview-1', [DashboardController::class, 'index'])->name('dashboard-overview-1');
    Route::get('dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

    // ==========================================
    // GLOBAL SEARCH (AJAX)
    // ==========================================
    Route::get('global-search', [GlobalSearchController::class, 'search'])->name('global-search');

    // ==========================================
    // ICEWALL DEMO ROUTES (redirect ke dashboard)
    // ==========================================
    $demoRedirects = [
        'dashboard-overview-2'         => 'dashboard-overview-2',
        'dashboard-overview-3'         => 'dashboard-overview-3',
        'crud-data-list'               => 'crud-data-list',
        'crud-form'                    => 'crud-form',
        'users-layout-1'               => 'users-layout-1',
        'users-layout-2'               => 'users-layout-2',
        'users-layout-3'               => 'users-layout-3',
        'profile-overview-1'           => 'profile-overview-1',
        'profile-overview-2'           => 'profile-overview-2',
        'profile-overview-3'           => 'profile-overview-3',
        'wizard-layout-1'              => 'wizard-layout-1',
        'wizard-layout-2'              => 'wizard-layout-2',
        'wizard-layout-3'              => 'wizard-layout-3',
        'blog-layout-1'                => 'blog-layout-1',
        'blog-layout-2'                => 'blog-layout-2',
        'blog-layout-3'                => 'blog-layout-3',
        'pricing-layout-1'             => 'pricing-layout-1',
        'pricing-layout-2'             => 'pricing-layout-2',
        'faq-layout-1'                 => 'faq-layout-1',
        'faq-layout-2'                 => 'faq-layout-2',
        'faq-layout-3'                 => 'faq-layout-3',
        'inbox'                        => 'inbox',
        'file-manager'                 => 'file-manager',
        'point-of-sale'                => 'point-of-sale',
        'chat'                         => 'chat',
        'post'                         => 'post',
        'calendar'                     => 'calendar',
        'regular-table'                => 'regular-table',
        'tabulator'                    => 'tabulator',
        'modal'                        => 'modal',
        'slide-over'                   => 'slide-over',
        'notification'                 => 'notification',
        'accordion'                    => 'accordion',
        'button'                       => 'button',
        'alert'                        => 'alert',
        'progress-bar'                 => 'progress-bar',
        'tooltip'                      => 'tooltip',
        'dropdown'                     => 'dropdown',
        'typography'                   => 'typography',
        'icon'                         => 'icon',
        'loading-icon'                 => 'loading-icon',
        'regular-form'                 => 'regular-form',
        'datepicker'                   => 'datepicker',
        'tom-select'                   => 'tom-select',
        'file-upload'                  => 'file-upload',
        'wysiwyg-editor-classic'       => 'wysiwyg-editor-classic',
        'wysiwyg-editor-inline'        => 'wysiwyg-editor-inline',
        'wysiwyg-editor-balloon'       => 'wysiwyg-editor-balloon',
        'wysiwyg-editor-balloon-block' => 'wysiwyg-editor-balloon-block',
        'wysiwyg-editor-document'      => 'wysiwyg-editor-document',
        'validation'                   => 'validation',
        'chart'                        => 'chart',
        'slider'                       => 'slider',
        'image-zoom'                   => 'image-zoom',
        'update-profile'               => 'update-profile',
        'change-password'              => 'change-password',
    ];

    foreach ($demoRedirects as $name => $path) {
        Route::get($path, function () {
            return redirect()->route('admin.dashboard');
        })->name($name);
    }

    // ==========================================
    // REORDER (harus DI ATAS resource)
    // ==========================================
    Route::post('hero-sliders/reorder', [HeroSliderController::class, 'reorder'])->name('hero-sliders.reorder');
    Route::post('galleries/reorder', [GalleryController::class, 'reorder'])->name('galleries.reorder');
    Route::post('blogs/reorder', [BlogController::class, 'reorder'])->name('blogs.reorder');
    Route::post('therapists/reorder', [TherapistController::class, 'reorder'])->name('therapists.reorder');

    // ==========================================
    // GALLERIES — BULK ACTION (harus DI ATAS resource)
    // ==========================================
    Route::post('galleries/bulk', [GalleryController::class, 'bulk'])->name('galleries.bulk');

    // ==========================================
    // RESOURCE CRUD
    // ==========================================
    Route::resource('treatments', TreatmentController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('galleries', GalleryController::class);
    Route::resource('gallery-categories', GalleryCategoryController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('blog-categories', BlogCategoryController::class);
    Route::resource('therapists', TherapistController::class);
    Route::resource('hero-sliders', HeroSliderController::class);
    Route::resource('users', UserController::class);

    // ==========================================
    // SETTINGS
    // ==========================================
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    // ==========================================
    // NEWSLETTER
    // ==========================================
    Route::get('newsletters', [NewsletterController::class, 'index'])->name('newsletters.index');
    Route::get('newsletters/export', [NewsletterController::class, 'export'])->name('newsletters.export');
    Route::get('newsletters/compose', [NewsletterController::class, 'compose'])->name('newsletters.compose');
    Route::post('newsletters/send', [NewsletterController::class, 'send'])->name('newsletters.send');
    Route::delete('newsletters/bulk-delete', [NewsletterController::class, 'bulkDelete'])->name('newsletters.bulk-delete');
    Route::patch('newsletters/{newsletter}/toggle', [NewsletterController::class, 'toggle'])->name('newsletters.toggle');
    Route::delete('newsletters/{newsletter}', [NewsletterController::class, 'destroy'])->name('newsletters.destroy');

    // ==========================================
    // CONTACT MESSAGES
    // ==========================================
    Route::post('contact-messages/bulk', [ContactMessageController::class, 'bulk'])->name('contact-messages.bulk');
    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::post('contact-messages/{contactMessage}/reply', [ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::patch('contact-messages/{contactMessage}/archive', [ContactMessageController::class, 'archive'])->name('contact-messages.archive');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    // ==========================================
    // BOOKINGS
    // ==========================================
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::patch('bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::delete('bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // ==========================================
    // CALENDAR
    // ==========================================
    Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('calendar/day/{date}', [CalendarController::class, 'day'])->name('calendar.day');
    Route::post('calendar', [CalendarController::class, 'store'])->name('calendar.store');
    Route::delete('calendar/{id}', [CalendarController::class, 'destroy'])->name('calendar.destroy');

    // Agenda CRUD
    Route::post('calendar/agenda', [CalendarController::class, 'storeAgenda'])->name('calendar.agenda.store');
    Route::put('calendar/agenda/{id}', [CalendarController::class, 'updateAgenda'])->name('calendar.agenda.update');
    Route::delete('calendar/agenda/{id}', [CalendarController::class, 'deleteAgenda'])->name('calendar.agenda.delete');

    // ==========================================
    // REPORTS
    // ==========================================
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Backward compat — redirect ke index dengan tab
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('reports/operational', [ReportController::class, 'operational'])->name('reports.operational');
});