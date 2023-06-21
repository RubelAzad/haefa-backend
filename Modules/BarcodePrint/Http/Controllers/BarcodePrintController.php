<?php

namespace Modules\BarcodePrint\Http\Controllers;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\BarcodePrint\Entities\BarcodePrint;
use Modules\BarcodeGenerat\Entities\BarcodeGenerate;
use DB;

class BarcodePrintController extends BaseController
{
    public function __construct(BarcodePrint $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        
        if(permission('barcode-print-access')){
            $this->setPageData('Print Barcode','Print Barcode','fas fa-barcode',[['name'=>'barcodeprint','link'=> route('barcodeprint')],['name' => 'Print Barcode']]);

            $data = [
                'barcodeGenerates' => BarcodeGenerate::get(),
            ];
            return view('barcodeprint::index',$data);
        }else{
            return $this->access_blocked();
        }
    }

    public function store_or_update_data(Request $request)
    {
        if($request->ajax()){
            $this->setPageData('Print Barcode','Print Barcode','fas fa-barcode',[['name'=>'barcodeprint','link'=> route('barcodeprint')],['name' => 'Print Barcode']]);
            $start=$request->filterValueStart;
            $end=$request->filterValueEnd;
            $barcode_type=$request->barcode_type;
            $data = [
                'barcodes' => BarcodeGenerate::whereBetween('mdata_barcode_prefix_number', [$start, $end])->get(),
            ];

            return response()->json($data);
        }else{
            return response()->json($this->access_blocked());
         }
        
    }

    public function latest_range($mdata_barcode_prefix_number_start){
        
        // Extract the string portion (letters)

        preg_match('/^[A-Za-z]+/', $mdata_barcode_prefix_number_start, $stringPortion);
        // Extract the number portion
        preg_match('/\d+$/', $mdata_barcode_prefix_number_start, $numberPortion);

        // Get the extracted portions
        $search_start_id= $stringPortion[0]; // Array element at index 0
        $search_start_code= $numberPortion[0]; // Array element at index 0

       $search_start_id = substr($mdata_barcode_prefix_number_start,0,9); 
       $search_start_code = substr($mdata_barcode_prefix_number_start,9,8); 
       $data = DB::table("mdatacc_barcodes")
                        ->where('mdata_barcode_prefix',$search_start_id)
                        ->where('mdata_barcode_number','>=',$search_start_code)
                        ->where('status',2)
                        ->orderBy('mdata_barcode_prefix','ASC')
                        ->get('mdata_barcode_prefix_number'); 

        return response()->json($data);

    }


    public function get_barcodes($barcode_type){
        $data = DB::table("mdatacc_barcodes")
            ->where('mdata_barcode_generate',$barcode_type)
            ->orderBy('mdata_barcode_prefix','ASC')
            ->get('mdata_barcode_prefix_number');

        return response()->json($data);

    }
    public function view_barcodes($startValue){


        $data=$startValue;
        return response()->json($data);
    }
}
