<?php

namespace Modules\Prescription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Prescription\Entities\Prescription;
use Modules\Patient\Entities\Patient;
use Modules\Base\Http\Controllers\BaseController;
use DB;

class PrescriptionController extends BaseController
{
    public function __construct(Prescription $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('prescription-access')){
            $this->setPageData('Prescription','prescription','fas fa-th-list');
            return view('prescription::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
        //return view('prescription::index');
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('prescription-access')){
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

                    // if(permission('prescription-edit')){
                    //     $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->prescriptionId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    // }
                    if(permission('prescription-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->PrescriptionCreationId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    
                    $row = [];

                    if(permission('prescription-bulk-delete')){
                        $row[] = table_checkbox($value->PrescriptionCreationId);
                    }
                    $row[] = $no;
                    $row[] = $value->PrescriptionId;
                    $row[] = $value->patient->RegistrationId;
                    $row[] = $value->CreateDate;
                    //$row[] = permission('prescription-access') ? change_status($value->prescriptionId,$value->Status) : STATUS_LABEL[$value->Status];
                    //$row[] = permission('prescription-edit') ? change_status($value->prescriptionId,$value->Status) : STATUS_LABEL[$value->Status];
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

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('prescription::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {

        if($request->ajax()){
            if (permission('prescription-view')) {
            
            $prescriptionDetails=Prescription::where('PrescriptionCreationId','=',$request->id)->first();

            $create_date_time = $prescriptionDetails->CreateDate;

            $create_date = date('Y-m-d',strtotime($create_date_time));

            $patient_id= $prescriptionDetails->PatientId;

            $patientDetails=Patient::with('Gender')->where('PatientId','=',$patient_id)->get();

            $prescriptionCreation= DB::select("SELECT PPC.PrescriptionId AS PrescriptionId,E.FirstName AS FirstName,E.LastName AS LastName,E.Designation AS Designation,E.EmployeeSignature AS EmployeeSignature, PPC.CreateDate
            FROM PrescriptionCreation as PPC 
            INNER JOIN Employee as E on E.EmployeeId = PPC.EmployeeId
            WHERE PPC.PatientId = '$patient_id' AND PPC.CreateDate ='$create_date_time'");

            
                
            $Complaints= DB::select("SELECT PC.ChiefComplain AS ChiefComplain, PC.CCDurationValue AS CCDurationValue,
            PC.OtherCC AS OtherCC, RD.DurationInEnglish AS DurationInEnglish, PC.CreateDate
            FROM MDataPatientCCDetails as PC
            INNER JOIN RefDuration as RD on RD.DurationId = PC.DurationId
            WHERE PC.PatientId ='$patient_id' AND PC.CreateDate ='$create_date'");

            


            $HeightWeight= DB::select("SELECT TOP 1 Height, Weight, BMI, BMIStatus, CreateDate FROM MDataHeightWeight WHERE PatientId ='$patient_id' AND CAST(CreateDate AS date)='$create_date' ORDER BY CreateDate DESC");


            $BP= DB::select("SELECT TOP 1 BPSystolic1, BPDiastolic1, BPSystolic2,BPDiastolic2,HeartRate,CurrentTemparature,CreateDate
            FROM MDataBP
            WHERE PatientId ='$patient_id' AND  CAST(CreateDate AS date) = '$create_date' ORDER BY CreateDate DESC");

            $GlucoseHb= DB::select("SELECT TOP 1 RBG, FBG, Hemoglobin, HrsFromLastEat, CreateDate
            FROM MDataGlucoseHb
            WHERE PatientId ='$patient_id' AND  CAST(CreateDate AS date)='$create_date' ORDER BY CreateDate DESC");

            $ProvisionalDx= DB::select("SELECT TOP 1 ProvisionalDiagnosis, DiagnosisStatus, OtherProvisionalDiagnosis, CreateDate
            FROM MDataProvisionalDiagnosis
            WHERE PatientId ='$patient_id' AND  CAST(CreateDate AS date) ='$create_date'ORDER BY CAST(CreateDate AS date) DESC");

            $Investigation= DB::select("SELECT TOP 1 RI.Investigation, I.OtherInvestigation, I.CreateDate
            FROM MDataInvestigation as I
            INNER JOIN RefLabInvestigation as RI on RI.RefLabInvestigationId = I.InvestigationId
            WHERE I.PatientId ='$patient_id' AND  CAST(I.CreateDate AS date)='$create_date' ORDER BY CAST(I.CreateDate AS date) DESC");

            $Treatment= DB::select("SELECT TOP 1 T.Frequency, T.DrugDurationValue, T.OtherDrug, T.SpecialInstruction, Dr.DrugCode, Dr.Description, Dr.DrugDose, Ins.InstructionInBangla, T.CreateDate
            FROM MDataTreatmentSuggestion as T
            INNER JOIN RefDrug as Dr on Dr.DrugId = T.DrugId
            INNER JOIN RefInstruction as Ins on Ins.RefInstructionId = T.RefInstructionId
            WHERE T.PatientId ='$patient_id' AND  CAST(T.CreateDate AS date)='$create_date' ORDER BY CAST(T.CreateDate AS date) DESC");

            $Advice= DB::select("SELECT TOP 1 RA.AdviceInBangla, RA.AdviceInEnglish, A.CreateDate
            FROM MDataAdvice as A
            INNER JOIN RefAdvice as RA on RA.AdviceId = A.AdviceId
            WHERE A.PatientId ='$patient_id' AND  CAST(A.CreateDate AS date)='$create_date' ORDER BY CAST(A.CreateDate AS date) DESC");

            $PatientReferral= DB::select("SELECT TOP 1 RR.Description, HC.HealthCenterName, PR.CreateDate
            FROM MDataPatientReferral as PR
            INNER JOIN RefReferral as RR on RR.RId = PR.RId
            INNER JOIN HealthCenter as HC on HC.HealthCenterId = PR.HealthCenterId
            WHERE PR.PatientId ='$patient_id' AND  CAST(PR.CreateDate AS date)='$create_date' ORDER BY CAST(PR.CreateDate AS date) DESC");

            $FollowUpDate= DB::select("SELECT TOP 1 FollowUpDate, Comment, CreateDate
            FROM MDataFollowUpDate
            WHERE PatientId ='$patient_id' AND  CAST(CreateDate AS date)='$create_date' ORDER BY CreateDate DESC");

           }
        }

        
       return view('prescription::details',compact('patientDetails','prescriptionCreation','Complaints','HeightWeight','BP','GlucoseHb','ProvisionalDx','Investigation','Treatment','Advice','PatientReferral','FollowUpDate'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('prescription::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}