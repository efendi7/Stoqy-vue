<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ProfileService;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $this->profileService->updateProfile($request->all());
        
        // Refresh the authenticated user
        $request->session()->forget('_old_input');
        Auth::user()->refresh();  // This refreshes the user model from the database
        
        // Or alternatively:
        // Auth::login(Auth::user()->fresh());
    
        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
