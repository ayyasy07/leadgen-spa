<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\TestimonialController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\TreatmentController;
use App\Http\Controllers\Frontend\PackageController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\BookingController;
use App\Http\Controllers\Frontend\ChatbotController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\HistoryController;
use App\Http\Controllers\Frontend\ProfileController;

// ============================================
// HOME
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('home', function () { return redirect('/'); });

// ============================================
// DUITKU CALLBACK (Ditaruh di web.php biar pasti kebaca)
// ============================================
Route::post('api/duitku/callback', [PaymentController::class, 'callback'])->name('duitku.callback');

// ============================================
// FRONTEND
// ============================================
Route::get('about', [PageController::class, 'about'])->name('about');
Route::get('treatments', [TreatmentController::class, 'index'])->name('treatments.index');
Route::get('treatment/{slug}', [TreatmentController::class, 'show'])->name('treatments.show');
Route::get('packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::view('contact', 'user.pages.contact')->name('contact.index');
Route::post('contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// ============================================
// BOOKING
// ============================================
Route::get('booking', [BookingController::class, 'index'])->name('booking.index');
Route::post('booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('booking/slots', [BookingController::class, 'slots'])->name('booking.slots');
Route::get('booking/success/{code}', [BookingController::class, 'success'])->name('booking.success');

// ============================================
// PAYMENT (Duitku)
// ============================================
Route::get('payment/methods', [PaymentController::class, 'methods'])->name('payment.methods');
Route::get('payment/status/{code}', [PaymentController::class, 'status'])->name('payment.status');
Route::post('payment/create/{code}', [PaymentController::class, 'create'])->name('payment.create');

// ============================================
// HISTORY ORDER USER
// ============================================
Route::get('history', [HistoryController::class, 'index'])->name('history.index');
Route::get('history/{code}/proof', [HistoryController::class, 'proof'])->name('history.proof');
Route::get('history/{code}', [HistoryController::class, 'show'])->name('history.show');
Route::post('history/{code}/cancel', [HistoryController::class, 'cancel'])->name('history.cancel');

// ============================================
// PROFILE USER
// ============================================
Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ============================================
// USER AUTH
// ============================================
Route::middleware('loggedin')->group(function () {
    Route::get('login', [AuthController::class, 'loginView'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
    Route::get('register', [AuthController::class, 'registerView'])->name('register.index');
    Route::post('register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

// Chatbot Route
Route::post('chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');