<?php

namespace Modules\Patient\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Patient\Entities\Patient;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Patient\Http\Requests\PatientFormRequest;
use DB;

class PatientController extends BaseController
{
    public function __construct(Patient $model)
    {
        $this->model = $model;
    }

    public function index()
    {  
        if(permission('patient-access')){
            $this->setPageData('Patient','patient','fas fa-th-list');
            return view('patient::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('patient-access')){
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

                    // if(permission('patient-edit')){
                    //     $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->PatientId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    // }
                    if(permission('patient-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->PatientId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    

                    $row = [];

                    if(permission('patient-bulk-delete')){
                        $row[] = table_checkbox($value->PatientId);
                    }
                    $row[] = $no;
                    $row[] = $value->PatientId;
                    $row[] = $value->RegistrationId;
                    $row[] = $value->GivenName.' '.$value->FamilyName;
                    $row[] = $value->IdNumber;
                    $row[] = $value->CellNumber;
                    //$row[] = permission('patient-access') ? change_status($value->PatientId,$value->Status) : STATUS_LABEL[$value->Status];
                    //$row[] = permission('patient-edit') ? change_status($value->PatientId,$value->Status) : STATUS_LABEL[$value->Status];
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

    public function store_or_update_data(PatientFormRequest $request)
    {
        if($request->ajax()){
            if(permission('patient-add') || permission('patient-edit')){
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id,$collection);
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output = $this->store_message($result,$request->update_id);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
           return response()->json($this->access_blocked());
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('patient-edit')){
                $data = $this->model->findOrFail($request->id);
                $output = $this->data_message($data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('patient-delete')){
                $result = $this->model->find($request->id)->delete();
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('patient-bulk-delete')){
                $result = $this->model->destroy($request->ids);
                $output = $this->bulk_delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function change_status(Request $request)
    {
        if($request->ajax()){
            if (permission('patient-edit')) {
                $result = $this->model->find($request->id)->update(['Status'=>$request->status]);
                $output = $result ? ['status'=>'success','message'=>'Status has been changed successfully']
                : ['status'=>'error','message'=>'Failed to change status'];
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function show(Request $request)
    {
        if($request->ajax()){
            if (permission('patient-view')) {

            $patientDetails=Patient::with('Gender')->where('PatientId','=',$request->id)->get();

            $prescriptionCreation= DB::select("SELECT TOP 1 MAX(PrescriptionId) AS PrescriptionId, CAST(CreateDate AS date) as CreateDate
            FROM PrescriptionCreation WHERE PatientId = '$request->id' AND CAST(CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM PrescriptionCreation WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY CreateDate ORDER BY CreateDate");

        $Complaints= DB::select("SELECT MAX(PC.ChiefComplain) AS ChiefComplain, MAX(PC.CCDurationValue) AS CCDurationValue, MAX(PC.OtherCC) AS OtherCC, MAX(RD.DurationInEnglish) AS DurationInEnglish, CAST(PC.CreateDate AS date) as CreateDate
            FROM MDataPatientCCDetails as PC
            INNER JOIN RefDuration as RD on RD.DurationId = PC.DurationId
            WHERE PatientId = '$request->id' AND CAST(PC.CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM MDataPatientCCDetails WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY PC.CreateDate ORDER BY PC.CreateDate");

        $HeightWeight= DB::select("SELECT TOP 1 MAX(Height) AS Height, MAX(Weight) AS Weight, MAX(BMI) AS BMI, MAX(BMIStatus) AS BMIStatus, CAST(CreateDate AS date) as CreateDate
            FROM MDataHeightWeight WHERE PatientId = '$request->id' AND CAST(CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM MDataHeightWeight WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY CreateDate ORDER BY CreateDate");
        
        $BP= DB::select("SELECT TOP 1 MAX(BPSystolic1) AS BPSystolic1, MAX(BPDiastolic1) AS BPDiastolic1, MAX(BPSystolic2) AS BPSystolic2, MAX(BPDiastolic2) AS BPDiastolic2, MAX(HeartRate) AS HeartRate, MAX(CurrentTemparature) AS CurrentTemparature, CAST(CreateDate AS date) as CreateDate
            FROM MDataBP WHERE PatientId = '$request->id' AND CAST(CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM MDataBP WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY CreateDate ORDER BY CreateDate");
        
        $GlucoseHb = DB::select("SELECT TOP 1 MAX(RBG) AS RBG, MAX(FBG) AS FBG, MAX(Hemoglobin) AS Hemoglobin, MAX(HrsFromLastEat) AS HrsFromLastEat, CAST(CreateDate AS date) as CreateDate
            FROM MDataGlucoseHb WHERE PatientId = '$request->id' AND CAST(CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM MDataGlucoseHb WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY CreateDate ORDER BY CreateDate");

        $ProvisionalDx = DB::select("SELECT MAX(ProvisionalDiagnosis) AS ProvisionalDiagnosis, MAX(DiagnosisStatus) AS DiagnosisStatus, MAX(OtherProvisionalDiagnosis) AS OtherProvisionalDiagnosis, CAST(CreateDate AS date) as CreateDate
        FROM MDataProvisionalDiagnosis WHERE PatientId = '$request->id' AND CAST(CreateDate AS date) 
        = CAST(
            (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
            FROM MDataProvisionalDiagnosis WHERE PatientId = '$request->id'
            GROUP BY CAST(CreateDate AS date)
            ORDER BY MaxCreateDate DESC)
            AS date) GROUP BY CreateDate ORDER BY CreateDate");


       $Investigation= DB::select("SELECT  MAX(RI.Investigation) AS Investigation, MAX(I.OtherInvestigation) AS OtherInvestigation, CAST(I.CreateDate AS date) as CreateDate
            FROM MDataInvestigation as I
            INNER JOIN RefLabInvestigation as RI on RI.RefLabInvestigationId = I.InvestigationId
            WHERE PatientId = '$request->id' AND CAST(I.CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM MDataInvestigation WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY I.CreateDate ORDER BY I.CreateDate");


        $Treatment= DB::select("SELECT MAX(T.Frequency) AS Frequency, MAX(T.DrugDurationValue) AS DrugDurationValue,MAX(T.OtherDrug) AS OtherDrug,MAX(T.SpecialInstruction) AS SpecialInstruction, MAX(Dr.DrugCode) AS DrugCode, MAX(Dr.Description) AS Description, MAX(Dr.DrugDose) AS DrugDose, MAX(Ins.InstructionInBangla) AS InstructionInBangla, CAST(T.CreateDate AS date) as CreateDate
        FROM MDataTreatmentSuggestion as T
        INNER JOIN RefDrug as Dr on Dr.DrugId = T.DrugId
        INNER JOIN RefInstruction as Ins on Ins.RefInstructionId = T.RefInstructionId
        WHERE PatientId = '$request->id' AND CAST(T.CreateDate AS date) 
        = CAST(
            (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
            FROM MDataTreatmentSuggestion WHERE PatientId = '$request->id'
            GROUP BY CAST(CreateDate AS date)
            ORDER BY MaxCreateDate DESC)
            AS date) GROUP BY T.CreateDate ORDER BY T.CreateDate");


        $Advice= DB::select("SELECT MAX(RA.AdviceInBangla) AS AdviceInBangla, MAX(RA.AdviceInEnglish) AS AdviceInEnglish, CAST(A.CreateDate AS date) as CreateDate
            FROM MDataAdvice as A
            INNER JOIN RefAdvice as RA on RA.AdviceId = A.AdviceId
            WHERE PatientId = '$request->id' AND CAST(A.CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM MDataAdvice WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY A.CreateDate ORDER BY A.CreateDate");


        $PatientReferral= DB::select("SELECT MAX(RR.Description) AS Description, MAX(HC.HealthCenterName) AS HealthCenterName, CAST(PR.CreateDate AS date) as CreateDate
            FROM MDataPatientReferral as PR
            INNER JOIN RefReferral as RR on RR.RId = PR.RId
            INNER JOIN HealthCenter as HC on HC.HealthCenterId = PR.HealthCenterId
            WHERE PatientId = '$request->id' AND CAST(PR.CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM MDataPatientReferral WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY PR.CreateDate ORDER BY PR.CreateDate");

        

        $FollowUpDate= DB::select("SELECT MAX(FD. FollowUpDate) AS FollowUpDate, MAX(FD.Comment) AS Comment, CAST(FD.CreateDate AS date) as CreateDate
            FROM MDataFollowUpDate as FD
            WHERE PatientId = '$request->id' AND CAST(FD.CreateDate AS date) 
            = CAST(
                (SELECT TOP 1 MAX(CreateDate) AS MaxCreateDate
                FROM MDataPatientReferral WHERE PatientId = '$request->id'
                GROUP BY CAST(CreateDate AS date)
                ORDER BY MaxCreateDate DESC)
                AS date) GROUP BY FD.CreateDate ORDER BY FD.CreateDate");

                
            }
        }

        return view('patient::details',compact('patientDetails','prescriptionCreation','Complaints','HeightWeight','BP','GlucoseHb','ProvisionalDx','Investigation','Treatment','Advice','PatientReferral','FollowUpDate'))->render();
    }


}
