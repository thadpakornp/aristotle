<?php

namespace App\Http\Controllers;

use App\Amphur;
use App\Course;
use App\District;
use App\Province;
use App\Store;
use App\StoreFile;
use App\Zipcode;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        if(auth()->user()->hasRole('admin')){
            $stores = Store::withoutTrashed()->orderByDesc('created_at')->paginate(10,['id','name','description','address','district','amphur','province','zipcode','email','phone','line','created_at','status']);
        } else {
            $stores = Store::withoutTrashed()->where('user_id',auth()->user()->id)->orderByDesc('created_at')->paginate(10,['id','name','description','address','district','amphur','province','zipcode','email','phone','line','created_at','status']);
        }

        return view('store.index',compact('stores'));
    }

    public function show($id)
    {
        if (\request()->session()->has('upload_id_channel')) {
            foreach (\request()->session()->get('upload_id_channel') as $upload_id) {
                $file = StoreFile::find($upload_id);
                File::delete(public_path('assets/media/' . $file->name));
            }
            StoreFile::destroy('id', \request()->session()->get('upload_id_channel'));
            \request()->session()->forget('upload_id_channel');
        }
        $store = Store::find($id);
        $provinces = Province::all(['PROVINCE_ID', 'PROVINCE_NAME']);

        $p = Province::where('PROVINCE_NAME',$store->province)->first();
        $amphurs = Amphur::where('PROVINCE_ID',$p->PROVINCE_ID)->get(['AMPHUR_ID','AMPHUR_NAME']);

        $a = Amphur::where('AMPHUR_NAME',$store->amphur)->first();
        $districts = District::where('AMPHUR_ID',$a->AMPHUR_ID)->where('PROVINCE_ID',$p->PROVINCE_ID)->get(['DISTRICT_CODE','DISTRICT_NAME']);

        $store_files = StoreFile::where('stores_id',$id);

        $courses = Course::withoutTrashed()->where('stores_id',$store->id)->get();

        return view('store.show', compact('store','provinces', 'amphurs','districts','store_files','courses'));
    }


    public function create_channel()
    {
        if (\request()->session()->has('upload_id_channel')) {
            foreach (\request()->session()->get('upload_id_channel') as $upload_id) {
                $file = StoreFile::find($upload_id);
                File::delete(public_path('assets/media/' . $file->name));
            }
            StoreFile::destroy('id', \request()->session()->get('upload_id_channel'));
            \request()->session()->forget('upload_id_channel');
        }
        $provinces = Province::all(['PROVINCE_ID', 'PROVINCE_NAME']);
        return view('store.create_channel', compact('provinces'));
    }

    public function getAddress(Request $request)
    {
        $fvalue = explode(',', $request->input('fvalue'));
        $fvalue = $fvalue[0];
        if ($request->input('find') == 'a') {
            $result = Amphur::where('PROVINCE_ID', $fvalue)->where('AMPHUR_NAME', 'NOT LIKE', '*%')->orderBy('AMPHUR_NAME', 'ASC')->get(['AMPHUR_ID', 'AMPHUR_NAME']);
            if (count($result) > 0) {
                $temp = '<option value="">:::::&nbsp;เลือกอำเภอ&nbsp;:::::</option>';
                foreach ($result as $read) {
                    $temp .= '<option value="' . $read['AMPHUR_ID'] . ',' . $read['AMPHUR_NAME'] . '">' . $read['AMPHUR_NAME'] . '</option>';
                }
            } else {
                $temp = '<option value="">:::&nbsp;ไม่มีข้อมูล&nbsp;:::</option>';
            }
        } else if ($request->input('find') == 't') {
            $result = District::where('AMPHUR_ID', $fvalue)->where('DISTRICT_NAME', 'NOT LIKE', '*%')->orderBy('DISTRICT_NAME', 'ASC')->get(['DISTRICT_CODE', 'DISTRICT_NAME']);
            if (count($result) > 0) {
                $temp = '<option value="" selected="selected">:::::&nbsp;เลือกตำบล&nbsp;:::::</option>';
                foreach ($result as $read) {
                    $temp .= '<option value="' . $read['DISTRICT_CODE'] . ',' . $read['DISTRICT_NAME'] . '">' . $read['DISTRICT_NAME'] . '</option>';
                }
            } else {
                $temp = '<option value="" selected="selected">:::&nbsp;ไม่มีข้อมูล&nbsp;:::</option>';
            }
        } else if ($request->input('find') == 'z') {
            $result = Zipcode::where('DISTRICT_CODE', $fvalue)->first(['ZIPCODE']);
            $temp = $result['ZIPCODE'];
        } else {
            if ($request->input('find') != 'z') {
                $temp = '<option value="">:::::&nbsp;เลือก&nbsp;:::::</option>';
            }
        }
        return $temp;
    }

    public function post(Request $request)
    {
        if ($request->file('file')) {
            $image = $request->file('file');
            $newIamgeName = $image->getClientOriginalName();
            $request->file('file')->move(public_path('assets/media'), $newIamgeName);

            $id = StoreFile::create([
                'name' => $newIamgeName,
            ]);
            $request->session()->push('upload_id_channel', $id->id);

            return response()->json(['html' => 'ok'], 200);
        }
    }

    public function deletedfile(Request $request)
    {
        $file = StoreFile::where('name', $request->input('filename'))->first();
        File::delete(public_path('assets/media/' . $file->name));
        $c = count($request->session()->get('upload_id_channel'));
        if ($c > 0) {
            $request->session()->pull('upload_id_channel', $file->id);
        } else {
            $request->session()->forget('upload_id_channel');
        }
        if ($file->delete()) {
            return response()->json(['html' => 'ok'], 200);
        }
        return response()->json(['html' => 'ok'], 401);
    }

    public function edit(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['required'],
            'address' => ['required'],
            'province' => ['required'],
            'country' => ['required'],
            'district' => ['required'],
            'code' => ['required'],
            'email' => ['required','email'],
            'phone' => ['required','min:10','max:10']
        ],[
            'name.required' => 'ชื่อไม่สามารถเป็นค่าว่างได้',
            'description.required' => 'รายละเอียดไม่สามารถเป็นค่าว่างได้',
            'address.required' => 'ที่อยู่ไม่สามารถเป็นค่าว่างได้',
            'province.required' => 'กรุณาเลือกจังหวัด',
            'country.required' => 'กรุณาเลือกอำเภอ/เขต',
            'district.required' => 'กรุณาเลือกตำบล/แขวง',
            'code.required' => 'รหัสไปรษณีย์ไม่สามารถเป็นค่าว่างได้',
            'email.required' => 'อีเมลล์ติดต่อกลับไม่สามารถเป็นค่าว่างได้',
            'email.email' => 'รูปแบบอีเมลล์ไม่ถูกต้อง',
            'phone.required' => 'เบอร์ติดต่อกลับไม่สามารถเป็นค่าว่างได้',
            'phone.min' => 'เบอร์มือถือต้องมี 10 หลัก',
            'phone.max' => 'เบอร์มือถือต้องมี 10 หลัก',
        ]);

        if($v->fails())
        {
            return back()->withErrors($v->errors()->getMessages());
        }

        $province = explode(',',$request->input('province'));
        $country = explode(',',$request->input('country'));
        $district = explode(',',$request->input('district'));

        $data['name'] = $request->input('name');
        $data['description'] = $request->input('description');
        $data['address'] = $request->input('address');
        $data['district'] = $district[1];
        $data['amphur'] = $country[1];
        $data['province'] = $province[1];
        $data['zipcode'] = $request->input('code');
        $data['phone'] = Crypt::encryptString($request->input('phone'));
        $data['email'] = Crypt::encryptString($request->input('email'));
        $data['line'] = $request->input('line') ? Crypt::encryptString($request->input('line')) : null;
        $data['g_lat'] = $request->input('g_location_lat') == '13.744674' ? null : $request->input('g_location_lat');
        $data['g_lng'] = $request->input('g_location_long') == '100.5633683' ? null : $request->input('g_location_long');

        $store = Store::find($request->input('id'))->update($data);
        if($store){
            if($request->session()->has('upload_id_channel')){
                StoreFile::whereIn('id',$request->session()->get('upload_id_channel'))->update(['stores_id' => $request->input('id')]);
                $request->session()->forget('upload_id_channel');
            }
            return back()->with('success','บันทึกการแก้ไขแล้ว');
        }
        return back()->withErrors('เกิดข้อผิดพลาด ไม่สามารถสร้างชาแนลได้');
    }

    public function stroed(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => ['required'],
            'description' => ['required'],
            'address' => ['required'],
            'province' => ['required'],
            'country' => ['required'],
            'district' => ['required'],
            'code' => ['required'],
            'email' => ['required','email'],
            'phone' => ['required','min:10','max:10']
        ],[
            'name.required' => 'ชื่อไม่สามารถเป็นค่าว่างได้',
            'description.required' => 'รายละเอียดไม่สามารถเป็นค่าว่างได้',
            'address.required' => 'ที่อยู่ไม่สามารถเป็นค่าว่างได้',
            'province.required' => 'กรุณาเลือกจังหวัด',
            'country.required' => 'กรุณาเลือกอำเภอ/เขต',
            'district.required' => 'กรุณาเลือกตำบล/แขวง',
            'code.required' => 'รหัสไปรษณีย์ไม่สามารถเป็นค่าว่างได้',
            'email.required' => 'อีเมลล์ติดต่อกลับไม่สามารถเป็นค่าว่างได้',
            'email.email' => 'รูปแบบอีเมลล์ไม่ถูกต้อง',
            'phone.required' => 'เบอร์ติดต่อกลับไม่สามารถเป็นค่าว่างได้',
            'phone.min' => 'เบอร์มือถือต้องมี 10 หลัก',
            'phone.max' => 'เบอร์มือถือต้องมี 10 หลัก',
        ]);

        if($v->fails())
        {
            return back()->withErrors($v->errors()->getMessages());
        }

        $province = explode(',',$request->input('province'));
        $country = explode(',',$request->input('country'));
        $district = explode(',',$request->input('district'));

        $data['name'] = $request->input('name');
        $data['description'] = $request->input('description');
        $data['address'] = $request->input('address');
        $data['district'] = $district[1];
        $data['amphur'] = $country[1];
        $data['province'] = $province[1];
        $data['zipcode'] = $request->input('code');
        $data['phone'] = Crypt::encryptString($request->input('phone'));
        $data['email'] = Crypt::encryptString($request->input('email'));
        $data['line'] = $request->input('line') ? Crypt::encryptString($request->input('line')) : null;
        $data['g_lat'] = $request->input('g_location_lat') == '13.744674' ? null : $request->input('g_location_lat');
        $data['g_lng'] = $request->input('g_location_long') == '100.5633683' ? null : $request->input('g_location_long');
        $data['user_id'] = auth()->user()->id;
        $data['status'] = auth()->user()->hasRole('admin') ? '1' : '0';

        $store = Store::create($data);
        if($store){
            if($request->session()->has('upload_id_channel')){
                StoreFile::whereIn('id',$request->session()->get('upload_id_channel'))->update(['stores_id' => $store->id]);
                $request->session()->forget('upload_id_channel');
            }
            if(auth()->user()->hasRole('user')){
                $role = config('roles.models.role')::where('slug', '=', 'store')->first();
                auth()->user()->syncRoles($role);
            }
            return redirect()->route('backend.store.index');
        }
        return back()->withErrors('เกิดข้อผิดพลาด ไม่สามารถสร้างชาแนลได้');
    }

    public function filedeleted(Request $request)
    {
        $s = StoreFile::where('name',$request->input('name'))->first();
        if($s->delete()){
            File::delete(public_path('assets/media/' . $request->input('name')));
            return response()->json('ok',200);
        }
        return response()->json('ok',404);
    }

    public function deleted(Request $request)
    {
        $s = Store::find($request->input('id'))->delete();
        if($s){
            return response()->json('ok',200);
        }
        return response()->json('ok',404);
    }

    public function approve(Request $request)
    {
        $s = Store::find($request->input('id'));
        $s->status = '1';
        if($s->save()){
            $url = route('backend.store.index');
            return response()->json($url,200);
        }
        return response()->json('ok',404);
    }

    public function noapprove(Request $request)
    {
        $s = Store::find($request->input('id'))->delete();
        if($s){
            $url = route('backend.store.index');
            return response()->json($url,200);
        }
        return response()->json('ok',404);
    }
}
