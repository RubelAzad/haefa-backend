<?php

namespace Modules\BarcodeGenerat\Http\Controllers;
use Illuminate\Http\Request;
use Modules\BarcodeGenerat\Entities\BarcodeGenerate;
use Modules\BarcodeFormat\Entities\BarcodeFormat;
use Modules\Base\Http\Controllers\BaseController;
use Modules\BarcodeGenerat\Http\Requests\BarcodeGenerateFormRequest;
use DB;
use Carbon\Carbon;
use Milon\Barcode\DNS1D;
use Auth;


class BarcodeGeneratController extends BaseController
{
    public function __construct(BarcodeGenerate $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('bgenerate-access')){
            $this->setPageData('Barcode Format','Barcode Format','fas fa-th-list');
            $data = [
                'barcodeFormates' => BarcodeFormat::get(),
            ];
            return view('barcodegenerat::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('bgenerate-access')){
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

                    // if(permission('bgenerate-edit')){
                    //     $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    // }
                    // if(permission('bgenerate-delete')){
                    //     $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->mdata_barcode_prefix_number . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    // }


                    $row = [];

                    if(permission('bgenerate-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->mdata_barcode_prefix_number;
                    $row[] = $value->mdata_barcode_status;
                    $row[] = permission('bgenerate-edit') ? change_status($value->id,$value->status,$value->mdata_barcode_prefix_number) : STATUS_LABEL[$value->status];
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

    public function store_or_update_data(Request $request){
        $data = array();
        $data['code_format'] = $request->mdata_barcode_prefix;
        $barcodeNumber= (int) $request->mdata_barcode_number;

        if($request->mdata_barcode_number == "10000001"){
            $intBarcodeNumber = $barcodeNumber-1;
            $data['range'] = (string) $intBarcodeNumber;
        }else{
            $data['range'] = $request->mdata_barcode_number;
        }
        
        $data['generate'] = $request->mdata_barcode_generate;

        $jsonObjects = array(); // Array to store each iteration as a JSON object

        foreach (range(0, $request->mdata_barcode_generate - 1) as $i) {
            $data['range']++; // Increase the value of $data['range'] by 1
            $data['create_date'] = Carbon::now()->toDateTimeString();
            $data['status'] = '1';
            $data['concat'] = $data['code_format'].$data['range'];
            $data['barcode'] = DNS1D::getBarcodeHTML($data['concat'], 'C39');
            $data['barcodeBase64'] = base64_encode($data['barcode']);


            // Add a copy of the data array to the jsonObjects array


            // Insert the data into the database
            $result=DB::table('mdatacc_barcodes')->insert([
                'mdata_barcode_prefix' => $data['code_format'],
                'mdata_barcode_number' => $data['range'],
                'mdata_barcode_prefix_number'=>$data['concat'],
                'mdata_barcode_generate'=> $data['barcodeBase64'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'status' => $data['status'],
                'created_by' => auth()->user()->name
            ]);

            $output = $this->store_message('ok',$request->update_id);

        }
        return response()->json($output);
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('bgenerate-edit')){
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
            if(permission('bgenerate-delete')){
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
            if(permission('bgenerate-bulk-delete')){
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
            if (permission('bgenerate-edit')) {
                $result = $this->model->find($request->id)->update(['status'=>$request->status]);
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
    public function latest_range($mdata_barcode_prefix){
        
        $check=BarcodeGenerate::where('mdata_barcode_prefix',$mdata_barcode_prefix)->get();
        if($check->isEmpty()){
            $check=BarcodeFormat::select('barcode_number')->where('barcode_prefix',$mdata_barcode_prefix)->latest('barcode_number')->first();
        }else{
            $check = BarcodeGenerate::select('mdata_barcode_number')->where('mdata_barcode_prefix', $mdata_barcode_prefix)->latest('mdata_barcode_number')->first();
        }
        return json_encode($check);

    }
}
