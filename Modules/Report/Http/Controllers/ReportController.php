<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Report\Entities\Report;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;

class ReportController extends BaseController
{
    public function __construct(Report $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $this->setPageData('Patient Age Count Report','Patient Age Count Report','fas fa-th-list');
        return view('report::index');
    }
    public function datewisedxindex(){
        $this->setPageData('Datewise Provisional DX','Datewise Provisional DX','fas fa-th-list');
        return view('report::datewisedx');
    }

    public function SearchByDate(Request $request){
        $starting_date = $request->starting_date;
        $ending_date = $request->ending_date;

        $results = DB::table("MDataProvisionalDiagnosis")
            ->select(DB::raw("CAST(CreateDate AS DATE) as CreateDate"), 'ProvisionalDiagnosis', DB::raw('COUNT(*) as Total'))
            ->whereDate('CreateDate', '>=', $starting_date)
            ->whereDate('CreateDate', '<=', $ending_date)
            ->groupBy(DB::raw("CAST(CreateDate AS DATE)"), 'ProvisionalDiagnosis')
            ->get();


        $this->setPageData('Datewise Provisional DX','Datewise Provisional DX','fas fa-th-list');
        return view('report::datewisedx',compact('results'));

    }
    public function SearchByAge(Request $request){
        $starting_age = $request->starting_age;
        $ending_age = $request->ending_age;
        $male=$female=$maleBelowFive=$maleAboveFive=$femaleBelowFive=$femaleAboveFive=$Total=0;

        $results = DB::table("Patient")
            ->where('Age', '>=', $starting_age)
            ->where('Age', '<=', $ending_age)
            ->join('MDataProvisionalDiagnosis','Patient.PatientId', '=', 'MDataProvisionalDiagnosis.PatientId')
            ->join('RefGender','Patient.GenderId', '=', 'RefGender.GenderId')
            ->get(['ProvisionalDiagnosis','OtherProvisionalDiagnosis','GivenName','FamilyName','Age','GenderCode']);
        foreach ($results as $result){
            if ($result->Age < 6 && $result->GenderCode == 'Male'){
                $maleBelowFive++;
            }
            if ($result->Age > 5 && $result->GenderCode == 'Male'){
                $maleAboveFive++;
            }
            if ($result->Age < 6 && $result->GenderCode == 'Female'){
                $femaleBelowFive++;
            }
            if ($result->Age > 5 && $result->GenderCode == 'Female'){
                $femaleAboveFive++;
            }
            if ($result->GenderCode == 'Male'){
                $male++;
            }
            if ($result->GenderCode == 'Female'){
                $female++;
            }

        }
        $Total=$male+$female;


        $this->setPageData(
            'Report-'. str_repeat(' ', 2).
            'Total: '. $Total .','.  str_repeat(' ', 2).
            'Male: '. $male .','.  str_repeat(' ', 2)  .
            'Female: '.$female.','. str_repeat(' ', 2) .
            'Male 0-5: '. $maleBelowFive .','. str_repeat(' ', 2) .
            'Male above 5: '. $maleAboveFive .','. str_repeat(' ', 2) .
            'Female 0-5: '. $femaleBelowFive .','. str_repeat(' ', 2) .
            'Female above 5: '. $femaleAboveFive,
            'Patient Age Count Report',
            'fas fa-th-list'
        );

        return view('report::index',compact('results','male','Total','female','maleAboveFive','maleBelowFive','femaleAboveFive','femaleBelowFive'));

    }

    public function PatientBloodPressureGraph(){
        $this->setPageData('Patient Blood Pressure Graph','Patient wise Blood Pressure Graph','fas fa-th-list');
        $startDate = $_GET['starting_date']??''; 
        $endDate = $_GET['ending_date']??''; 
        $RegistrationId = $_GET['registration_id']??'';

        $datas = DB::select("
            SELECT TOP 7 CONVERT(date, MDataBP.CreateDate) AS DistinctDate, BPSystolic1, BPDiastolic1, BPSystolic2, BPDiastolic2
            FROM MDataBP
            INNER JOIN Patient ON Patient.PatientId=MDataBP.PatientId AND Patient.RegistrationId='{$RegistrationId}'
            WHERE CONVERT(date, MDataBP.CreateDate) BETWEEN ? AND ? AND BPSystolic1 !='' AND BPSystolic2 !=''
            AND BPSystolic2 !='' AND BPDiastolic2 !=''
            GROUP BY CONVERT(date, MDataBP.CreateDate), BPSystolic1, BPDiastolic1, BPSystolic2, BPDiastolic2
            ORDER BY DistinctDate DESC
        ", [$startDate, $endDate]);

        $BPSystolic1 = array();
        $BPDiastolic1 = array();
        $BPSystolic2 = array();
        $BPDiastolic2 = array();
        $DistinctDate = array();

        foreach ($datas as $row) {
            array_push($BPSystolic1, $row->BPSystolic1);
            array_push($BPDiastolic1, $row->BPDiastolic1);

            array_push($BPSystolic2, $row->BPSystolic2);
            array_push($BPDiastolic2, $row->BPDiastolic2);

            array_push($DistinctDate, $row->DistinctDate);
        }   

        $BPSystolic1Numeric = json_encode($BPSystolic1,JSON_NUMERIC_CHECK);
        $BPDiastolic1Numeric = json_encode($BPDiastolic1, JSON_NUMERIC_CHECK);
        $BPSystolic2Numeric = json_encode($BPSystolic2, JSON_NUMERIC_CHECK);
        $BPDiastolic2Numeric = json_encode($BPDiastolic2, JSON_NUMERIC_CHECK);

        return view('report::patientbloodpressuregraph',compact('BPSystolic1Numeric','BPDiastolic1Numeric',
        'BPSystolic2Numeric','BPDiastolic2Numeric','DistinctDate','BPSystolic1','BPDiastolic1',
        'BPSystolic2','BPDiastolic2'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('report::create');
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
    public function show($id)
    {
        return view('report::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('report::edit');
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
