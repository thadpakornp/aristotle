<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseFile;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function create($id)
    {
        if (\request()->session()->has('upload_id_course')) {
            foreach (\request()->session()->get('upload_id_course') as $upload_id) {
                $file = CourseFile::find($upload_id);
                File::delete(public_path('assets/media/' . $file->name));
            }
            CourseFile::destroy('id', \request()->session()->get('upload_id_course'));
            \request()->session()->forget('upload_id_course');
        }
        return view('store.course_create', compact('id'));
    }

    public function post(Request $request)
    {
        if ($request->file('file')) {
            $image = $request->file('file');
            $newIamgeName = $image->getClientOriginalName();
            $request->file('file')->move(public_path('assets/media'), $newIamgeName);

            $id = CourseFile::create([
                'name' => $newIamgeName,
            ]);
            $request->session()->push('upload_id_course', $id->id);

            return response()->json(['html' => 'ok'], 200);
        }
    }

    public function deletedfile(Request $request)
    {
        $file = CourseFile::where('name', $request->input('filename'))->first();
        File::delete(public_path('assets/media/' . $file->name));
        $c = count($request->session()->get('upload_id_course'));
        if ($c > 0) {
            $request->session()->pull('upload_id_course', $file->id);
        } else {
            $request->session()->forget('upload_id_course');
        }
        if ($file->delete()) {
            return response()->json(['html' => 'ok'], 200);
        }
        return response()->json(['html' => 'ok'], 401);
    }

    public function filedeleted(Request $request)
    {
        $s = CourseFile::where('name',$request->input('name'))->first();
        if($s->delete()){
            File::delete(public_path('assets/media/' . $request->input('name')));
            return response()->json('ok',200);
        }
        return response()->json('ok',404);
    }

    public function coverdeleted(Request $request)
    {
        $s = Course::where('id',$request->input('name'))->first();
        $s->cover = null;
        if($s->save()){
            File::delete(public_path('assets/media/' . $request->input('name')));
            return response()->json('ok',200);
        }
        return response()->json('ok',404);
    }

    public function deleted(Request $request)
    {
        $c = Course::find($request->input('id'));
        $id = $c->stores_id;

        if($c->delete()){
            $url = route('backend.store.show',$id);
            return response()->json($url,200);
        }
        return response()->json('ok',404);
    }

    public function edit($id)
    {
        $course = Course::find($id);

        $course_file = CourseFile::where('course_id',$course->id);

        return view('store.course_edit', compact('course','course_file'));
    }

    public function edited(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name_th' => ['required'],
            'name_en' => ['required'],
            'professor' => ['required'],
            'full_cost' => ['required'],
            'num_course' => ['required'],
            'num_hour' => ['required'],
            'num' => ['required'],
            'type_course' => ['required'],
            'description' => ['required','max:250'],
        ],[
            'name_th.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'name_en.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'professor.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'full_cost.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'num_course.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'num_hour.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'num.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'type_course.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'description.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'description.max' => 'รายละเอียดยาวเกินไป',
        ]);

        if($v->fails())
        {
            return back()->withErrors($v->errors()->getMessages());
        }

        $data['name_th'] = $request->input('name_th');
        $data['name_en'] = $request->input('name_en');
        $data['professor'] = $request->input('professor');
        $data['full_cost'] = $request->input('full_cost');
        $data['discount_cost'] = $request->input('discount_cost') ? $request->input('discount_cost') : null;
        $data['num_course'] = $request->input('num_course');
        $data['num_hour'] = $request->input('num_hour');
        $data['num'] = $request->input('num');
        $data['type_course'] = $request->input('type_course');
        $data['description'] = $request->input('description');
        $data['user_id'] = auth()->user()->id;

        if($request->hasFile('cover')){
            $newIamgeName = Str::random(8).date('YmdHis').'.'.$request->file('cover')->getClientOriginalExtension();
            $request->file('cover')->move(public_path('assets/media'), $newIamgeName);

            $data['cover'] = $newIamgeName;
        }

        $c = Course::find($request->input('id'));
        if($c->update($data)){
            if($request->session()->has('upload_id_course')){
                CourseFile::whereIn('id',$request->session()->get('upload_id_course'))->update(['course_id' => $request->input('id')]);
                $request->session()->forget('upload_id_course');
            }
            return redirect()->route('backend.store.show',$c->stores_id);
        }
        return back()->withErrors('กรุณาลองใหม่อีกครั้ง');
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'stores_id' => ['required'],
            'name_th' => ['required'],
            'name_en' => ['required'],
            'professor' => ['required'],
            'full_cost' => ['required'],
            'num_course' => ['required'],
            'num_hour' => ['required'],
            'num' => ['required'],
            'type_course' => ['required'],
            'description' => ['required','max:250'],
        ],[
            'stores_id.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'name_th.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'name_en.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'professor.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'full_cost.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'num_course.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'num_hour.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'num.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'type_course.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'description.required' => 'ไม่สามารถเป็นค่าว่างได้',
            'description.max' => 'รายละเอียดยาวเกินไป',
        ]);

        if($v->fails())
        {
            return back()->withErrors($v->errors()->getMessages());
        }

        $data['stores_id'] = $request->input('stores_id');
        $data['name_th'] = $request->input('name_th');
        $data['name_en'] = $request->input('name_en');
        $data['professor'] = $request->input('professor');
        $data['full_cost'] = $request->input('full_cost');
        $data['discount_cost'] = $request->input('discount_cost') ? $request->input('discount_cost') : null;
        $data['num_course'] = $request->input('num_course');
        $data['num_hour'] = $request->input('num_hour');
        $data['num'] = $request->input('num');
        $data['type_course'] = $request->input('type_course');
        $data['description'] = $request->input('description');
        $data['user_id'] = auth()->user()->id;

        if($request->hasFile('cover')){
            $newIamgeName = Str::random(8).date('YmdHis').'.'.$request->file('cover')->getClientOriginalExtension();
            $request->file('cover')->move(public_path('assets/media'), $newIamgeName);

            $data['cover'] = $newIamgeName;
        }

        $c = Course::create($data);
        if($c){
            if($request->session()->has('upload_id_course')){
                CourseFile::whereIn('id',$request->session()->get('upload_id_course'))->update(['course_id' => $c->id]);
                $request->session()->forget('upload_id_course');
            }
            return redirect()->route('backend.store.show',$request->input('stores_id'));
        }
        return back()->withErrors('กรุณาลองใหม่อีกครั้ง');
    }
}
