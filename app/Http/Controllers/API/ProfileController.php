<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{use ResponseTrait;

    public function __construct(private AuthRepository $auth)
    {
        $this->auth = $auth;
    }
    public function show()
    {
        try {
           return  Auth::guard()->user();

        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
    public function logout(): JsonResponse
    {
        try {
            Auth::guard()->user()->token()->revoke();
            Auth::guard()->user()->token()->delete();
            return $this->responseSuccess('', 'User Logged Out Successfully!');


        } catch (Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
    public function update(ProfileRequest $request)
    {
        try {
            $user = Auth::guard()->user();

            $user->profile->fill($request->all())->save();

            return $this->responseSuccess($request, 'User Profile updated successfully.');
        } catch (\Exception $exception) {
            return $this->responseError([], $exception->getMessage());
        }
    }
}
