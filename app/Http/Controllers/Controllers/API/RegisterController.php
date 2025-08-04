<?php

namespace App\Http\Controllers\Controllers\API;

use App\Http\Controllers\Controllers\API\BaseController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class RegisterController extends BaseController
{
    function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),

            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success, $user, 'User registered successfully.');
    }



    public function login(Request $request): JsonResponse
    {
        // 1. Valider les champs
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        // 2. Tentative d’authentification
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['Unauthorised'], 401);
        }
    }


    public function logout(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated.',
        ], 401);
    }

    $user->currentAccessToken()->delete();

    return response()->json([
        'success' => true,
        'message' => 'User logged out successfully.',
    ]);
}
}
