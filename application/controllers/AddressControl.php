<?php
class AddressControl extends CI_Controller{
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('Address');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, true);
    }

    /**
     *获取用户所有收获地址
     */
    public function getUserAddress(){
        $res_user_id = $this->address->getUserId($this->receive_data);
        if(!$res_user_id){
            $resultArr = build_resultArr('GUA001', false, 0,'获取用户id失败', null );
            http_data(200, $resultArr, $this);
        }
        $this->receive_data['custome_id'] = $res_user_id[0]['custome_id'];
        $res = $this->address->getUserAddress($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('GUA002', false, 0,'获取用户收获地址失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GUA000', true, 0,'获取用户收获地址成功', $res );
        http_data(200, $resultArr, $this);
    }

    /**
     *根据收获地址id获取收获地址
     */
    public function getAimAddress(){
        $res = $this->address->getAimAddress($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('GAA001', false, 0,'获取用户收获地址失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GAA000', true, 0,'获取用户收获地址成功', $res[0] );
        http_data(200, $resultArr, $this);
    }

    /**
     *获取用户默认收获地址
     */
    public function getDefaultAddress(){
        $res_user_id = $this->address->getUserId($this->receive_data);
        if(!$res_user_id){
            $resultArr = build_resultArr('GDA001', false, 0,'获取用户id失败', null );
            http_data(200, $resultArr, $this);
        }
        $this->receive_data['custome_id'] = $res_user_id[0]['custome_id'];
        $res = $this->address->getDefaultAddress($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('GDA002', false, 0,'获取用户默认收获地址失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GDA000', true, 0,'获取用户默认收获地址成功', $res[0] );
        http_data(200, $resultArr, $this);
    }

    /**
     *保存收获地址
     */
    public function saveAddress(){
        $res_user_id = $this->address->getUserId($this->receive_data);
        if(!$res_user_id){
            $resultArr = build_resultArr('SA001', false, 0,'获取用户id失败', null );
            http_data(200, $resultArr, $this);
        }
        $user_id = $res_user_id[0]['custome_id'];
        //根据用户收获地址数量判断是否为当前用户的第一条收获地址,是第一条则默认为默认收获地址
        $res_num = $this->address->getNumOfAddress($user_id);
        if(!$res_num){
            $this->receive_data['address_default'] = 1;
        }
        //判断当前接收的收获地址是否是默认地址
        if($this->receive_data['address_default'] == 1){
            $res_default = $this->address->setDefaultAddress($user_id);
//            if(!$res_default){
//                $resultArr = build_resultArr('SA002', false, 0,'设置默认地址失败', null );
//                http_data(200, $resultArr, $this);
//            }
        }
        $type = $this->receive_data['type'];
        if($type === 'add'){
            $this->receive_data['custome_id'] = $user_id;
            $res = $this->address->addAddress($this->receive_data);
        }else{
            $res = $this->address->editAddress($this->receive_data);
        }
        if ($res === false) {
            $resultArr = build_resultArr('SA003', false, 0,'保存用户收获地址失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('SA000', true, 0,'保存用户收获地址成功', $res );
        http_data(200, $resultArr, $this);
    }


}