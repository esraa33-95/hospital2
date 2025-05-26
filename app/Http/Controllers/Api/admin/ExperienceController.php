<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\StoreExperience;
use App\Http\Requests\Api\admin\UpdateExperience;
use App\Transformers\Admin\ExperienceTransform;
use League\Fractal\Serializer\ArraySerializer;
use App\Models\Experience;
use App\Traits\Response;
use App\Traits\Common;
use Illuminate\Http\Request;

class ExperienceController extends Controller
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
   
    $query = Experience::query();

      if ($search)
    {
        $query->where('jobtitle', 'like', '%' . $search . '%');
    }

    $total = $query->count();

    $experience = $query->skip($skip ?? 0)->take($take ?? $total)->get();

     $experience = fractal()
                   ->collection($experience)
                   ->transformWith(new ExperienceTransform())
                   ->serializeWith(new ArraySerializer())
                   ->toArray();

    return $this->responseApi('', $experience, 200, ['count' =>$total]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExperience $request)
    {
        $data = $request->validated();

       $experience = Experience::create($data);

       $experience = fractal($experience,new ExperienceTransform())
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

      return $this->responseApi('', $experience, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExperience $request, string $id)
    {
       $experience = Experience::findOrFail($id);

       $data = $request->validated();

      $experience->update($data);

     $experience = fractal($experience, new ExperienceTransform() )
                    ->serializeWith(new ArraySerializer())
                    ->toArray();

    return $this->responseApi(__('messages.update_experience'), $experience, 201);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $experience = Experience::with('users')->findOrFail($id);

        if( $experience)
        {
            return  $this->responseApi(__('messages.Nodelete_experience'),403); 
        }

        $experience->delete();
        
        return  $this->responseApi(__('messages.delete_experience'),204);  
    }
}
