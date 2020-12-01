<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\CommentFile;
use App\Helpers\ResponeReturnFromApi;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Jobs\CommentSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommentApiController extends Controller
{
    public function getComment($id)
    {
        try {
            $comment = Comment::withoutTrashed()->where('post_id', $id)->get();
            $data = [
                'comments' => CommentResource::collection($comment)
            ];
            return response()->json(ResponeReturnFromApi::responseRequestSuccess($data));
        } catch (\Exception $e) {
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }

    public function comment(Request $request)
    {
        try {
            DB::beginTransaction();
            $comment = Comment::create([
                'user_id' => auth()->user()->id,
                'post_id' => $request->input('postid'),
                'description' => $request->input('description')
            ]);

            if ($comment) {
                if ($request->hasFile('file')) {
                    $image = $request->file('file');
                    $imageName = $image->getClientOriginalName();
                    $newIamgeName = Str::random(8) . date('YmdHis') . '.' . $request->file('file')->getClientOriginalExtension();
                    $request->file('file')->move(public_path('assets/media'), $newIamgeName);

                    $filecomment = CommentFile::create([
                        'comment_id' => $comment->id,
                        'name' => $newIamgeName,
                        'old_name' => $imageName
                    ]);
                    if (!$filecomment) {
                        DB::rollBack();
                        return response()->json(ResponeReturnFromApi::responseRequestError('เกิดข้อผิดพลาด'));
                    }
                }
                DB::commit();

                CommentSave::dispatch(auth()->user()->id,$request->input('postid'),'0');

                return response()->json(ResponeReturnFromApi::responseRequestSuccess('บันทึกเรียบร้อยแล้ว'));
            }
            DB::rollBack();
            return response()->json(ResponeReturnFromApi::responseRequestError('เกิดข้อผิดพลาด'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }

    public function deleted(Request $request)
    {
        $comment = Comment::find($request->input('commentid'));
        if (empty($comment)) return response()->json(ResponeReturnFromApi::responseRequestError('เกิดข้อผิดพลาด'));

        if (!auth()->user()->hasRole('admin')) {
            if ($comment->user_id != auth()->user()->id) return response()->json(ResponeReturnFromApi::responseRequestError('เกิดข้อผิดพลาด'));
        }

        DB::beginTransaction();
        try {
            $comment->delete();
            DB::commit();
            return response()->json(ResponeReturnFromApi::responseRequestSuccess('บันทึกเรียบร้อยแล้ว'));
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }
}
