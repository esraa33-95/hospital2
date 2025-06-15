<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Traits\Response;
use App\Transformers\front\CertificateTransform;
use App\Transformers\front\UserTransform;
use League\Fractal\Serializer\ArraySerializer;
use App\Http\Requests\Api\front\StoreCeritificate;
use App\Http\Requests\Api\front\Updatecertificate;
use App\Traits\Common;
use App\Http\Requests\Api\front\uploadimageRequest;

class CertificateController extends Controller
{
    use Response;
    use Common;

    //upload pdf
    public function uploadfile(uploadimageRequest $request,string $id)
    {
        $request->validated();

        $user = User::findOrFail($id);

        if ($request->hasFile('image'))
        {
             $user->clearMediaCollection('files');

             $user->addMedia($request->file('image'))
                 ->toMediaCollection('files');
        }

        return $this->responseApi(__('messages.uploadpdf'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCeritificate $request, string $id)
    {
     $user = auth()->user();
                
     $data = [
        'user_id'=>$user->id,
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
      ];

       $certificate = Certificate::create($data);

       $certificate = fractal($certificate, new CertificateTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_certificate'), $certificate, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {     
        $certificate = Certificate::findOrFail($id);

         $certificate = fractal()
                 ->item($certificate)
                 ->transformWith(new CertificateTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$certificate,200);
    }

  
    /**
     * Update the specified resource in storage.
     */
   public function updatecertificate(Updatecertificate $request, string $id)
    {
        $user = auth()->user();

         $data =[
            'ar'=>['name'=>$request->name_ar],
            'en' => ['name' => $request->name_en],
              ];

         $certificate = Certificate::findOrFail($id);

        $certificate->update($data);

      $certificate = fractal($certificate, new CertificateTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_certificate'), $certificate, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $certificate = Certificate::with('users')->findOrFail($id);
    
        if($certificate)
        {
            return  $this->responseApi(__('messages.no_deletecerificate'),403); 
        }

        $certificate->delete();
        
        return  $this->responseApi(__('messages.delete_certificate'),204); 
    }
}
