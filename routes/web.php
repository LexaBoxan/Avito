<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdController;
use App\Models\AdDescriptionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

// Гостевые маршруты
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

// Главная страница и просмотр объявлений
Route::get('/', [AdController::class, 'index'])->name('home');

Route::get('/ads/{ad}', [AdController::class, 'show'])
    ->whereNumber('ad')
    ->name('ads.show');

// Объявления пользователя
Route::middleware(['auth'])->prefix('ads')->name('ads.')->group(function () {
    Route::get('/create', [AdController::class, 'create'])->name('create');
    Route::post('/', [AdController::class, 'store'])->name('store');
    Route::get('/mine', [AdController::class, 'myAds'])->name('mine');
});

// Загрузка изображений из редактора
Route::post('/upload-image', function (Request $request) {

    if (!$request->hasFile('upload')) {
        Log::warning('CKEditor upload: no file', ['user_id' => optional($request->user())->id]);
        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'Файл не найден.']
        ], 400);
    }

    $file = $request->file('upload');

    $extension = strtolower($file->getClientOriginalExtension());
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $allowedTypes)) {
        Log::warning('CKEditor upload: wrong extension', [
            'user_id' => optional($request->user())->id,
            'extension' => $extension,
        ]);
        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => 'Неверный формат изображения.']
        ], 422);
    }

    $path = $file->store('uploads', 'public');

    $storedPath = Storage::url($path);
    $assetUrl = asset($storedPath);

    AdDescriptionImage::create([
        'user_id' => auth()->id(),
        'path' => $storedPath,
        'original_name' => $file->getClientOriginalName(),
        'size' => $file->getSize(),
    ]);

    Log::info('CKEditor upload: success', [
        'user_id' => auth()->id(),
        'path' => $storedPath,
        'size' => $file->getSize(),
    ]);

    return response()->json([
        'uploaded' => true,
        'fileName' => basename($path),
        'url' => $assetUrl,
    ]);

})->middleware('auth');

// Админ панель
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');

    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('users.updateRole');
});

// Панель модерации
Route::middleware(['auth', 'role:moderator,admin'])->prefix('moderation')->name('moderation.')->group(function () {
    Route::get('/', [AdController::class, 'moderate'])->name('index');
    Route::post('/{ad}/approve', [AdController::class, 'approve'])->name('approve');
    Route::post('/{ad}/reject', [AdController::class, 'reject'])->name('reject');
});

// Выход
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
