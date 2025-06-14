<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreBanner;
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
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBanner $request)
    { 
        $data =[  
        'ar'=>['description'=>$request->description_ar],
        'en'=>['description'=>$request->description_en],

        ];

       if($request->hasfile('image_right'))
        {
        $data['image_right'] = $this->uploadFile($request->image_right,'assets/images');
        
        }

        if($request->hasfile('image_left'))
        {
        $data['image_left'] = $this->uploadFile($request->image_left,'assets/images');
        
        }

        $banner = Banner::create($data);

        $banner->position = $request->position; 
        $banner->direction = $request->direction;
        
       $banner = fractal($banner, new BannerTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_banner'), $banner, 201);

    }


    public function update(StoreBanner $request,string $id)
    {
    $data =[  
        'ar'=>['description'=>$request->description_ar],
        'en'=>['description'=>$request->description_en],

        ];

       if($request->hasfile('image_right'))
        {
        $data['image_right'] = $this->uploadFile($request->image_right,'assets/images');
        
        }

        if($request->hasfile('image_left'))
        {
        $data['image_left'] = $this->uploadFile($request->image_left,'assets/images');
        
        }

        $banner = Banner::findOrFail($id);

        $banner->update($data);

        $banner->position = $request->position; 
        $banner->direction = $request->direction;
        
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
