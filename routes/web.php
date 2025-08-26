<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkshopsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChallengeController;
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

// CAPTCHA Routes
Route::get('/captcha', [App\Http\Controllers\CaptchaController::class, 'generate'])->name('captcha.generate');
Route::post('/captcha/verify', [App\Http\Controllers\CaptchaController::class, 'verify'])->name('captcha.verify');

// Authentication Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1'); // 10 attempts per minute (lebih fleksibel)
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Auth Routes Group
Route::middleware(['auth'])->group(function () {
    // Dashboard sebagai halaman utama setelah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/send-otp', [ProfileController::class, 'sendOtp'])->name('profile.send-otp');
    Route::post('/profile/verify-otp', [ProfileController::class, 'verifyOtp'])->name('profile.verify-otp');
    Route::post('/profile/setup-username', [ProfileController::class, 'setupUsername'])->name('profile.setup-username');

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

    // Challenge Routes for All Users
    Route::prefix('challenges')->name('challenges.')->group(function () {
        Route::get('/', [ChallengeController::class, 'index'])->name('index');
        Route::get('/{challenge}', [ChallengeController::class, 'show'])->name('show');
        Route::post('/{challenge}/tasks/{task}/submit', [ChallengeController::class, 'submitFlag'])
            ->name('submit')
            ->middleware('throttle:10,1'); // 10 flag submissions per minute
        Route::post('/hints/{hint}/purchase', [ChallengeController::class, 'purchaseHint'])
            ->name('hint.purchase')
            ->middleware('throttle:5,1'); // 5 hint purchases per minute
    });

    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/mark-all-read', function() {
            /** @var \App\Models\User|null $user */
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user) {
                $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
            }
            return response()->json(['success' => true, 'message' => 'Semua notifikasi telah dibaca']);
        })->name('markAllRead');
        
        Route::delete('/{notification}', function(\App\Models\Notification $notification) {
            if ($notification->user_id !== \Illuminate\Support\Facades\Auth::id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            $notification->delete();
            return response()->json(['success' => true, 'message' => 'Notifikasi berhasil dihapus']);
        })->name('delete');
        
        Route::delete('/', function() {
            /** @var \App\Models\User|null $user */
            $user = \Illuminate\Support\Facades\Auth::user();
            if ($user) {
                $user->notifications()->delete();
            }
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

        // Challenge Management
        Route::prefix('challenges')->name('challenges.')->group(function () {
            Route::get('/', [ChallengeController::class, 'adminIndex'])->name('index');
            Route::get('/create', [ChallengeController::class, 'create'])->name('create');
            Route::post('/', [ChallengeController::class, 'store'])->name('store');
            Route::get('/{challenge}/edit', [ChallengeController::class, 'edit'])->name('edit');
            Route::put('/{challenge}', [ChallengeController::class, 'update'])->name('update');
            Route::delete('/{challenge}', [ChallengeController::class, 'destroy'])->name('destroy');
            
            // Task Management
            Route::get('/{challenge}/tasks', [ChallengeController::class, 'manageTasks'])->name('tasks');
            Route::post('/{challenge}/tasks', [ChallengeController::class, 'storeTask'])->name('tasks.store');
            Route::put('/tasks/{task}', [ChallengeController::class, 'updateTask'])->name('tasks.update');
            Route::delete('/tasks/{task}', [ChallengeController::class, 'destroyTask'])->name('tasks.destroy');
        });

        // CTF Management
        Route::prefix('ctf')->name('ctf.')->group(function () {
            Route::get('/', [App\Http\Controllers\CtfController::class, 'adminIndex'])->name('index');
            Route::get('/create', [App\Http\Controllers\CtfController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\CtfController::class, 'store'])->name('store');
            Route::get('/{ctf}/edit', [App\Http\Controllers\CtfController::class, 'edit'])->name('edit');
            Route::put('/{ctf}', [App\Http\Controllers\CtfController::class, 'update'])->name('update');
            Route::delete('/{ctf}', [App\Http\Controllers\CtfController::class, 'destroy'])->name('destroy');
            
            // Challenge Management
            Route::get('/{ctf}/challenges', [App\Http\Controllers\CtfController::class, 'manageChallenges'])->name('challenges');
            Route::get('/{ctf}/challenges/create', [App\Http\Controllers\CtfController::class, 'createChallenge'])->name('challenges.create');
            Route::post('/{ctf}/challenges', [App\Http\Controllers\CtfController::class, 'storeChallenge'])->name('challenges.store');
            Route::get('/{ctf}/challenges/{challenge}/edit', [App\Http\Controllers\CtfController::class, 'editChallenge'])->name('challenges.edit');
            Route::put('/{ctf}/challenges/{challenge}', [App\Http\Controllers\CtfController::class, 'updateChallenge'])->name('challenges.update');
            Route::delete('/{ctf}/challenges/{challenge}', [App\Http\Controllers\CtfController::class, 'destroyChallenge'])->name('challenges.destroy');
        });
    });

    // CTF Routes for All Users
    Route::prefix('ctf')->name('ctf.')->group(function () {
        Route::get('/', [App\Http\Controllers\CtfController::class, 'index'])->name('index');
        Route::get('/{ctf}', [App\Http\Controllers\CtfController::class, 'show'])->name('show');
        Route::get('/{ctf}/leaderboard', [App\Http\Controllers\CtfController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/{ctf}/user/{user}', [App\Http\Controllers\CtfController::class, 'userProfile'])->name('user.profile');
        Route::post('/{ctf}/challenges/{challenge}/submit', [App\Http\Controllers\CtfController::class, 'submitFlag'])
            ->name('submit')
            ->middleware('throttle:5,1'); // 5 CTF flag submissions per minute
        Route::post('/challenges/{challenge}/hints/purchase', [App\Http\Controllers\CtfController::class, 'purchaseHint'])
            ->name('hint.purchase')
            ->middleware('throttle:3,1'); // 3 CTF hint purchases per minute
    });
});