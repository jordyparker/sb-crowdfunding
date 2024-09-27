<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login to the user's account with the credentials provided
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        $user = User::query()
            ->when($request->username_type === 'email', function ($q) use ($request) {
                $q->where('email', $request->username);
            })
            ->when($request->username_type === 'phone', function ($q) use ($request) {
                $q->where('phone', $request->username)
                    ->where('dail_code', $request->dail_code);
            })->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('ACCESS TOKEN');

        $response = [
            'code' => 'LOGIN_SUCCESS',
            'token_type' => 'Bearer',
            'status' => true,
            'message' => 'Successfully logged in.',
            'auth_token' => $token->plainTextToken,
            'user' => UserResource::make($user)
        ];

        return response($response);
    }

    /**
     * Create a new user account
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function register(SignupRequest $request)
    {
        $user = User::query()
            ->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => generateUsername(
                    new User,
                    explode('@', $request->email)[0]
                ),
                'phone' => $request->phone,
                'dail_code' => $request->dail_code,
                'password' => $request->password
            ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store("users/{$user->username}");

            $user->update(['avatar' => $path]);
        }

        event(new Registered($user));

        return response([
            'code' => 'REGISTER_SUCCESS',
            'message' => 'Successfully sign up.',
            'user' => UserResource::make($user)
        ], 201);
    }

    /**
     * Get information of the authenticated user
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function getAuthenticatedUser(Request $request)
    {
        return response([
            'message' => 'User data successfully retrieved',
            'code' => 'USER_DATA_RETRIEVED',
            'user' => UserResource::make($request->user('api'))
        ], 200);
    }

    /**
     * Logout user from his account
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user('api');

        $user->tokens()->delete();

        return response([
            'message' => 'You have been successfully logged out!',
            'CODE' => 'USER_LOGOUT'
        ], 200);
    }
}
