<?php

use Illuminate\Support\Facades\Route;

// 1. Halaman Login (GET & POST)
Route::get('/', function () {
    return view('login');
});

Route::post('/', function () {
    return view('login');
});

// 2. Dashboard Eksekutif
Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::post('/dashboard', function () {
    return view('dashboard');
});

// 3. Katalog Produk Skincare
Route::get('/catalog', function () {
    return view('index');
});

// 4. Tambah Produk Baru
Route::get('/catalog/create', function () {
    return view('create');
});

Route::post('/catalog/create', function () {
    return view('create');
});

// 5. Edit Produk (Mendukung /edit?id= dan /catalog/edit?id=)
Route::get('/edit', function () {
    return view('edit');
});

Route::post('/edit', function () {
    return view('edit');
});

Route::get('/catalog/edit', function () {
    return view('edit');
});

Route::post('/catalog/edit', function () {
    return view('edit');
});

// 6. Hapus Produk (Mendukung /delete?id= dan /catalog/delete?id=)
Route::get('/delete', function () {
    return view('delete');
});

Route::post('/delete', function () {
    return view('delete');
});

Route::get('/catalog/delete', function () {
    return view('delete');
});

Route::post('/catalog/delete', function () {
    return view('delete');
});

// 7. Modul Barang Masuk (Restock)
Route::get('/stock/in', function () {
    return view('stock.in');
});

Route::post('/stock/in', function () {
    return view('stock.in');
});

// 8. Modul Barang Keluar (Outflow)
Route::get('/stock/out', function () {
    return view('stock.out');
});

Route::post('/stock/out', function () {
    return view('stock.out');
});

// 9. Logout Pengguna
Route::get('/logout', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    session_destroy();
    return redirect('/');
});