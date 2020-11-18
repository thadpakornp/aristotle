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
        $users = User::withoutTrashed()->orderBy('created_at','ASC')->paginate(5, ['id', 'name', 'surname', 'phone', 'email', 'profile']);

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

    public function edited(Request $request)
    {
        $user = User::find($request->input('id'));
        if(empty($user)){
            return back()->with(['error', 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง']);
        }

        if($user->update($request->only(['name','surname']))){
            $role = config('roles.models.role')::where('id', '=', $request->input('role'))->first();
            $user->syncRoles($role);
            return redirect()->route('backend.users.index')->with(['success', 'แก้ไขข้อมูลเรียบร้อยแล้ว']);
        };

        return back()->with(['error', 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง']);
    }
}
