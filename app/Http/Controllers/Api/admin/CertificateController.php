<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreCeritificate;
use App\Http\Requests\Api\admin\Updatecertificate;
use App\Models\Certificate;
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
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $search = $request->input('search');
    $take = $request->input('take'); 
    $skip = $request->input('skip');  
    $locale = $request->query('lang', app()->getLocale());

    $query = Certificate::query();

      if ($search)
    {
        $query->whereTranslationLike('name', '%' . $search . '%', $locale);
    }

    $total = $query->count();

    $certificates = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $certificates =  fractal()
                   ->collection($certificates)
                   ->transformWith(new CertificateTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $certificates, 200, ['count' =>$total]);
}
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCeritificate $request)
    {
        $data = [
            'user_id' => auth()->id(),
            'ar'=>['name'=>$request->name_ar],
            'en' => ['name' => $request->name_en],
        ];

       $certificate = Certificate::create($data);

      $certificate = fractal($certificate, new CertificateTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_certificate'), $certificate, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
     $certificate = Certificate::findOrFail($id);

      $certificate = fractal()
                    ->item($certificate)
                    ->transformWith(new CertificateTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi('', $certificate, 201);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Updatecertificate $request, string $id)
    {
          $certificate = Certificate::findOrFail($id);

         $certificate ->update([
            'ar'=>['name'=>$request->name_ar],
            'en' => ['name' => $request->name_en],
        ]);

      $certificate = fractal($certificate, new CertificateTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_certificate'), $certificate, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( string $id)
    {
        $certificate = Certificate::findOrFail($id);

        if($certificate->where('user_type',2))
        {
            return  $this->responseApi(__('messages.no_deletecerificate'),403); 
        }

        $certificate->delete();
        
        return  $this->responseApi(__('messages.delete_certificate'),204); 
    }
}
