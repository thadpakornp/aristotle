<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::withoutTrashed()->Limit30days()->orderByDesc('created_at')->skip($request->input('start'))->take($request->input('limit'))->get();
        $html = '';
        foreach ($posts as $post){
            $html .= '<div class="au-message__item">
                                        <div class="au-message__item-inner">
                                            <div class="au-message__item-text">
                                                <div class="avatar-wrap">
                                                    <div class="avatar">
                                                    ';
            if($post->users->profile == null){
                $html .= '<img src="'.asset('images/icon/avatar-01.jpg').'"
                                                             alt="" width="100px" height="100px">';
            } else {
                $html .= '<img src="'.asset('images/icon/'.$post->users->profile).'"
                                                             alt="" width="100px" height="100px">';
            }
                                             $html .= '</div>
                                                </div>
                                                <div class="text">
                                                    <h5 class="name">'.$post->users->name.' '.$post->users->surname.'</h5>
                                                    <div class="au-message__item-time">
                                                        <span>'.$post->created_at.'</span>
                                                    </div>
                                                    <p>'.$post->description.'</p>
                                                        ';
                                $files = PostFile::withoutTrashed()->where('post_id',$post->id);
                                if($files->count() > 0){
                                    $html .= '<div class="row">';
                                    foreach ($files->get() as $file){
                                        if($file->type_file == 'pdf'){
                                            $html .= '<div class="col-3"><i class="fa fa-file-pdf text-danger"></i> <span>'.$file->old_name.'</span></div>';
                                        } else {
                                            $html .= '<div class="col-3"><img src="' . asset("media/".$file->name) . '" class="img-fluid" alt="Responsive image" max-height="200px"></div>';
                                        }
                                    }
                                    $html .= '</div>';
                                }
                                                    $html .= '
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
        }
        return response()->json($html,200);
    }

    public function create()
    {
        if(\request()->session()->has('upload_id'))
        {
            foreach (\request()->session()->get('upload_id') as $upload_id)
            {
                $file = PostFile::find($upload_id);
                File::delete(public_path('assets/media/' . $file->name));
            }
            PostFile::destroy('id',\request()->session()->get('upload_id'));
            \request()->session()->forget('upload_id');
        }
        return view('create_post');
    }

    public function post(Request $request)
    {
        if ($request->file('file')) {
            $image = $request->file('file');
            $imageName = $image->getClientOriginalName();
            $newIamgeName = Str::random(8).date('YmdHis').'.'.$request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(public_path('assets/media'), $newIamgeName);

            $id = PostFile::create([
                'name' => $newIamgeName,
                'old_name' => $imageName,
                'type_file' => $request->file('file')->getClientOriginalExtension()
            ]);
            $request->session()->push('upload_id', $id->id);

            return response()->json(['html' => 'ok'], 200);
        }
    }

    public function deletedfile(Request $request)
    {
        $file = PostFile::where('old_name',$request->input('filename'))->first();
        File::delete(public_path('assets/media/' . $file->name));
        $c = count($request->session()->get('upload_id'));
        if($c > 0){
            $request->session()->pull('upload_id',$file->id);
        } else {
            $request->session()->forget('upload_id');
        }
        if($file->delete()){
            return response()->json(['html' => 'ok'], 200);
        }
        return response()->json(['html' => 'ok'], 401);
    }

    public function stroed(Request $request)
    {
        if($request->input('description') == null && !$request->file('file')){
            return back()->with(['error', 'ไม่สามารถบันทึกข้อมูลว่างได้']);
        }

        $data['description'] = $request->input('description');
        $data['g_lat'] = $request->input('g_location_lat') == '13.744674' ? null : $request->input('g_location_lat');
        $data['g_lng'] = $request->input('g_location_long') == '100.5633683' ? null : $request->input('g_location_long');
        $data['tag'] = '0';
        $data['user_id'] = auth()->user()->id;

        $post = Post::create($data);
        if($post){
            if($request->session()->has('upload_id')){
                PostFile::whereIn('id',$request->session()->get('upload_id'))->update(['post_id' => $post->id]);
                $request->session()->forget('upload_id');
            }
            return redirect()->route('backend.home');
        }

        return back()->with(['error', 'ไม่สามารถบันทึกได้']);
    }
}
