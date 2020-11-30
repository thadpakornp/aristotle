<?php

namespace App\Http\Resources;

use App\Course;
use App\StoreFile;
use App\StoreFollow;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class HomeStoreResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "storesid" => $this->storesid,
            "storesname" => $this->storesname,
            "coursetotal" => Course::withoutTrashed()->where('stores_id',$this->storesid)->count(),
            "storesdescription" => $this->storesdescription,
            "storesadrress" => $this->storesadrress,
            "storesdistrict" => $this->storesdistrict,
            "storesamphur" => $this->storesamphur,
            "storesprovince" => $this->storesprovince,
            "storeszipcode" => $this->storeszipcode,
            "storesphone" => Crypt::decryptString($this->storesphone),
            "storesemail" => Crypt::decryptString($this->storesemail),
            "storesline" => $this->storesline == null ? "null" : Crypt::decryptString($this->storesline),
            "storesglat" => $this->storesglat == null ? "null" : $this->storesglat,
            "storesglng" => $this->storesglng == null ? "null" : $this->storesglng,
            "storesimagename" => $this->storesimagename == null ? "null" : asset('media/'.$this->storesimagename),
            "storesimage" => StoreFile::where('stores_id',$this->storesid)->count() > 0 ? StoreImage::collection(StoreFile::where('stores_id',$this->storesid)->get()) : "null",
            "storesfollow" => auth()->check() ? StoreFollow::where('user_id', auth()->user()->id)->where('store_id',$this->storesid)->count() > 0 ? true : false : false,
            "storescourse" => Course::withoutTrashed()->where('stores_id',$this->storesid)->count() > 0 ? StoreCourseResource::collection(Course::withoutTrashed()->where('stores_id',$this->storesid)->get()) : "null",
            "storefollowtotal" => StoreFollow::where('store_id',$this->storesid)->count()
        ];
    }

    protected function getStoresphoneAttribute($storesphone)
    {
        if ($storesphone == null) {
            return null;
        }
        try {
            return Crypt::decryptString($storesphone);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function getStoresemailAttribute($storesemail)
    {
        if ($storesemail == null) {
            return null;
        }
        try {
            return Crypt::decryptString($storesemail);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function getStoreslineAttribute($storesline)
    {
        if ($storesline == null) {
            return null;
        }
        try {
            return Crypt::decryptString($storesline);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
