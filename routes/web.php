<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DetailPenjualanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
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

    Route::middleware(['authorize:ADM,MNG,STF,CUS'])-> group(function () {
        Route::get('/profile', [ProfileController::class, 'index']);
        Route::post('/profile/list', [ProfileController::class, 'list']);
        Route::get('/profile/{id}/change_profile', [ProfileController::class, 'change_profile']);      
        Route::put('/profile/{id}/update_profile', [ProfileController::class, 'update_profile']);      
        Route::get('/profile/{id}/change_password', [ProfileController::class, 'change_password']);      
        Route::put('/profile/{id}/update_password', [ProfileController::class, 'update_password']);      
        Route::post('/profile/upload', [ProfileController::class, 'upload'])->name('profile.upload');
    });

    Route::middleware(['authorize:ADM'])-> group(function () {
        Route::get('/level', [LevelController::class, 'index']);              // menampilkan halaman awal level
        Route::post('/level/list', [LevelController::class, 'list']);          // menampilkan data level dalam bentuk json untuk datatables
        Route::get('/level/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan halaman form tambah level Ajax
        Route::post('/level/ajax', [LevelController::class, 'store_ajax']); // Menyimpan data level baru Ajax
        Route::get('/level/{id}', [LevelController::class, 'show']);           // menampilkan detail level
        Route::get('/level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // Menampilkan halaman form edit level Ajax 
        Route::put('/level/{id}/update_ajax', [LevelController::class, 'update_ajax']); // Menyimpan perubahan data level Ajax
        Route::get('/level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete level Ajax
        Route::delete('/level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Untuk hapus data level Ajax
        Route::get('/level/import', [LevelController::class, 'import']); // Ajax Form upload excel
        Route::post('/level/import_ajax', [LevelController::class, 'import_ajax']); // ajax import excel
        Route::GET('/level/export_excel', [LevelController::class, 'export_excel']); // export excel
        Route::GET('/level/export_pdf', [LevelController::class, 'export_pdf']); // export pdf
    });

    Route::middleware(['authorize:ADM'])-> group(function () {
        Route::get('/user', [UserController::class, 'index']);              // menampilkan halaman awal user
        Route::post('/user/list', [UserController::class, 'list']);          // menampilkan data user dalam bentuk json untuk datatables
        Route::get('/user/create_ajax', [UserController::class, 'create_ajax']); // Menampilkan halaman form tambah user Ajax
        Route::post('/user/ajax', [UserController::class, 'store_ajax']); // Menyimpan data user baru Ajax
        Route::get('/user/{id}', [UserController::class, 'show']);           // menampilkan detail user
        Route::get('/user/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // Menampilkan halaman form edit user Ajax 
        Route::put('/user/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
        Route::get('/user/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/user/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
        Route::get('/user/import', [UserController::class, 'import']); // Ajax Form upload excel
        Route::post('/user/import_ajax', [UserController::class, 'import_ajax']); // ajax import excel
        Route::GET('/user/export_excel', [UserController::class, 'export_excel']); // export excel
        Route::GET('/user/export_pdf', [UserController::class, 'export_pdf']); // export pdf
    });
    
    Route::middleware(['authorize:ADM,MNG,STF'])-> group(function () {
        Route::get('/kategori', [KategoriController::class, 'index']);              // menampilkan halaman awal kategori
        Route::post('/kategori/list', [KategoriController::class, 'list']);          // menampilkan data kategori dalam bentuk json untuk datatables
        Route::get('/kategori/create_ajax', [KategoriController::class, 'create_ajax']); // Menampilkan halaman form tambah kategori Ajax
        Route::post('/kategori/ajax', [KategoriController::class, 'store_ajax']); // Menyimpan data kategori baru Ajax
        Route::get('/kategori/{id}', [KategoriController::class, 'show']);           // menampilkan detail kategori
        Route::get('/kategori/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // Menampilkan halaman form edit kategori Ajax 
        Route::put('/kategori/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // Menyimpan perubahan data kategori Ajax
        Route::get('/kategori/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete kategori Ajax
        Route::delete('/kategori/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Untuk hapus data kategori Ajax
        Route::get('/kategori/import', [KategoriController::class, 'import']); // Ajax Form upload excel
        Route::post('/kategori/import_ajax', [KategoriController::class, 'import_ajax']); // ajax import excel
        Route::GET('/kategori/export_excel', [KategoriController::class, 'export_excel']); // export excel
        Route::GET('/kategori/export_pdf', [KategoriController::class, 'export_pdf']); // export pdf
    });

    Route::middleware(['authorize:ADM,MNG,STF'])-> group(function () {
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

    Route::middleware(['authorize:ADM,MNG,STF'])-> group(function () {
        Route::get('/supplier', [SupplierController::class, 'index']);              // menampilkan halaman awal supplier
        Route::post('/supplier/list', [SupplierController::class, 'list']);          // menampilkan data supplier dalam bentuk json untuk datatables
        Route::get('/supplier/create_ajax', [SupplierController::class, 'create_ajax']); // Menampilkan halaman form tambah supplier Ajax
        Route::post('/supplier/ajax', [SupplierController::class, 'store_ajax']); // Menyimpan data supplier baru Ajax
        Route::get('/supplier/{id}', [SupplierController::class, 'show']);           // menampilkan detail supplier
        Route::get('/supplier/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // Menampilkan halaman form edit supplier Ajax 
        Route::put('/supplier/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // Menyimpan perubahan data supplier Ajax
        Route::get('/supplier/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete supplier Ajax
        Route::delete('/supplier/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Untuk hapus data supplier Ajax
        Route::get('/supplier/import', [SupplierController::class, 'import']); // Ajax Form upload excel
        Route::post('/supplier/import_ajax', [SupplierController::class, 'import_ajax']); // ajax import excel
        Route::GET('/supplier/export_excel', [SupplierController::class, 'export_excel']); // export excel
        Route::GET('/supplier/export_pdf', [SupplierController::class, 'export_pdf']); // export pdf
    });
    Route::middleware(['authorize:ADM,MNG,STF'])-> group(function () {
        Route::get('/stok', [StokController::class, 'index']);              // menampilkan halaman awal supplier
        Route::post('/stok/list', [StokController::class, 'list']);          // menampilkan data supplier dalam bentuk json untuk datatables
        Route::get('/stok/create_ajax', [StokController::class, 'create_ajax']); // Menampilkan halaman form tambah supplier Ajax
        Route::post('/stok/ajax', [StokController::class, 'store_ajax']); // Menyimpan data supplier baru Ajax
        Route::get('/stok/{id}', [StokController::class, 'show']);           // menampilkan detail supplier
        Route::get('/stok/{id}/edit_ajax', [StokController::class, 'edit_ajax']); // Menampilkan halaman form edit supplier Ajax 
        Route::put('/stok/{id}/update_ajax', [StokController::class, 'update_ajax']); // Menyimpan perubahan data supplier Ajax
        Route::get('/stok/{id}/delete_ajax', [StokController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete supplier Ajax
        Route::delete('/stok/{id}/delete_ajax', [StokController::class, 'delete_ajax']); // Untuk hapus data supplier Ajax
        Route::get('/stok/import', [StokController::class, 'import']); // Ajax Form upload excel
        Route::post('/stok/import_ajax', [StokController::class, 'import_ajax']); // ajax import excel
        Route::GET('/stok/export_excel', [StokController::class, 'export_excel']); // export excel
        Route::GET('/stok/export_pdf', [StokController::class, 'export_pdf']); // export pdf
    });
    Route::middleware(['authorize:ADM,MNG,STF'])-> group(function () {
        Route::get('/penjualan', [PenjualanController::class, 'index']);              // menampilkan halaman awal supplier
        Route::post('/penjualan/list', [PenjualanController::class, 'list']);          // menampilkan data supplier dalam bentuk json untuk datatables
        Route::get('/penjualan/create_ajax', [PenjualanController::class, 'create_ajax']); // Menampilkan halaman form tambah supplier Ajax
        Route::post('/penjualan/ajax', [PenjualanController::class, 'store_ajax']); // Menyimpan data supplier baru Ajax
        Route::get('/penjualan/{id}/detail', [DetailPenjualanController::class, 'index']);           // menampilkan detail supplier
        Route::get('/penjualan/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax']); // Menampilkan halaman form edit supplier Ajax 
        Route::put('/penjualan/{id}/update_ajax', [PenjualanController::class, 'update_ajax']); // Menyimpan perubahan data supplier Ajax
        Route::get('/penjualan/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete supplier Ajax
        Route::delete('/penjualan/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']); // Untuk hapus data supplier Ajax
        Route::get('/penjualan/import', [PenjualanController::class, 'import']); // Ajax Form upload excel
        Route::post('/penjualan/import_ajax', [PenjualanController::class, 'import_ajax']); // ajax import excel
        Route::GET('/penjualan/export_excel', [PenjualanController::class, 'export_excel']); // export excel
        Route::GET('/penjualan/export_pdf', [PenjualanController::class, 'export_pdf']); // export pdf

        Route::post('/penjualan/{id}/detail/list', [DetailPenjualanController::class, 'list']);          // menampilkan data supplier dalam bentuk json untuk datatables
        Route::get('/penjualan/{id}/detail/create_ajax', [DetailPenjualanController::class, 'create_ajax']); // Menampilkan halaman form tambah supplier Ajax
        Route::post('/penjualan/{id}/detail/ajax', [DetailPenjualanController::class, 'store_ajax']); // Menyimpan data supplier baru Ajax
        Route::get('/penjualan/{penjualan_id}/detail/{detail_id}/show', [DetailPenjualanController::class, 'show']);           // menampilkan detail supplier
        Route::get('/penjualan/{penjualan_id}/detail/{detail_id}/edit_ajax', [DetailPenjualanController::class, 'edit_ajax']); // Menampilkan halaman form edit supplier Ajax 
        Route::put('/penjualan/{penjualan_id}/detail/{detail_id}/update_ajax', [DetailPenjualanController::class, 'update_ajax']); // Menyimpan perubahan data supplier Ajax
        Route::get('/penjualan/{penjualan_id}/detail/{detail_id}/delete_ajax', [DetailPenjualanController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete supplier Ajax
        Route::delete('/penjualan/{penjualan_id}/detail/{detail_id}/delete_ajax', [DetailPenjualanController::class, 'delete_ajax']); // Untuk hapus data supplier Ajax
        Route::get('/penjualan/{id}/detail/import', [DetailPenjualanController::class, 'import']); // Ajax Form upload excel
        Route::post('/penjualan/{id}/detail/import_ajax', [DetailPenjualanController::class, 'import_ajax']); // ajax import excel
        Route::GET('/penjualan/{id}/detail/export_excel', [DetailPenjualanController::class, 'export_excel']); // export excel
        Route::GET('/penjualan/{id}/detail/export_pdf', [DetailPenjualanController::class, 'export_pdf']); // export pdf
    });
});
