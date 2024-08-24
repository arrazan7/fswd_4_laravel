<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // POST [username, fullname, email, password]
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request -> all(),[
            'username' => 'required|string|unique:users',
            'fullname' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        // Return validation errors on failure
        if ($validator -> fails()) {
            $errors = $validator -> errors() -> messages();
            return response() -> json([
                'message' => 'The given data was invalid.',
                'errors' => $errors
            ], 422);
        }

        // Save Account
        $user = User::create([
            'fullname' => $request -> fullname,
            'username' => $request -> username,
            'email' => $request -> email,
            'password' => bcrypt($request -> password),
            'role' => 'public',
            'phone' => '',
            'photo' => '',
        ]);

        return new UserResource($user, true, 'User registered successfully');
    }

    /**
     * Display the specified resource.
     */
    // GET [Auth: Token]
    public function show(Request $request)
    {
        $user = $request->user(); // Retrieve authenticated user using Sanctum

        return new UserResource($user, true, 'Profile Information');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $username = User::findOrFail($id) -> username;
        $email = User::findOrFail($id) -> email;

        // Validation
        if (($username == $request -> username) && ($email != $request -> email)) {
            $validator = Validator::make($request -> all(),[
                'fullname' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'phone' => 'nullable|string'
            ]);
        }
        elseif (($username != $request -> username) && ($email == $request -> email)) {
            $validator = Validator::make($request -> all(),[
                'username' => 'required|string|unique:users',
                'fullname' => 'required|string',
                'phone' => 'nullable|string'
            ]);
        }
        elseif (($username != $request -> username) && ($email != $request -> email)) {
            $validator = Validator::make($request -> all(),[
                'username' => 'required|string|unique:users',
                'fullname' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'phone' => 'nullable|string'
            ]);
        }
        else {
            $validator = Validator::make($request -> all(),[
                'fullname' => 'required|string',
                'phone' => 'nullable|string'
            ]);
        }

        // Return validation errors on failure
        if ($validator -> fails()) {
            $errors = $validator -> errors() -> messages();
            return response() -> json([
                'message' => 'The given data was invalid.',
                'errors' => $errors
            ], 422);
        }

        $phone = $request -> phone;
        if (!$phone) {
            $phone = '';
        }

        $user = User::findOrFail($id);
        $user->update([
            'fullname' => $request -> fullname,
            'username' => $request -> username,
            'email' => $request -> email,
            'phone' => $phone
        ]);

        return new UserResource($user, true, 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return new UserResource($user, true, 'User deleted successfully');
        }
    }
}
