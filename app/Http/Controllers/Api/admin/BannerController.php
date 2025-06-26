<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreBanner;
use App\Http\Requests\Api\admin\UpdateBanner;
use App\Models\Banner;
use App\Traits\Common;
use App\Traits\Response;
use App\Transformers\admin\BannerTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class BannerController extends Controller
{
    use Response;
    use Common;
    
    public function store(StoreBanner $request)
{ 
    $data = [  
        'ar' => ['description' => $request->description_ar],
        'en' => ['description' => $request->description_en],
        'position' => $request->position,
    ];

    $banner = Banner::create($data);

    if ($request->hasFile('image_ar')) {
        $banner->addMedia($request->file('image_ar'))
               ->withCustomProperties(['locale' => 'ar'])
               ->toMediaCollection('files');
    }

    if ($request->hasFile('image_en')) {
        $banner->addMedia($request->file('image_en'))
               ->withCustomProperties(['locale' => 'en'])
               ->toMediaCollection('files');
    }

    $banner = fractal($banner, new BannerTransform())
                ->serializeWith(new ArraySerializer())
                ->toArray();

    return $this->responseApi(__('messages.store_banner'), $banner, 201);
}


//update
    public function update(UpdateBanner $request,string $id)
    {
   $data =[  
        'ar'=>['description'=>$request->description_ar],
        'en'=>['description'=>$request->description_en],

        'position'=>$request->position,
        ];

        $banner = Banner::findOrFail($id);
        $banner->update($data);

         if ($request->hasFile('image_ar')) 
         {

        $banner->clearMediaCollection('files');
       
        $banner->addMedia($request->file('image_ar'))
               ->withCustomProperties(['locale' => 'ar'])
               ->toMediaCollection('files');
    }

    if ($request->hasFile('image_en'))
     {
        $banner->clearMediaCollection('files');
        
        $banner->addMedia($request->file('image_en'))
               ->withCustomProperties(['locale' => 'en'])
               ->toMediaCollection('files');
    }

       $banner = fractal($banner, new BannerTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();


    return $this->responseApi(__('messages.update_banner'), $banner, 200);
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $banner = Banner::findOrFail($id);

        $banner->delete();
        
        return  $this->responseApi(__('messages.delete_banner'),204);
    }
}
