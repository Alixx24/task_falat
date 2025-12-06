<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //users list
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'mobile')->get();

        return response()->json([
            'status' => true,
            'message' => 'User list fetched successfully',
            'data' => $users
        ]);
    }

    //search

public function search(Request $request)
{
    $search = $request->search;

    $users = User::query()
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
        })
        ->orderBy('id', 'desc')
        ->get(); //Or paginate(10)

    return response()->json([
        'status' => true,
        'data' => $users
    ]);
}






    // show user
    public function show($id)
    {
        $user = User::select('id', 'name', 'email', 'mobile')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    //update user
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        //update with validated data
        $user->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'User updated successfully',
            'data'    => $user
        ]);
    }
}
