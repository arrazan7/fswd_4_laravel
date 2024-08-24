<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthAPIController extends Controller
{
    // POST [username, password]
    public function login(Request $request)
    {
        // Validation
        $validator = Validator::make($request -> all(),[
            'username' => 'required|string',
            'password' => 'required'
        ]);

        // Return validation errors on failure
        if ($validator -> fails()) {
            $errors = $validator -> errors() -> messages();
            return response() -> json([
                'message' => 'The given data was invalid.',
                'errors' => $errors,
            ], 422);
        }

        // user data check by name
        $user = User::where('username', $request -> username) -> first();
        if (!empty($user)) {
            // user data exists

            // Password Check
            if (Hash::check($request -> password, $user -> password)) {
                // Password matched

                // Auth Token
                $token = $user -> createToken("mytoken") -> plainTextToken;
                return response() -> json([
                    'status' => true,
                    'message' => 'User logged in',
                    'token' => $token,
                    'data' => []
                ]);
            }
            else {
                // Password not matched
                return response() -> json([
                    'status' => false,
                    'message' => 'Invalid password',
                    'data' => []
                ], 422);
            }
        }
        else {
            // user data not exists
            return response() -> json([
                'status' => false,
                'message' => "Username doesn't match with records",
                'data' => []
            ], 422);
        }
    }

    // GET [Auth: Token]
    public function logout()
    {
        if (auth() -> user() -> tokens() -> delete()) {
            return response() -> json([
                'status' => true,
                'message' => 'User logged out',
                'data' => []
            ]);
        }
    }

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
