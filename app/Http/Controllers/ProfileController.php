<?php

namespace App\Http\Controllers;

use App\Models\Profile;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Pejabat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'id_pejabat' => 'required|integer',
            'hk' => 'required|integer',
        ]);

        $user = Auth::user();
        $userId = $user->id;

        $user->username = $validatedData['username'];
        $user->name = $validatedData['name'];
        $user->save();

        $profile = Profile::firstOrNew(['id_user' => $userId]);
        $profile->id_pejabat = $validatedData['id_pejabat'];
        $profile->hk = $validatedData['hk'];
        $profile->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
