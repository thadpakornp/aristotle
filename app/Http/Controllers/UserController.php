<?php

namespace App\Http\Controllers;

use App\Mail\PasswordAfterRegisterByUser;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use jeremykenedy\LaravelRoles\Models\Role;
use Illuminate\Support\Str;

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

    public function profile(){
        $user = User::find(auth()->user()->id);

        return view('profile_edit', compact('user'));
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

    public function profileedited(Request $request)
    {

        $user = User::find($request->input('id'));
        if(empty($user)){
            return back()->with(['error', 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง']);
        }
        $data['name'] = $request->input('name');
        $data['surname'] = $request->input('surname');
        $data['phone'] = $request->input('phone') ? Crypt::encryptString($request->input('phone')) : null;

        if($request->file('profile')){
            $newIamgeName = Str::random(8).date('YmdHis').'.'.$request->file('profile')->getClientOriginalExtension();
            $request->file('profile')->move(public_path('assets/images/icon/'), $newIamgeName);

            $data['profile'] = $newIamgeName;
        }
        if(!$user->update($data)){
            return back()->with(['error', 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง']);
        }
        return back()->with('success', 'แก้ไขเรียบร้อยแล้ว');
    }

    public function password()
    {
        return view('password');
    }

    public function passwordchange(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if (!password_verify($request->input('old_password'), $user->password)) {
            return back()->with(['error', 'รหัสผ่านเดิมไม่ถูกต้อง']);
        }
        $user->password = Hash::make($request->input('password'));
        if (!$user->save()) {
            return back()->with(['error', 'ไม่สามารถเปลี่ยนรหัสผ่านได้']);
        }
        return back()->with(['success', 'เปลี่ยนรหัสผ่านแล้ว']);
    }

    public function create()
    {
        $rules = Role::withoutTrashed()->get();

        return view('users_create', compact('rules'));
    }

    public function created(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'surname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['string', 'max:10', 'min:10'],
        ]);

        if($v->fails()) {
            return back()->withErrors($v->getMessageBag());
        };

        $password = Str::random(8);
        $role = config('roles.models.role')::where('id', '=', $request->input('role'))->first();

        $data['name'] = $request->input('name');
        $data['surname'] = $request->input('surname');
        $data['email'] = $request->input('email');
        $data['password'] = Hash::make($password);
        $data['phone'] = $request->input('phone') ? Crypt::encryptString($request->input('phone')) : null;
        $data['email_verified_at'] = Carbon::now();

        $user = User::forceCreate($data)->syncRoles($role);
        if($user){
            Mail::to($request->input('email'))->send(new PasswordAfterRegisterByUser($password));
            return redirect()->route('backend.users.index')->with(['success', 'บันทึกเรียบร้อยแล้ว']);
        }

        return back()->with(['error', 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง']);
    }
}
