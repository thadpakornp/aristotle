<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use jeremykenedy\LaravelRoles\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withoutTrashed()->orderByDesc('updated_at')->paginate(5, ['id', 'name', 'surname', 'phone', 'email', 'profile'])->except(auth()->user()->id);

        return view('users', compact('users'));
    }

    public function show($id)
    {
        $user = User::find($id);
        $rules = Role::withoutTrashed()->get();

        return view('users_edit', compact('user','rules'));
    }

    public function deleted(Request $request)
    {
        $user = User::destroy($request->input('id'));
        if($user){
            return response()->json(['success'],200);
        }
        return response()->json(['error'],404);
    }
}
