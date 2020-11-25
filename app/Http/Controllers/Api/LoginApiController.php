<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponeReturnFromApi;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginApiController extends Controller
{

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        ], [
            'email.required' => 'โปรดระบุอีเมลล์ผู้ใช้งาน',
            'email.email' => 'รูปแบบอีเมลล์ไม่ถูกต้อง',
            'password.required' => 'โปรดระบุรหัสผ่าน'
        ]);

        if ($validate->fails()) return ResponeReturnFromApi::responseRequestError($validate->errors()->getMessages());

        $user = User::where('email', $request->input('email'))->first();

        if (!empty($user) && Hash::check($request->input('password'), $user->password)) {
            $tokenResult = $user->createToken($request->input('email'));
            $data = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ];

            return ResponeReturnFromApi::responseRequestSuccess($data);
        } else {
            return ResponeReturnFromApi::responseRequestError("ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง");
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user()->token()->revoke();
        if($user){
            return ResponeReturnFromApi::responseRequestSuccess('ออกจากระบบเรียบร้อยแล้ว');
        }
        return ResponeReturnFromApi::responseRequestError("ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง");
    }
}
