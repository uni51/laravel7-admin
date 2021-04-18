<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCrateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate();

        return UserResource::collection($users);
    }

    public function show($id)
    {
        $user = User::find($id);

        return new UserResource($user);
    }

    public function store(UserCrateRequest $request)
    {
        $user = User::create($request->only('first_name', 'last_name', 'email', 'role_id') + [
            'password' => Hash::make(1234),
        ]);

        return response(new UserResource($user), Response::HTTP_CREATED); // 201
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);

        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED); // 202
    }

    public function destroy($id)
    {
        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT); //204
    }

    public function user()
    {
        return \Auth::user();
    }

    public function updateInfo(Request $request)
    {
        $user = \Auth::user();

        $user->update($request->only('first_name', 'last_name', 'email'));

        return response(new UserResource($user), Response::HTTP_ACCEPTED); // 202
    }

    public function updatePassword(Request $request)
    {
        $user = \Auth::user();

        $user->update([
           'password' => Hash::make($request->input('password'))
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED); // 202
    }
}
