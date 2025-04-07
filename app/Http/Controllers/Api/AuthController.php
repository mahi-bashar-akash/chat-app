<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{

    use AuthorizesRequests, ValidatesRequests;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        }
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->verification_code = Str::random(6);
        $user->save();
        Mail::raw("Your verification code is: {$user->verification_code}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Email Verification');
        });
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->reset_code = Str::random(6);
        $user->save();
        Mail::raw("Your reset code is: {$user->reset_code}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Password Reset Code');
        });
        return response()->json(['message' => 'Reset code sent successfully'], 200);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reset_code' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)
            ->where('reset_code', $request->reset_code)
            ->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid reset code'], 400);
        }
        $user->password = Hash::make($request->password);
        $user->reset_code = null;
        $user->save();
        return response()->json(['message' => 'Password reset successfully'], 200);
    }

    public function verification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'verification_code' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)
            ->where('verification_code', $request->verification_code)
            ->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid verification code'], 400);
        }
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();
        return response()->json(['message' => 'Email verified successfully'], 200);
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['user' => $user], 200);
    }

    public function changeDetails(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user->update($request->only('name', 'email', 'phone'));
        return response()->json(['user' => $user], 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 401);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'Password updated successfully'], 200);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function users()
    {
        $users = User::all();
        return response()->Json(['users' => $users], 200);
    }

}
