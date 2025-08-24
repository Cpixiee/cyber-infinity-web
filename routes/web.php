<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkshopsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/test-tailwind', function () {
    return view('test-tailwind');
})->name('test.tailwind');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Auth Routes Group
Route::middleware(['auth'])->group(function () {
    // Dashboard sebagai halaman utama setelah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Workshop Routes for Admin
    Route::group(['middleware' => ['auth', \App\Http\Middleware\AdminMiddleware::class]], function () {
        Route::prefix('workshops')->name('workshops.')->group(function () {
            Route::get('/', [WorkshopsController::class, 'index'])->name('index');
            Route::get('/create', [WorkshopsController::class, 'create'])->name('create');
            Route::post('/', [WorkshopsController::class, 'store'])->name('store');
            Route::get('/{workshop}/edit', [WorkshopsController::class, 'edit'])->name('edit');
            Route::put('/{workshop}', [WorkshopsController::class, 'update'])->name('update');
            Route::delete('/{workshop}', [WorkshopsController::class, 'destroy'])->name('destroy');
        });
    });

    // Workshop Routes for All Users
    Route::prefix('workshops')->name('workshops.')->group(function () {
        Route::get('/', [WorkshopsController::class, 'index'])->name('index');
        Route::post('/{workshop}/register', [RegistrationController::class, 'store'])->name('register');
    });

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/mark-all-read', function() {
            auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
            return response()->json(['success' => true, 'message' => 'Semua notifikasi telah dibaca']);
        })->name('markAllRead');
        
        Route::delete('/{notification}', function(\App\Models\Notification $notification) {
            if ($notification->user_id !== auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            $notification->delete();
            return response()->json(['success' => true, 'message' => 'Notifikasi berhasil dihapus']);
        })->name('delete');
        
        Route::delete('/', function() {
            auth()->user()->notifications()->delete();
            return response()->json(['success' => true, 'message' => 'Semua notifikasi berhasil dihapus']);
        })->name('deleteAll');
    });

    // Registration Routes for Admin
    // Admin Routes
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Registrations Management
        Route::prefix('registrations')->name('registrations.')->group(function () {
            Route::get('/', [RegistrationController::class, 'index'])->name('index');
            Route::patch('/{registration}/approve', [RegistrationController::class, 'approve'])->name('approve');
            Route::patch('/{registration}/reject', [RegistrationController::class, 'reject'])->name('reject');
        });
    });
});