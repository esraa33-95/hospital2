<?php

namespace App\Http\Controllers\Api\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Experience;
use App\Traits\Response;
use App\Transformers\front\ExperienceTransform;
use League\Fractal\Serializer\ArraySerializer;
use App\Http\Requests\Api\front\StoreExperience;
use App\Http\Requests\Api\front\UpdateExperience;

class ExperienceController extends Controller
{
    
    use Response;
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExperience $request,string $id)
    {
     $user = auth()->user();

    if ($request->current == 1) 
         {
            Experience::where('user_id', $user->id)
                        ->update(['current' => 0]);
        }
                
     $data = [
        'user_id'=>$user->id,
        'ar' => ['jobtitle' => $request->jobtitle_ar,
                  'organization' => $request->organization_ar],

        'en' => ['jobtitle' => $request->jobtitle_en,
                   'organization'=>$request->organization_en],

        'current'=> $request->current,         
      ];

       $experience = Experience::create($data);

       $experience = fractal($experience, new ExperienceTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.store_experience'), $experience, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $experience = Experience::findOrFail($id);

         $experience = fractal()
                 ->item($experience)
                 ->transformWith(new ExperienceTransform())
                 ->serializeWith(new ArraySerializer())
                 ->toArray();

        return  $this->responseApi('',$experience,200);
    }


    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateExperience $request,string $id)
    {
     $user = auth()->user();

     $experience = Experience::where('user_id', $user->id)->findOrFail($id);
  
      if ($request->current == 1) 
         {
            Experience::where('user_id', $user->id)
                       ->update(['current' => 0]);
        }
                
     $data = [
        'ar' => ['jobtitle' => $request->jobtitle_ar,
                  'organization' => $request->organization_ar],

        'en' => ['jobtitle' => $request->jobtitle_en,
                   'organization'=>$request->organization_en],

        'current'=> $request->current ?? 0,         
      ];
 
        $experience->update($data);

       $experience = fractal($experience, new ExperienceTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_experience'), $experience, 200);
    }
    /**
     * Remove the specified resource from storage.
     */

 public function delete(string $id)
    {
      $user = auth()->user();
      
        $experience = Experience::with('users')
                     ->where('user_id',$user->id)
                    ->firstOrFail();

        $experience->delete();
        
        return  $this->responseApi(__('messages.delete_experience'),204); 
    }
}
