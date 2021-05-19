<?php


/**
 * Class
 */
class Agent extends HTY_service
{
    /**
     * Dept constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sys_Model');
        $this->load->helper('tool');
    }


    public function getCustomer($val)
    {
        $field="custome_id,custome_created_time,custome_name,custome_phone,custome_sex,custome_agent_rate";
        $where=[];
        $where['custome_agent']=$val['custome_agent'];
        if($val['custome_name']!=""){
            $where['custome_name']=$val['custome_name'];
        }
        $begin=$val['rows'];
        $offset=($val['pages']-1)*$val['rows'];
        $allData=$this->Sys_Model->table_seleRow("custome_id",'cell_customer',$where);
        $result['total']=count($allData);
        $result['data']=$this->Sys_Model->table_seleRow_limit($field,"cell_customer",$where,array(),$begin,$offset,"custome_id","DESC");
        return $result;
    }
    public function newCustomer($val)
    {
        $val['custome_created_by'] = $val['custome_agent'];
        $val['custome_created_time'] = date('Y-m-d H:i');
        $result=$this->Sys_Model->table_addRow("cell_customer",$val);
        return $result;
    }
    public function updateCustomer($val)
    {
        $where=[];
        $update=[];
        $where['custome_id']=$val['custome_id'];
        $update['custome_name']=$val['custome_name'];
        $update['custome_sex']=$val['custome_sex'];
        $update['custome_agent_rate']=$val['custome_agent_rate'];
        $val['custome_updated_by'] = $val['custome_agent'];
        $val['custome_updated_time'] = date('Y-m-d H:i');
        $result=$this->Sys_Model->table_updateRow("cell_customer",$update,$where);
        return $result;
    }

}







