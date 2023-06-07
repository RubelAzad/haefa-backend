<?php

namespace Modules\Deviceregistration\Entities;

use Modules\Base\Entities\BaseModel;

class Deviceregistration extends BaseModel
{
    protected $fillable = ['name','deviceId','status','created_by','updated_by'];
    
    protected $name;
    protected $deviceId;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;
    }

    private function get_datatable_query()
    {
        if(permission('deviceregistration-bulk-delete')){
            $this->column_order = [null,'id','name','deviceId','status',null];
        }else{
            $this->column_order = ['id','name','deviceId','status',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->deviceId)) {
            $query->where('deviceId', 'like', '%' . $this->deviceId . '%');
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
