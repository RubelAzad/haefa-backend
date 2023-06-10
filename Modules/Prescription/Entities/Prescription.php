<?php

namespace Modules\Prescription\Entities;
use Modules\Patient\Entities\Gender;
use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends BaseModel
{
    protected $table = 'PrescriptionCreation';
    public $timestamps = false;

    protected $fillable = ['PrescriptionCreationId','PrescriptionId',
    'PatientId','Status','CreateUser','CreateDate','UpdateUser','UpdateDate','OrgId','EmployeeId'];

    protected $order = ['CreateDate'=>'desc'];
    
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
    
    private function get_datatable_query()
    {
        if(permission('patient-bulk-delete')){
            $this->column_order = [null,'PatientId','name','status',null];
        }else{
            $this->column_order = ['PatientId','name','status',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('PatientId', 'like', '%' . $this->name . '%');
        }

        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    public function count_all()
    {
        return self::toBase()->get()->count();
    }
}
