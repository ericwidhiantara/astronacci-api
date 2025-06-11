<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $request->validate(['avatar' => 'image|max:2048']);
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Validate and update other fields
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'sometimes|string|max:1000',
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|string|max:50',
            'location' => 'sometimes|string|max:255',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    // Assuming these methods exist from a basic implementation
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn($query) => $query->where('name', 'like', '%' . $request->search . '%'))
            ->paginate(10);
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
}
