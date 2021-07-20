<?php

namespace App\Http\Controllers;

use App\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


/**
 * Class AuthController
 *
 * @package Tuandm\Laravue\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            return response()->json(new JsonResponse(Auth::user()), Response::HTTP_OK)->header('Authorization', $token);
        }

        return response()->json(new JsonResponse([], 'login_error'), Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json((new JsonResponse())->success([]), Response::HTTP_OK);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * @return mixed
     */
    private function guard()
    {
        return Auth::guard();
    }
}
