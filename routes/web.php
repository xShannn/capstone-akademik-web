<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Kalau kamu ingin ada halaman web tambahan khusus admin
    Route::get('/admin-only', function () {
        return "Admin Area";
    });
});
