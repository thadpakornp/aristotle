<?php

namespace App\Http\Controllers\Api;

use App\CourseLike;
use App\Helpers\ResponeReturnFromApi;
use App\Http\Controllers\Controller;
use App\Post;
use App\PostFile;
use App\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostApiController extends Controller
{
    public function newpost(Request $request)
    {
        if($request->input('description') == null && !$request->file('file') && $request->input('g_location_lat') == '13.744674' && $request->input('g_location_long') == '100.5633683') return response()->json(ResponeReturnFromApi::responseRequestError('ไม่สามารถบันทึกข้อมูลว่างได้'));;

        DB::beginTransaction();
        try {
            $data['description'] = $request->input('description');
            $data['g_lat'] = $request->input('g_location_lat') == '13.744674' ? null : $request->input('g_location_lat');
            $data['g_lng'] = $request->input('g_location_long') == '100.5633683' ? null : $request->input('g_location_long');
            $data['tag'] = '0';
            $data['user_id'] = auth()->user()->id;

            $post = Post::create($data);
            if($post){
                if ($request->file('file')) {
                    foreach ($request->file('file') as $image ){
                        $imageName = $image->getClientOriginalName();
                        $newIamgeName = Str::random(8).date('YmdHis').'.'.$request->file('file')->getClientOriginalExtension();
                        $request->file('file')->move(public_path('assets/media'), $newIamgeName);

                        $postfile = PostFile::create([
                            'post_id' => $post->id,
                            'name' => $newIamgeName,
                            'old_name' => $imageName,
                            'type_file' => $request->file('file')->getClientOriginalExtension()
                        ]);

                        if(!$postfile) {
                            DB::rollBack();
                            return response()->json(ResponeReturnFromApi::responseRequestError('เกิดข้อพิดพลาด'));
                        }
                    }
                }
                DB::commit();
                return response()->json(ResponeReturnFromApi::responseRequestSuccess('บันทึกรายการเรียบร้อยแล้ว'));
            }
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }

    public function deleted(Request $request)
    {
        $post = Post::find($request->input('postid'));
        if(empty($post)) return response()->json(ResponeReturnFromApi::responseRequestError('เกิดข้อพิดพลาด'));

        DB::beginTransaction();
        try {
            $post->delete();
            DB::commit();
            return response()->json(ResponeReturnFromApi::responseRequestSuccess('บันทึกรายการเรียบร้อยแล้ว'));
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }

    public function likeandunlike(Request $request)
    {
        try {
            $likeandunlike = PostLike::where('post_id', $request->input('id'))->where('user_id', auth()->user()->id);
            if ($likeandunlike->count() == 0) {
                PostLike::create([
                    'post_id' => $request->input('id'),
                    'user_id' => auth()->user()->id
                ]);
            } else {
                $likeandunlike->first()->delete();
            }
            return response()->json(ResponeReturnFromApi::responseRequestSuccess('บันทึกรายการเรียบร้อยแล้ว'));
        } catch (\Exception $e) {
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }
}
