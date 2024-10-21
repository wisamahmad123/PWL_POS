<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Home', 'Profile']
        ];

        $page = (object) [
            'title' => 'Profile User'
        ];

        $activeMenu = 'profile'; // set menu yang sedang aktif

        return view('profile.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function upload(Request $request)
    {
        // Validasi file
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Pastikan user ada
        if (!$user || !($user instanceof UserModel)) {
            return redirect()->back()->withErrors(['msg' => 'User not found.']);
        }

        // Menyimpan file di storage (public/profile_pictures)
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_pictures', $fileName);

            // Menghapus foto profil lama jika ada
            if (isset($user->profile_picture) && $user->profile_picture) {
                // Cek apakah file lama ada sebelum mencoba menghapusnya
                $oldFilePath = 'public/profile_pictures/' . $user->profile_picture;
                if (Storage::exists($oldFilePath)) {
                    Storage::delete($oldFilePath);
                }
            }

            // Simpan nama file di database
            $user->profile_picture = $fileName;
            $user->save();
        }

        return redirect('/profile');
    }

    public function list()
    {
        $profile = UserModel::select('user_id', 'username', 'nama');
        return DataTables::of($profile)
            ->make(true);
    }


    public function change_profile($id)
    {
        $profile = UserModel::findOrFail($id);
        return view('profile.change_profile', compact('profile'));
    }

    // Menyimpan perubahan data stok barang ajax
    public function update_profile(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'username' => 'required|String|max: 20',
                'nama' => 'required|String|max: 100'
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = UserModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function change_password($id)
    {
        $password = UserModel::findOrFail($id);
        return view('profile.change_password', compact('password'));
    }

    public function update_password(Request $request, $id)
{
    // cek apakah request dari ajax
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'old_password' => 'required|string|min:5', // Validasi untuk password lama
            'new_password' => 'required|string|min:5', // Validasi untuk password baru
        ];

        // Validasi input
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors() // menunjukkan field mana yang error
            ]);
        }

        $user = UserModel::find($id);
        if ($user) {
            // Verifikasi password lama
            if (Hash::check($request->old_password, $user->password)) {
                // Update password baru
                $user->password = Hash::make($request->new_password);
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Password berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Password lama salah'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
    }

}
