<?php


class AgentControl extends CI_Controller
{
    private $dataArr = [];//操作数据
    private $oldDataArr=[];
    function __construct()
    {
        parent::__construct();
        $this->load->service('Agent');
        $this->load->helper('tool');
        $receiveArr = file_get_contents('php://input');
        $this->oldDataArr = json_decode($receiveArr, true);
    }

    public function getCustomer()
    {
        $result = $this->agent->getCustomer($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gc000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gc002', false, 0,'未获取到数据', []);
            http_data(200, $resultArr, $this);
        }

    }
    public function newCustomer()
    {
        $result = $this->agent->newCustomer($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('nc000', true, 0,'插入成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('nc002', false, 0,'插入失败', []);
            http_data(200, $resultArr, $this);
        }

    }
    public function updateCustomer(){
        $result = $this->agent->updateCustomer($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('uc000', true, 0,'修改成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('uc002', false, 0,'修改失败', []);
            http_data(200, $resultArr, $this);
        }
    }


}
