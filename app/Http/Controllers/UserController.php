<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Define validation rules
        $rules = [
            'avatar' => 'sometimes|image|max:2048',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'sometimes|string|max:1000',
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|string|max:50',
            'location' => 'sometimes|string|max:255',
        ];

        // Create validator
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return ResponseFormatter::error([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 'Validation failed', 400);
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }

            $validated = $validator->validated();

            $user->update($validated);

            DB::commit();

            return ResponseFormatter::success($user, 'Profile updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'An error occurred, please try again later',
            ], 'Internal Server Error', 500);
        }
    }
    // Assuming these methods exist from a basic implementation
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn($query) => $query->where('name', 'like', '%' . $request->search . '%'))
            ->paginate(10);

        return ResponseFormatter::success(
            $users,
            'Users retrieved successfully'
        );
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return ResponseFormatter::error(
                null,
                'User not found',
                404
            );
        }
        return ResponseFormatter::success(
            $user,
            'User retrieved successfully'
        );
    }
}
