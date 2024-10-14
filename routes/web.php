<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('registrasi', [AuthController::class, 'registrasi']);
Route::post('registrasi', [AuthController::class, 'postregistrasi']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', [WelcomeController::class, 'index']);

    Route::middleware(['authorize:ADM'])-> group(function () {
        Route::get('/level', [LevelController::class, 'index']);              // menampilkan halaman awal level
        Route::post('/level/list', [LevelController::class, 'list']);          // menampilkan data level dalam bentuk json untuk datatables
        Route::get('/level/create', [LevelController::class, 'create']);       // menampilkan halaman form tambah user
        Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan halaman form tambah level Ajax
        Route::post('/level/ajax', [LevelController::class, 'store_ajax']); // Menyimpan data level baru Ajax
        Route::post('/level', [LevelController::class, 'store']);             // menyimpan data level baru
        Route::get('/level/{id}', [LevelController::class, 'show']);           // menampilkan detail level
        Route::get('/level/{id}/edit', [LevelController::class, 'edit']);     // menampilkan halaman form edit level
        Route::put('/level/{id}', [LevelController::class, 'update']);         // menyiapkan perubahan data level
        Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // Menampilkan halaman form edit level Ajax 
        Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']); // Menyimpan perubahan data level Ajax
        Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete level Ajax
        Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Untuk hapus data level Ajax
        Route::delete('/level/{id}', [LevelController::class, 'destroy']);     // menghapus data level
    });

    Route::middleware(['authorize:ADM'])-> group(function () {
        Route::get('/user', [UserController::class, 'index']);              // menampilkan halaman awal user
        Route::post('/user/list', [UserController::class, 'list']);          // menampilkan data user dalam bentuk json untuk datatables
        Route::get('/user/create', [UserController::class, 'create']);       // menampilkan halaman form tambah user
        Route::post('/user', [UserController::class, 'store']);             // menyimpan data user baru
        Route::get('/user/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/user/ajax', [UserController::class, 'store_ajax']); // Menyimpan data user baru Ajax
        Route::get('/user/{id}', [UserController::class, 'show']);           // menampilkan detail user
        Route::get('/user/{id}/edit', [UserController::class, 'edit']);     // menampilkan halaman form edit user
        Route::put('/user/{id}', [UserController::class, 'update']);         // menyiapkan perubahan data user
        Route::get('/user/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax 
        Route::put('/user/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
        Route::get('/user/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/user/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
        Route::delete('/user/{id}', [UserController::class, 'destroy']);     // menghapus data user
    });
    
    Route::middleware(['authorize:ADM,MNG'])-> group(function () {
        Route::get('/kategori', [KategoriController::class, 'index']);              // menampilkan halaman awal kategori
        Route::post('/kategori/list', [KategoriController::class, 'list']);          // menampilkan data kategori dalam bentuk json untuk datatables
        Route::get('/kategori/create', [KategoriController::class, 'create']);       // menampilkan halaman form tambah kategori
        Route::get('/kategori/create_ajax', [KategoriController::class, 'create_ajax']); // Menampilkan halaman form tambah kategori Ajax
        Route::post('/kategori/ajax', [KategoriController::class, 'store_ajax']); // Menyimpan data kategori baru Ajax
        Route::post('/kategori', [KategoriController::class, 'store']);             // menyimpan data kategori baru
        Route::get('/kategori/{id}', [KategoriController::class, 'show']);           // menampilkan detail kategori
        Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit']);     // menampilkan halaman form edit kategori
        Route::put('/kategori/{id}', [KategoriController::class, 'update']);         // menyiapkan perubahan data kategori
        Route::get('/kategori/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // Menampilkan halaman form edit kategori Ajax 
        Route::put('/kategori/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // Menyimpan perubahan data kategori Ajax
        Route::get('/kategori/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete kategori Ajax
        Route::delete('/kategori/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Untuk hapus data kategori Ajax
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy']);     // menghapus data kategori
    });

    Route::middleware(['authorize:ADM,MNG'])-> group(function () {
        Route::get('/barang', [BarangController::class, 'index']);              // menampilkan halaman awal barang
        Route::post('/barang/list', [BarangController::class, 'list']);          // menampilkan data barang dalam bentuk json untuk datatables
        Route::get('/barang/create_ajax', [BarangController::class, 'create_ajax']); // Menampilkan halaman form tambah barang Ajax
        Route::post('/barang/ajax', [BarangController::class, 'store_ajax']); // Menyimpan data barang baru Ajax
        Route::get('/barang/{id}', [BarangController::class, 'show']);           // menampilkan detail barang
        Route::get('/barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // Menampilkan halaman form edit barang Ajax 
        Route::put('/barang/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Menyimpan perubahan data barang Ajax
        Route::get('/barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete barang Ajax
        Route::delete('/barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Untuk hapus data barang Ajax
        Route::get('/barang/import', [BarangController::class, 'import']); // Ajax Form upload excel
        Route::post('/barang/import_ajax', [BarangController::class, 'import_ajax']); // ajax import excel
        Route::GET('/barang/export_excel', [BarangController::class, 'export_excel']); // export excel
        Route::GET('/barang/export_pdf', [BarangController::class, 'export_pdf']); // export pdf


    });
    Route::middleware(['authorize:ADM,MNG'])-> group(function () {
        Route::get('/supplier', [SupplierController::class, 'index']);              // menampilkan halaman awal supplier
        Route::post('/supplier/list', [SupplierController::class, 'list']);          // menampilkan data supplier dalam bentuk json untuk datatables
        Route::get('/supplier/create', [SupplierController::class, 'create']);       // menampilkan halaman form tambah supplier
        Route::get('/supplier/create_ajax', [SupplierController::class, 'create_ajax']); // Menampilkan halaman form tambah supplier Ajax
        Route::post('/supplier/ajax', [SupplierController::class, 'store_ajax']); // Menyimpan data supplier baru Ajax
        Route::post('/supplier', [SupplierController::class, 'store']);             // menyimpan data supplier baru
        Route::get('/supplier/{id}', [SupplierController::class, 'show']);           // menampilkan detail supplier
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']);     // menampilkan halaman form edit supplier
        Route::put('/supplier/{id}', [SupplierController::class, 'update']);         // menyiapkan perubahan data supplier
        Route::get('/supplier/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // Menampilkan halaman form edit supplier Ajax 
        Route::put('/supplier/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // Menyimpan perubahan data supplier Ajax
        Route::get('/supplier/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete supplier Ajax
        Route::delete('/supplier/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Untuk hapus data supplier Ajax
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']);     // menghapus data supplier
    });
});
