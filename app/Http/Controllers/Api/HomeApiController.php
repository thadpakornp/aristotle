<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Helpers\ResponeReturnFromApi;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\HomeStoreResource;
use App\Http\Resources\PostResource;
use App\Post;
use App\PostLike;
use App\Store;
use App\StoreFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeApiController extends Controller
{
    public function indexposts(Request $request)
    {
        try {
            $post = $this->getPosts($request->input('start'), $request->input('limit'));

            $data = [
                'posts' => PostResource::collection($post)
            ];

            return response()->json(ResponeReturnFromApi::responseRequestSuccess($data));
        } catch (\Exception $e) {
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }

    }

    public function indexcourses(Request $request)
    {
        try {
            $course = $this->getCourses(0,10);

            $data = [
                'courses' => CourseResource::collection($course),
            ];

            return response()->json(ResponeReturnFromApi::responseRequestSuccess($data));
        } catch (\Exception $e) {
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }

    }


    public function indexcoursesall(Request $request)
    {
        try {
            $course = $this->getCourses($request->input('start'), $request->input('limit'));

            $data = [
                'courses' => CourseResource::collection($course),
            ];

            return response()->json(ResponeReturnFromApi::responseRequestSuccess($data));
        } catch (\Exception $e) {
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }

    }

    public function indexchannel(Request $request)
    {
        try {
            $store = $this->getStores(0,10);

            $data = [
                'stores' => HomeStoreResource::collection($store),
            ];

            return response()->json(ResponeReturnFromApi::responseRequestSuccess($data));
        } catch (\Exception $e) {
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }

    public function indexchannelall(Request $request)
    {
        try {
            $store = $this->getStores($request->input('start'), $request->input('limit'));

            $data = [
                'stores' => HomeStoreResource::collection($store),
            ];

            return response()->json(ResponeReturnFromApi::responseRequestSuccess($data));
        } catch (\Exception $e) {
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }

    public function loadmore(Request $request)
    {
        try {
            $post = $this->getPosts($request->input('start'), $request->input('limit'));
            $data = [
                'posts' => PostResource::collection($post)
            ];

            return response()->json(ResponeReturnFromApi::responseRequestSuccess($data));
        } catch (\Exception $e) {
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }

    private function getStores($start, $limit)
    {
        return Store::withoutTrashed()->leftJoin('store_follow', 'stores.id', '=', 'store_follow.store_id')->leftJoin('stores_image', 'stores.id', '=', 'stores_image.stores_id')->select('stores.id as storesid', 'stores.name as storesname', 'stores.description as storesdescription', 'stores.address as storesadrress', 'stores.district as storesdistrict', 'stores.amphur as storesamphur', 'stores.province as storesprovince', 'stores.zipcode as storeszipcode', 'stores.phone as storesphone', 'stores.email as storesemail', 'stores.line as storesline', 'stores.g_lat as storesglat', 'stores.g_lng as storesglng', 'stores_image.name as storesimagename', DB::raw('count(store_follow.store_id) as storefollowtotal'))->where('stores.status', '1')->skip($start)->take($limit)->groupBy('stores.id')->orderByDesc('storefollowtotal')->get();
    }

    private function getCourses($start, $limit)
    {
        return Course::withoutTrashed()->leftJoin('course_like', 'course.id', '=', 'course_like.course_id')->select('course.id as id', 'course.name_th as name_th', 'course.name_en as name_en', 'course.professor as professor', 'course.full_cost as full_cost', 'course.discount_cost as discount_cost', 'course.cover as cover', 'course.num_course as num_course', 'course.num_hour as num_hour', 'course.num as num', 'course.type_course as type_course', 'course.description as description','course.stores_id as storesid', DB::raw('count(course_like.course_id) as courseliketotal'))->skip($start)->take($limit)->groupBy('course.id')->get();
    }

    private function getPosts($start, $limit)
    {
        return Post::withoutTrashed()->leftJoin('post_comment', 'post.id', '=', 'post_comment.post_id')->leftJoin('post_like', 'post.id', '=', 'post_like.post_id')->select('post.id as postid', 'post.user_id as userpost', 'post.description as postdescription', 'post.tag as posttag', 'post.g_lat as postglat', 'post.g_lng as postglng', 'post.created_at as postcreatedat', DB::raw('count(post_comment.post_id) as postcommenttotal'), DB::raw('count(post_like.post_id) as postliketotal'))->Limit30days()->skip($start)->take($limit)->groupBy('post.id')->orderByDesc('post.created_at')->get();
    }

    public function likeandinlike(Request $request)
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

    public function followandunfollow(Request $request)
    {
        try {
            $followandunfollow = StoreFollow::where('store_id', $request->input('id'))->where('user_id', auth()->user()->id);
            if ($followandunfollow->count() == 0) {
                StoreFollow::create([
                    'store_id' => $request->input('id'),
                    'user_id' => auth()->user()->id
                ]);
            } else {
                $followandunfollow->first()->delete();
            }

            return response()->json(ResponeReturnFromApi::responseRequestSuccess('บันทึกรายการเรียบร้อยแล้ว'));
        }catch (\Exception $e){
            return response()->json(ResponeReturnFromApi::responseRequestError($e->getMessage()));
        }
    }
}
