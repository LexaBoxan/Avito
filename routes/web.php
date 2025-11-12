<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdController;
use Illuminate\Http\Request;

// Гостевые маршруты: регистрация и вход
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});



// Домашняя страница
Route::get('/', [AdController::class, 'index'])->name('home');

Route::get('/ads/{ad}', [AdController::class, 'show'])
    ->whereNumber('ad')
    ->name('ads.show');


Route::middleware(['auth'])->prefix('ads')->name('ads.')->group(function () {
    Route::get('/create', [AdController::class, 'create'])->name('create');
    Route::post('/', [AdController::class, 'store'])->name('store');
    Route::get('/mine', [AdController::class, 'myAds'])->name('mine');

});



Route::post('/upload-image', function (Request $request) {

    if (!$request->hasFile('upload')) {
        return response()->json([
            'error' => ['message' => 'Файл не был отправлен']
        ], 400);
    }

    $file = $request->file('upload');

    // Можно добавить проверку расширений
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
        return response()->json([
            'error' => ['message' => 'Недопустимый формат файла']
        ], 422);
    }

    // Сохраняем файл в storage/app/public/uploads
    $path = $file->store('uploads', 'public');

    return response()->json([
        "url" => asset("storage/" . $path), // <- CKEditor вставит картинку в текст
    ]);

})->middleware('auth');

// Только для админов
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');       // admin.dashboard
    Route::get('/users', [AdminController::class, 'users'])->name('users');      // admin.users

    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::post('/users/{user}/update-role', [AdminController::class, 'updateRole'])->name('users.updateRole');

});


// Только для модераторов и админов
Route::middleware(['auth', 'role:moderator,admin'])->prefix('moderation')->name('moderation.')->group(function () {
    Route::get('/', [AdController::class, 'moderate'])->name('index');
    Route::post('/{ad}/approve', [AdController::class, 'approve'])->name('approve');
    Route::post('/{ad}/reject', [AdController::class, 'reject'])->name('reject');
});

//Выход
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');