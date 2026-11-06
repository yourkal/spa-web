<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'unique:users,phone,' . $user->id],
            'email'    => ['nullable', 'email', 'unique:users,email,' . $user->id],
            'avatar'   => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name'   => $request->name,
            'phone'  => $request->phone,
            'email'  => $request->email,
            'avatar' => $request->avatar ?: $user->avatar,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', __('Profil Anda berhasil diperbarui!'));
    }
}
