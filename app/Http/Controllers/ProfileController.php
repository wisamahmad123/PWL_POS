<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;
use Yajra\DataTables\DataTables as DataTablesDataTables;
use Yajra\DataTables\Facades\DataTables;

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

        return redirect('/');
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        error_log(`$user`);
        // Pastikan user ada
        if (!$user || !($user instanceof UserModel)) {
            return redirect()->back()->withErrors(['msg' => 'User not found.']);
        }

        return DataTables::of($user)
            ->addIndexColumn() // Jika ingin menambahkan indeks otomatis
            ->addColumn('aksi', function ($user) { // Menggunakan addColumn, bukan addRow
                $btn = '<button onclick="modalAction(\'' . url('/profile/' . $user->user_id . '/change_profile') . '\')" class="btn btn-warning btn-sm">Change Profile</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/profile/' . $user->user_id . '/change_password') . '\')" class="btn btn-danger btn-sm">Change Password</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // rawColumns, bukan rawRows untuk mengizinkan HTML
            ->make(true);
    }

    public function change_profile($id)
    {
        $user = UserModel::findOrFail($id);
        return view('profile.change_profile', compact('user'));
    }
}
