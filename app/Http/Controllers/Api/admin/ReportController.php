<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\admin\UpdateReport;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use App\Models\User;
use App\Traits\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportController extends Controller
{
    use Response;

    public function store(Request $request)
    {
        $data = $request->validate([
            'report_name'=>'required|string|max:255',
            'symptoms'=>'required|string',
            'traitment'=>'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

         $user = User::findOrFail($request->user_id);

         $data['doctor_name'] = $user->name;

        $report = Report::create($data);

      return response()->json(['report created successfully',new ReportResource($report),201]);
    }


 //show   
public function show(string $id)
{
   $report = Report::findOrFail($id);

   return response()->json(['',$report,200]);

}

//index
  public function index(Request $request)
{
    $search = $request->input('search');
    $take = $request->input('take'); 
    $skip = $request->input('skip');  
 
    $query = Report::query();
    
    if ($search)
     {
        $query->where(function ($q) use ($search) {
            $q->where('report_name', 'like', '%' . $search . '%');
        });
    }


    $total = $query->count(); 

    $reports = $query->skip($skip ?? 0)->take($take ?? $total)->get();
    

    return $this->responseApi('',ReportResource::collection($reports),200,['count' => $total]);
}


//update
public function update(UpdateReport  $request, string $id)
    {
        $data = $request->validated();

        $report = Report::findOrFail($id);
    
        $report->update($data);

        return  $this->responseApi(__('update report successfully'),$report,200);
    }


     public function destroy(string $id)
    {
        $report = Report::findOrFail($id);

        $report->delete();
        
        return  $this->responseApi(__('report delete successfully'),204); 
    }


}
