<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreCeritificate;
use App\Http\Requests\Api\admin\Updatecertificate;
use App\Models\Certificate;
use App\Models\User;
use App\Traits\Common;
use App\Traits\Response;
use App\Transformers\Admin\CertificateTransform;
use Illuminate\Http\Request;
use League\Fractal\Serializer\ArraySerializer;

class CertificateController extends Controller
{
    use Response;
    use Common;
   
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCeritificate $request)
    {
        $user = auth()->user();
     $data = [
         'user_id' => $user->id,
        'ar' => ['name' => $request->name_ar],
        'en' => ['name' => $request->name_en],
    ];

       $certificate = Certificate::create($data);

      $certificate = fractal($certificate, new CertificateTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_certificate'), $certificate, 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Updatecertificate $request, string $id)
    {
      $user = auth()->user();

      $certificate = Certificate::where('id', $id)
                    ->where('user_id', $user->id)
                    ->firstOrFail();

         $certificate ->update([
            'ar'=>['name'=>$request->name_ar],
            'en' => ['name' => $request->name_en],
        ]);

      $certificate = fractal($certificate, new CertificateTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_certificate'), $certificate, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete( string $id)
    {
        $certificate = Certificate::with('users')->findOrFail($id);

        if( $certificate)
        {
            return  $this->responseApi(__('messages.no_deletecerificate'),403); 
        }

        $certificate->delete();
        
        return  $this->responseApi(__('messages.delete_certificate'),204); 
    }
}
