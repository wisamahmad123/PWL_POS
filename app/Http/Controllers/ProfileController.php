<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserModel;

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
}
