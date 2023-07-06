<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\Employee;
use Modules\RefDepartment\Entities\RefDepartment;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Employee\Http\Requests\EmployeeFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class EmployeeController extends BaseController
{
    protected $model;
    public function __construct(Employee $model)
    {
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('employee-access')){
            $this->setPageData('Employee','Employee','fas fa-th-list');
            $data['genders'] = DB::select("SELECT * FROM RefGender");  
            $data['maritalStatus'] = DB::select("SELECT * FROM RefMaritalStatus");  
            $data['educations'] = DB::select("SELECT * FROM RefEducation");  
            $data['religions'] = DB::select("SELECT * FROM RefReligion");  
            $data['roles'] = DB::select("SELECT * FROM Role");  
            $data['workplaces'] = DB::select("SELECT * FROM WorkPlace");  
            $data['departments'] = DB::select("SELECT * FROM RefDepartment");
            return view('employee::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('employee-access')){
            if($request->ajax()){
                
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';

                    if(permission('employee-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->EmployeeId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('employee-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->EmployeeId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('employee-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->EmployeeId . '" data-name="' . $value->EmployeeId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('employee-bulk-delete')){
                        $row[] = table_checkbox($value->EmployeeId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->EmployeeCode;
                    $row[] = $value->RegistrationNumber;
                    $row[] = $value->FirstName;
                    $row[] = $value->LastName;
                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                 $this->model->count_filtered(), $data);
            }else{
                $output = $this->access_blocked();
            }

            return response()->json($output);
        }
    }

    public function change_status(Request $request)
    {
        try{
            if($request->ajax()){
            if (permission('employee-edit')) {
                    $result = $this->update_change_status($request);
            if($result){
                return response()->json(['status'=>'success','message'=>'Status Changed Successfully']);
            }else{
                return response()->json(['status'=>'error','message'=>'Something went wrong!']);
            }
            }else{
                $output = $this->access_blocked();
                return response()->json($output);
            }
            }else{
                return response()->json(['status'=>'error','message'=>'Something went wrong!']);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function update_change_status(Request $request)
    {
        return $this->model->where('EmployeeId',$request->EmployeeId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('employee-view')){
            if($request->ajax()){
                if (permission('employee-view')) {
                    $Employee = DB::select("SELECT  rd.DesignationTitle,rd.Description,
                    wp.WorkPlaceName,rfd.DepartmentCode
                    FROM Employee AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId 
                    INNER JOIN RefDepartment AS rfd ON rd.RefDepartmentId = rfd.RefDepartmentId
                    WHERE rd.EmployeeId='$request->id'");
                }
            }
            return view('employee::details',compact('Employee'))->render();
        
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
         $data1 = DB::select("SELECT  rd.DesignationTitle,rd.Description,rd.RefDepartmentId,
                    wp.WorkPlaceName,rfd.DepartmentCode,rd.EmployeeId,rd.WorkPlaceId
                    FROM Employee AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId 
                    INNER JOIN RefDepartment AS rfd ON rd.RefDepartmentId = rfd.RefDepartmentId
                    WHERE rd.EmployeeId='$request->id'");

         $data2 = DB::select("SELECT * FROM WorkPlace");  
         $data3 = DB::select("SELECT * FROM RefDepartment");  
         
         return response()->json(['designation'=>$data1,'workplaces'=>$data2,'departments'=> $data3]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(EmployeeFormRequest $request)
    {
        if($request->ajax()){
            if(permission('employee-add') || permission('employee-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->EmployeeId) && !empty($request->EmployeeId)){
                        $collection = collect($request->all());
                        //track_data from base controller to merge created_by and created_at merge with request data
                        $collection = $this->track_data_org($request->EmployeeId,$collection);
                        $result = $this->model->where('EmployeeId', $request->EmployeeId)
                            ->update($collection->all());
                        $output = $this->store_message($result,$request->EmployeeId);
                        return response()->json($output);
                    }
                    else{
                        // $collection = collect($request->all());
                        //track_data from base controller to merge created_by and created_at merge with request data
                        // $collection = $this->track_data_org($request->EmployeeId,$collection);
                        //update existing index value
                        // $collection['EmployeeId'] = Str::uuid();
                        // $result = $this->model->create($collection->all());
                        // $output = $this->store_message($result,$request->EmployeeId);
                        // return response()->json($output);
                        return $request->all();
                        $file = $request->EmployeeImage;
                        $EmployeeImage = base64_encode($file); 
                        
                        $file2 = $request->EmployeeSignature;
                        $EmployeeSignature = base64_encode($file2); 

                        // DB::table('Employee')->insert([
                        //     'EmployeeId'=>Str::uuid(),
                        //     'OrgId'=>$OrgId = auth()->user()->OrgId,
                        //     'EmployeeCode'=>$request->EmployeeCode,
                        //     'RegistrationNumber'=>$request->RegistrationNumber,
                        //     'FirstName'=>$request->FirstName,
                        //     'LastName'=>$request->LastName,
                        //     'GenderId'=>$request->GenderId,
                        //     'BirthDate'=>$request->BirthDate,
                        //     'JoiningDate'=>$request->JoiningDate,
                        //     'MaritalStatusId'=>$request->MaritalStatusId,
                        //     'EducationId'=>$request->EducationId,
                        //     'Designation'=>$request->Designation,
                        //     'ReligionId'=>$request->ReligionId,
                        //     'RoleId'=>$request->RoleId,
                        //     'Email'=>$request->Email,
                        //     'Phone'=>$request->Phone,
                        //     'NationalIdNumber'=>$request->NationalIdNumber,
                        //     'EmployeeImage'=>$EmployeeImage,
                        //     'EmployeeSignature'=>$EmployeeSignature,
                        //     'Status'=>1,
                        //     'CreateUser'=>Auth::user()->id??'',
                        //     'CreateDate'=>Carbon::now()
                        // ]);
                    }

                }catch(\Exception $e){
                    // return response()->json(['status'=>'error','message'=>'Something went wrong !']);
                    return response()->json(['status'=>'error','message'=>$e->getMessage()]);
                }
                
            }else{
                $output = $this->access_blocked();
                return response()->json($output);
            }
            
        }else{
           return response()->json($this->access_blocked());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete(Request $request)
    {
        if($request->ajax()){
            if (permission('employee-delete')) {
                $result = $this->model->where('EmployeeId',$request->id)->delete();
                $output = $this->store_message($result,$request->EmployeeId);
                return response()->json($output);
            }else{
                return response()->json($this->access_blocked());
            }
        }else{
           return response()->json(['status'=>'error','message'=>'Something went wrong !']);
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('employee-bulk-delete')){
                    $result = $this->model->whereIn('RefDepartmentId',$request->ids)->delete();
                    $output = $this->bulk_delete_message($result);
                }else{
                    $output = $this->access_blocked();
                }
                return response()->json($output);
            }
            catch(\Exception $e){
                return response()->json(['status'=>'error','message'=>'Something went wrong !']);
            }
        }else{
            return response()->json($this->access_blocked());
        }
    }
}
