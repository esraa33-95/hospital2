<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreExperience;
use App\Http\Requests\Api\admin\UpdateExperience;
use App\Transformers\Admin\ExperienceTransform;
use League\Fractal\Serializer\ArraySerializer;
use App\Models\Experience;
use App\Models\User;
use App\Traits\Response;
use App\Traits\Common;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    use Response;
    use Common;
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExperience $request, string $id)
    {
       $data = $request->validated();

      $user = auth()->user();

      $data['user_id'] = $user->id;         

       $experience = Experience::create($data);

       $experience = fractal($experience,new ExperienceTransform())
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

       return $this->responseApi(__('messages.store_experience'), $experience, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExperience $request, string $id)
    {
    $user = auth()->user();

    $experience = Experience::where('id', $id)
                            ->where('user_id', $user->id)
                            ->firstOrFail();

       $data = $request->validated();

      $experience->update($data);

     $experience = fractal($experience, new ExperienceTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_experience'), $experience, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $experience = Experience::with('users')->findOrFail($id);
        
        if ($experience) 
        {
            return  $this->responseApi(__('messages.Nodelete_experience'),403); 
        }

        $experience->delete();
        
        return  $this->responseApi(__('messages.delete_experience'),204);  
    }
}
