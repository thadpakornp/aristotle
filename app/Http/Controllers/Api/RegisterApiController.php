<?php

namespace App\Http\Controllers\Api;

use App\AccessTokenApi;
use App\Helpers\ResponeReturnFromApi;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterApiController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|string|max:250',
            'password' => 'required|min:8|max:250|confirmed|string',
            'name' => 'required|string|max:250',
            'surname' => 'required|string|max:250',
            'phone' => 'string|min:10|max:10'
        ], [
            'email.required' => 'โปรดระบุอีเมลล์ผู้ใช้งาน',
            'email.email' => 'รูปแบบอีเมลล์ไม่ถูกต้อง',
            'email.unique' => 'อีเมลล์นี้มีในระบบแล้ว',
            'email.string' => 'โปรดตรวจสอบอีเมลล์อีกครั้ง',
            'email.max' => 'อีเมลล์ยาวเกินไป',
            'password.required' => 'โปรดระบุรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
            'password.max' => 'รหัสผ่านยาวเกินไป',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
            'password.string' => 'โปรดตรวจสอบรหัสผ่านอีกครั้ง',
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
        $user = User::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phone' => Crypt::encryptString($request->input('phone'))
        ]);

        if ($user) {
            $user->sendEmailVerificationNotification();
            $role = config('roles.models.role')::where('slug', '=', 'user')->first();
            $user->syncRoles($role);

            $credentials = $request->only(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                DB::rollBack();
                return ResponeReturnFromApi::responseRequestError('เกิดข้อผิดพลาดระหว่างการเชื่อมต่อ กรุณากลับหน้าล็อคอินและเข้าระบบใหม่อีกครั้ง');
            }
            $user = $request->user();
            $tokenResult = $user->createToken($request->input('email'));
            $token = $tokenResult->token;
            $token->save();
            $data = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ];

            $tokendevice = AccessTokenApi::where('uuid_device',$request->input('uuid_device'));
            if($tokendevice->count() > 0){
                //updatde
                $updatetoken = $tokendevice->first();
                $updatetoken->totken_device = $request->input('token_device');
                $updatetoken->user_id = $user->id;
                $updatetoken->save();
            } else {
                //create
                AccessTokenApi::create([
                    'user_id' => $user->id,
                    'token_device' => $request->input('token_device'),
                    'uuid_device' => $request->input('uuid_device')
                ]);
            }

            DB::commit();
            return ResponeReturnFromApi::responseRequestSuccess($data);
        }
        DB::rollBack();
        return ResponeReturnFromApi::responseRequestError('การลงทะเบียนล้มเหลว กรุณาลองใหม่อีกครั้ง');
    }
}
