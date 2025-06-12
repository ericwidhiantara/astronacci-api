<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Define validation rules
        $rules = [
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
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 10);

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

    public function changePassword(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
            'old_password' => 'required|string',
            'password_confirmation' => 'required|string',
        ], [
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'old_password.required' => 'Old password is required',
            'password_confirmation.required' => 'Password confirmation is required',

        ]);
        if ($validator->fails()) {
            return ResponseFormatter::error([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 'Validation failed', 400);
        }
        if (!\Hash::check($request->old_password, $user->password)) {
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Old password is incorrect',
            ], 'Old password is incorrect', 400);
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        return ResponseFormatter::success(null,'Password has changed, please login again');
    }

    public function changeProfilePicture(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'avatar.required' => 'Avatar is required',
            'avatar.image' => 'Avatar must be an image',
            'avatar.mimes' => 'Avatar must be a JPEG, PNG, JPG, or GIF',
            'avatar.max' => 'Avatar size must not exceed 2MB',
        ]);
        if ($validator->fails()) {
            return ResponseFormatter::error([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 'Validation failed', 400);
        }
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update([
            'avatar' => $path,
        ]);
        return ResponseFormatter::success(null,'Profile picture has changed');
    }
}
