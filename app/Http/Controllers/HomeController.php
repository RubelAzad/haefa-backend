<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Entities\Customer;
use Modules\Expense\Entities\Expense;
use Modules\HRM\Entities\Payroll;
use Modules\Purchase\Entities\Purchase;
use Modules\Sale\Entities\Sale;
use Modules\Supplier\Entities\Supplier;

class HomeController extends Controller
{
    protected function setPageData($page_title,$sub_title,$page_icon)
    {
        view()->share(['page_title'=>$page_title,'sub_title'=>$sub_title,'page_icon'=>$page_icon]);
    }

    public function index()
    {
        if (permission('dashboard-access')) {
            $this->setPageData('Dashboard','Dashboard','fas fa-tachometer-alt');

            return view('home');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function dashboard_data($start_date,$end_date)
    {
        if($start_date && $end_date)
        {
            $sale = Sale::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->sum('grand_total');

            $purchase = Purchase::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->sum('grand_total');

            $customer = Customer::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->get()->count();

            $supplier = Supplier::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->get()->count();

            $expense = Expense::toBase()->whereDate('created_at','>=',$start_date)
            ->whereDate('created_at','<=',$end_date)->sum('amount');

            $data = [
                'sale' => number_format($sale,2,'.',','),
                'purchase' => number_format($purchase,2,'.',','),
                'profit' => number_format(($sale - $purchase),2,'.',','),
                'customer' => $customer,
                'supplier' => $supplier,
                'expense' => number_format($expense,2,'.',','),
            ];

            return response()->json($data);
        }
    }

    public function unauthorized()
    {
        $this->setPageData('Unathorized','Unathorized','fas fa-ban');
        return view('unauthorized');
    }
}
