<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    // Index
    public function index()
    {
        $user = Auth::user();
        return view('public.settings', compact('user'));
    }

    // Update profile picture
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // delete old
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // store new
        $path = $request->file('profile_image')->store('profile-images', 'public');

        // update model
        $user->update([
            'profile_image' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui',
            'imageUrl' => asset('storage/' . $path),
        ]);
    }

    // Update username
    public function updateUsername(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:3',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Username berhasil diperbarui',
            'name' => $user->name,
        ]);
    }

    // Update email
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update([
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diperbarui',
            'email' => $user->email,
        ]);
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // verify current
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini tidak sesuai',
            ], 422);
        }

        // set new password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui',
        ]);
    }

    // Remove profile picture
    public function removeProfilePicture()
    {
        $user = Auth::user();

        // delete file
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // null profile image
        $user->update([
            'profile_image' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus',
        ]);
    }
}
