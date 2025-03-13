<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updateProfile(array $data)
    {
        $user = Auth::user();

        // Update nama
        $user->name = $data['name'];

        // Update foto profil jika ada
        if (isset($data['profile_picture'])) {
            if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
                // Hapus foto lama jika ada
                Storage::delete('public/' . $user->profile_picture);
            }

            // Simpan foto baru
            $path = $data['profile_picture']->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->save();

        return $user;
    }
}
