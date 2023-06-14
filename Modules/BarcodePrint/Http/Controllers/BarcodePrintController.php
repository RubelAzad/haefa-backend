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

    // public function generateBarcode(BarcodeFormRequest $request)
    // {
    //     if($request->ajax())
    //     {
    //         $data = [
    //             'barcode'           => $request->barcodeprint_code,
    //             'barcodeprint_name'      => $request->barcodeprint_name,
    //             'barcodeprint_price'     => $request->barcodeprint_price,
    //             'barcode_symbology' => $request->barcode_symbology,
    //             'tax_rate'          => $request->tax_rate,
    //             'tax_method'        => $request->tax_method,
    //             'barcode_qty'       => $request->barcode_qty,
    //             'row_qty'           => $request->row_qty
    //         ];
    //         return view('barcodeprint::barcode.print-area',$data)->render();
    //     }
    // }

    // public function autocomplete_search_barcodeprint(Request $request)
    // {
    //     if(!empty($request->search)){
    //         $output = array();
    //         $search_text = $request->search;
    //         $barcodeprints = barcodeprint::with('tax')->where(function($q) use($search_text){
    //             $q->where('code', 'like','%'.$search_text.'%')
    //             ->orWhere('name', 'like','%'.$search_text.'%');
    //         })->get();

    //         if(!$barcodeprints->isEmpty())
    //         {
    //             $temp_array = [];
    //             foreach ($barcodeprints as $value) {
    //                 $temp_array['code']              = $value->code;
    //                 $temp_array['name']              = $value->name;
    //                 $temp_array['price']             = $value->base_unit_price;
    //                 $temp_array['barcode_symbology'] = $value->barcode_symbology;
    //                 $temp_array['tax_rate']          = $value->tax->rate ? $value->tax->rate : 0;
    //                 $temp_array['tax_method']        = $value->tax_method;
    //                 $temp_array['value']             = $value->name.' ('.$value->code.')';
    //                 $temp_array['label']             = $value->name.' ('.$value->code.')';
    //                 $output[] = $temp_array;
    //             }
    //         }

    //         if(empty($output) && count($output) == 0)
    //         {
    //             $output['value'] = '';
    //             $output['label'] = 'No Record Found';
    //         }
    //         return $output; 
    //     }
    // }

    // public function search_barcodeprint(Request $request)
    // {
    //     $barcodeprint_data =barcodeprint::with(['tax','base_unit'])->where('code',$request['data'])->first();
    //     if($barcodeprint_data)
    //     {
    //         $barcodeprint['id']             = $barcodeprint_data->id;
    //         $barcodeprint['name']           = $barcodeprint_data->name;
    //         $barcodeprint['code']           = $barcodeprint_data->code;
    //         $barcodeprint['price']          = $barcodeprint_data->base_unit_price;
    //         $barcodeprint['base_unit_id']   = $barcodeprint_data->base_unit_id;
    //         $barcodeprint['base_unit_name'] = $barcodeprint_data->base_unit->unit_name.' ('.$barcodeprint_data->base_unit->unit_code.')';
    //         $barcodeprint['tax_rate']       = $barcodeprint_data->tax->rate ? $barcodeprint_data->tax->rate : 0;
    //         $barcodeprint['tax_name']       = $barcodeprint_data->tax->name;
    //         $barcodeprint['tax_method']     = $barcodeprint_data->tax_method;
    //         return $barcodeprint;
    //     }
    // }
}
