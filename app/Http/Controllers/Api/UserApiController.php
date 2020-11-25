<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponeReturnFromApi;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserApiController extends Controller
{
    public function getUser()
    {
        $data = [
            'user' => UserResource::make(User::find(auth()->user()->id))
        ];
        return ResponeReturnFromApi::responseRequestSuccess($data);
    }

    public function changepassword(Request $request)
    {
        $v = Validator::make($request->all(), [
            'oldpassword' => 'required|string',
            'password' => 'required|min:8|max:250|confirmed|string',
        ], [
            'oldpassword.required' => 'โปรดระบุรหัสผ่านเดิม',
            'oldpassword.string' => 'โปรดตรวจสอบรหัสผ่านเดิมอีกครั้ง',
            'password.required' => 'โปรดระบุรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
            'password.max' => 'รหัสผ่านยาวเกินไป',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
            'password.string' => 'โปรดตรวจสอบรหัสผ่านอีกครั้ง',
        ]);

        if ($v->fails()) return ResponeReturnFromApi::responseRequestError($v->errors()->getMessages());
        DB::beginTransaction();
        $user = User::find(auth()->user()->id);
        try {
            if (!password_verify($request->input('oldpassword'), $user->password)) return ResponeReturnFromApi::responseRequestError('รหัสผ่านเดิมไม่ถูกต้อง');
            $user->password = Hash::make($request->input('password'));
            if (!$user->save()) return ResponeReturnFromApi::responseRequestError('ไม่สามารถเปลี่ยนรหัสผ่านได้');
            DB::commit();
            return ResponeReturnFromApi::responseRequestSuccess('ดำเนินการเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponeReturnFromApi::responseRequestError($e->getMessage());
        }
    }

    public function changeprofile(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'surname' => 'required|string|max:250',
            'phone' => 'string|min:10|max:10'
        ], [
            'name.required' => 'โปรดระบุชื่อ',
            'name.string' => 'โปรดตรวจสอบชื่ออีกครั้ง',
            'name.max' => 'ชื่อของท่านยาวเกินไป',
            'surname.required' => 'โปรดระบุนามสกุล',
            'surname.string' => 'โปรดตรวจสอบนามสกุลของท่าน',
            'surname.max' => 'นามสกุลขอท่านยาวเกินไป',
            'phone.string' => 'โปรดตรวจสอบเบอร์มือถืออีกครั้ง',
            'phone.min' => 'เบอร์มือถือต้องประกอบด้วย 10 หลัก',
            'phone.max' => 'เบอร์มือถือต้องประกอบด้วย 10 หลัก'
        ]);

        if ($validate->fails()) return ResponeReturnFromApi::responseRequestError($validate->errors()->getMessages());
        DB::beginTransaction();
        $user = User::find(auth()->user()->id);
        try {
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->phone = $request->input('phone');


            if($request->file('profile')){
                $newIamgeName = Str::random(8).date('YmdHis').'.'.$request->file('profile')->getClientOriginalExtension();
                $request->file('profile')->move(public_path('assets/images/icon/'), $newIamgeName);

                $user->profile = $newIamgeName;
            }

            if(!$user->save()){
                DB::rollBack();
                return ResponeReturnFromApi::responseRequestError('เกิดข้อผิดพลาด');
            }
            DB::commit();
            return ResponeReturnFromApi::responseRequestSuccess('ดำเนินการเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return ResponeReturnFromApi::responseRequestError($e->getMessage());
        }
    }
}
