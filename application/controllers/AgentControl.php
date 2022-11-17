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
    /**
     * Notes:获取客户信息
     * User: hyr
     * DateTime: 2021/5/18 10:50
     */
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
    /**
     * Notes:新增报备客户
     * User: hyr
     * DateTime: 2021/5/18 10:50
     */
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
    /**
     * Notes:修改客户信息
     * User: hyr
     * DateTime: 2021/5/18 10:50
     */
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
    /**
     * Notes:获取消息表
     * User: hyr
     * DateTime: 2021/5/18 10:50
     */
    public function getMessage(){
        $result = $this->agent->getMessage($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gm000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gm002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取可提现金额
     * User: hyr
     * DateTime: 2021/5/18 15:32
     */
    public function getMoney(){
        $result = $this->agent->getMoney($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gm000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gm002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取提现历史
     * User: hyr
     * DateTime: 2021/5/18 15:50
     */
    public function getWithdraw(){
        $result = $this->agent->getWithdraw($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gw000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gw002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:新增提现申请
     * User: hyr
     * DateTime: 2021/5/18 17:50
     */
    public function addWithdraw()
    {
        $result = $this->agent->addWithdraw($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('aw000', true, 0,'插入成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('aw002', false, 0,'插入失败', []);
            http_data(200, $resultArr, $this);
        }

    }
    /**
     * Notes:代理商获取个人信息、重要消息数、报备客户数和成交客户数
     * User: hyr
     * DateTime: 2021/5/18 17:50
     */
    public function getInfo()
    {
        $result = $this->agent->getInfo($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gt000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gt002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取代理商信息
     * User: hyr
     * DateTime: 2021/5/18 18:10
     */
    public function getAgent(){
        $result = $this->agent->getAgent($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('ga000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('ga002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:获取代理商产品图表信息
     * User: hyr
     * DateTime: 2021/5/18 18:10
     */
    public function getChart(){
        $result = $this->agent->getChart($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gc000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gc002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:代理商修改订单价格
     * User: hyr
     * DateTime: 2021/5/21 16:52
     */
    public function updatePrice(){
        $result = $this->agent->updatePrice($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('up000', true, 0,'修改成功',[] );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('up002', false, 0,'修改失败', []);
            http_data(200, $resultArr, $this);
        }
    }
    /**
     * Notes:代理商生成二维码并保存
     * User: hyr
     * DateTime: 2021/5/28 14:45
     */
    public function getQrCode(){
        $result = $this->agent->getQrCode($this->oldDataArr);
        if (count($result) > 0) {
            $resultArr = build_resultArr('gq000', true, 0,'获取成功',json_encode($result) );
            http_data(200, $resultArr, $this);
        } else {
            $resultArr = build_resultArr('gq002', false, 0,'获取失败', []);
            http_data(200, $resultArr, $this);
        }
    }

}
