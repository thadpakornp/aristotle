<?php

namespace App\Http\Resources;

use App\CourseFile;
use App\CourseLike;
use App\Store;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "courseid" => $this->id,
            "coursenameth" => $this->name_th,
            "coursenameen" => $this->name_en,
            "courseprofessor" => $this->professor == null ? "null" : $this->professor,
            "coursefullcost" => number_format($this->full_cost),
            "coursediscountcost" => number_format($this->discount_cost),
            "coursecover" => $this->cover == null ? "null" : $this->cover,
            "coursenumcourse" => $this->num_course,
            "coursenumhour" => $this->num_hour,
            "coursenum" => $this->num,
            "coursetype" => $this->type_course,
            "coursedescription" => $this->description,
            "coursefile" => CourseFile::where('course_id',$this->id)->count() > 0 ? CourseImage::collection(CourseFile::where('course_id',$this->id)->get()) : "null",
            "courselike" => CourseLike::where('course_id',$this->id)->count(),
            "courselocation" => Store::where('id',$this->stores_id)->first(['g_lat','g_lng']),
            "cousseuserlike" => auth()->check() ? CourseLike::where('user_id',auth()->user()->id)->where('course_id',$this->id)->count() > 0 ? true : false : false
        ];
    }
}
