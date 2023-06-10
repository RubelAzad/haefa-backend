<?php

namespace Modules\Prescription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Prescription\Entities\Prescription;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Patient\Http\Requests\PatientFormRequest;
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
        if(permission('patient-access')){
            $this->setPageData('Prescription','prescription','fas fa-th-list');
            return view('prescription::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
        //return view('prescription::index');
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
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->PrescriptionCreationId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    

                    $row = [];

                    if(permission('patient-bulk-delete')){
                        $row[] = table_checkbox($value->PrescriptionCreationId);
                    }
                    $row[] = $no;
                    $row[] = $value->PrescriptionId;
                    $row[] = $value->PatientId;
                    $row[] = $value->CreateDate;
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
            if (permission('patient-view')) {
            
            $patientDetails=Prescription::where('PrescriptionCreationId','=',$request->id)->get();

            $prescriptionCreation= DB::select("SELECT PPC.PrescriptionId AS PrescriptionId,E.FirstName AS FirstName,E.LastName AS LastName,E.Designation AS Designation,E.EmployeeSignature AS EmployeeSignature, PPC.CreateDate
            FROM PrescriptionCreation as PPC 
            INNER JOIN Employee as E on E.EmployeeId = PPC.EmployeeId
            WHERE PPC.PrescriptionCreationId = '$request->id' ");

            $create_date_time = $patientDetails[0]->CreateDate;
            $create_date = date('Y-m-d',strtotime($create_date_time));

            $patient_id= $patientDetails[0]->PatientId;
                
            $Complaints= DB::select("SELECT TOP 1 MAX(PC.ChiefComplain) AS ChiefComplain, MAX(PC.CCDurationValue) AS CCDurationValue,
            MAX(PC.OtherCC) AS OtherCC, MAX(RD.DurationInEnglish) AS DurationInEnglish, CAST(PC.CreateDate AS date) as CreateDate
            FROM MDataPatientCCDetails as PC
            INNER JOIN RefDuration as RD on RD.DurationId = PC.DurationId
            WHERE PatientId ='$patient_id' AND CAST(PC.CreateDate AS date) ='$create_date' GROUP BY PC.CreateDate 
            ORDER BY PC.CreateDate DESC");

            $HeightWeight= DB::select("SELECT TOP 1 MAX(Height) AS Height, MAX(Weight) AS Weight, MAX(BMI) AS BMI, MAX(BMIStatus) AS BMIStatus, CreateDate
            FROM MDataHeightWeight
            WHERE PatientId ='$patient_id' AND  CreateDate='$create_date' GROUP BY CreateDate ORDER BY CreateDate DESC");

            $BP= DB::select("SELECT TOP 1 MAX(BPSystolic1) AS BPSystolic1, MAX(BPDiastolic1) AS BPDiastolic1, MAX(BPSystolic2) AS BPSystolic2, MAX(BPDiastolic2) AS BPDiastolic2, MAX(HeartRate) AS HeartRate, MAX(CurrentTemparature) AS CurrentTemparature, CAST(CreateDate AS date) as CreateDate
            FROM MDataBP
            WHERE PatientId ='$patient_id' AND  CreateDate='$create_date' GROUP BY CreateDate ORDER BY CreateDate DESC");

            $GlucoseHb= DB::select("SELECT TOP 1 MAX(RBG) AS RBG, MAX(FBG) AS FBG, MAX(Hemoglobin) AS Hemoglobin, MAX(HrsFromLastEat) AS HrsFromLastEat, CAST(CreateDate AS date) as CreateDate
            FROM MDataGlucoseHb
            WHERE PatientId ='$patient_id' AND  CreateDate='$create_date' GROUP BY CreateDate ORDER BY CreateDate DESC");

            $ProvisionalDx= DB::select("SELECT TOP 1 MAX(ProvisionalDiagnosis) AS ProvisionalDiagnosis, MAX(DiagnosisStatus) AS DiagnosisStatus, MAX(OtherProvisionalDiagnosis) AS OtherProvisionalDiagnosis, CAST(CreateDate AS date) as CreateDate
            FROM MDataProvisionalDiagnosis
            WHERE PatientId ='$patient_id' AND  CreateDate='$create_date' GROUP BY CreateDate ORDER BY CreateDate DESC");

            $Investigation= DB::select("SELECT TOP 1 MAX(RI.Investigation) AS Investigation, MAX(I.OtherInvestigation) AS OtherInvestigation, CAST(I.CreateDate AS date) as CreateDate
            FROM MDataInvestigation as I
            INNER JOIN RefLabInvestigation as RI on RI.RefLabInvestigationId = I.InvestigationId
            WHERE I.PatientId ='$patient_id' AND  I.CreateDate='$create_date' GROUP BY I.CreateDate ORDER BY I.CreateDate DESC");

            $Treatment= DB::select("SELECT TOP 1 MAX(T.Frequency) AS Frequency, MAX(T.DrugDurationValue) AS DrugDurationValue,MAX(T.OtherDrug) AS OtherDrug,MAX(T.SpecialInstruction) AS SpecialInstruction, MAX(Dr.DrugCode) AS DrugCode, MAX(Dr.Description) AS Description, MAX(Dr.DrugDose) AS DrugDose, MAX(Ins.InstructionInBangla) AS InstructionInBangla, CAST(T.CreateDate AS date) as CreateDate
            FROM MDataTreatmentSuggestion as T
            INNER JOIN RefDrug as Dr on Dr.DrugId = T.DrugId
            INNER JOIN RefInstruction as Ins on Ins.RefInstructionId = T.RefInstructionId
            WHERE T.PatientId ='$patient_id' AND  T.CreateDate='$create_date' GROUP BY T.CreateDate ORDER BY T.CreateDate DESC");

            $Advice= DB::select("SELECT TOP 1 MAX(RA.AdviceInBangla) AS AdviceInBangla, MAX(RA.AdviceInEnglish) AS AdviceInEnglish, CAST(A.CreateDate AS date) as CreateDate
            FROM MDataAdvice as A
            INNER JOIN RefAdvice as RA on RA.AdviceId = A.AdviceId
            WHERE A.PatientId ='$patient_id' AND  A.CreateDate='$create_date' GROUP BY A.CreateDate ORDER BY A.CreateDate DESC");

            $PatientReferral= DB::select("SELECT TOP 1 MAX(RR.Description) AS Description, MAX(HC.HealthCenterName) AS HealthCenterName, CAST(PR.CreateDate AS date) as CreateDate
            FROM MDataPatientReferral as PR
            INNER JOIN RefReferral as RR on RR.RId = PR.RId
            INNER JOIN HealthCenter as HC on HC.HealthCenterId = PR.HealthCenterId
            WHERE PR.PatientId ='$patient_id' AND  PR.CreateDate='$create_date' GROUP BY PR.CreateDate ORDER BY PR.CreateDate DESC");

            $FollowUpDate= DB::select("SELECT TOP 1 MAX(FollowUpDate) AS FollowUpDate, MAX(Comment) AS Comment, CAST(CreateDate AS date) as CreateDate
            FROM MDataFollowUpDate
            WHERE PatientId ='$patient_id' AND  CreateDate='$create_date' GROUP BY CreateDate ORDER BY CreateDate DESC");

           }
        }

       // return view('prescription::details',compact('patientDetails','prescriptionCreation','Complaints','HeightWeight','BP','GlucoseHb','ProvisionalDx'))->render();
        
        return view('prescription::details',compact('patientDetails','prescriptionCreation','Complaints','HeightWeight','BP','GlucoseHb','ProvisionalDx','Investigation','Treatment','Advice','PatientReferral','FollowUpDate'))->render();
        //return view('prescription::show');
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