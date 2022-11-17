<?php
class CheckControl extends CI_Controller {
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('Check');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, true);
    }

    /**
     *获取用户余额
     */
    public function getUserBalance(){
        $res_user_id = $this->check->getUserId($this->receive_data);
        if(!$res_user_id){
            $resultArr = build_resultArr('GUB001', false, 0,'获取用户id失败', null );
            http_data(200, $resultArr, $this);
        }
        $res = $this->check->getUserBalance($res_user_id[0]['custome_id']);
        if (!$res) {
            $resultArr = build_resultArr('GUB002', false, 0,'获取用户余额失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GUB000', true, 0,'获取用户余额成功', $res[0] );
        http_data(200, $resultArr, $this);
    }

    /**
     *获取商品信息
     */
    public function getProductInf(){
        $res_user_id = $this->check->getUserId($this->receive_data);
        if(!$res_user_id){
            $resultArr = build_resultArr('GPI001', false, 0,'获取用户id失败', null );
            http_data(200, $resultArr, $this);
        }
        $this->receive_data['custome_id'] = $res_user_id[0]['custome_id'];
        $res = $this->check->getProductInf($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('GPI002', true, 0,'获取商品信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $product_type = $res[0]['product_type'];
        if($product_type === '牙髓干细胞应用' || $product_type === '免疫干细胞应用'){
            $aim_certificate_type = '免疫细胞储存';
            if($product_type === '牙髓干细胞应用'){
                $aim_certificate_type = '牙髓干细胞储存';
            }
            $res_save = $this->check->getCertificateType($this->receive_data);
            $res[0]['save_flag'] = false;
            if(count($res_save)>0){
                $res_test = "";
                for($i=0;$i<count($res_save);$i++){
                    if(array_values($res_save[$i])[0] === $aim_certificate_type){
                        $res[0]['save_flag'] = true;
                    }
                }
                $resultArr = build_resultArr('GPI000', true, 0,'获取商品信息成功', $res[0] );
                http_data(200, $resultArr, $this);
            }
            $resultArr = build_resultArr('GPI000', true, 0,'获取商品信息成功', $res[0] );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GPI000', true, 0,'获取商品信息成功', $res[0]);
        http_data(200, $resultArr, $this);
    }

    /**
     *获取商品封面
     */
    public function getCover(){
        $dir_original='./public/prodcutimage';
        $handler = opendir($dir_original);
        if(!$handler) {
            $resultArr = build_resultArr('GC001', false, 0,'获取商品封面成功', null );
            http_data(200, $resultArr, $this);
        }
        $base_url='http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/index.php')+1);
        $file_path = $base_url . 'public/productimg/'. $this->receive_data['product_cover'];
        closedir($handler);
        $res = array(
            'name'=>$this->receive_data['product_cover'],
            'url'=>$file_path,
            'raw'=>"image/jpg",
            'type'=>"image/jpg"
        );
        $resultArr = build_resultArr('GC000', true, 0,'获取商品封面成功', $res );
        http_data(200, $resultArr, $this);
    }

    /**
     *判断用户余额是否足够支付
     */
    public function checkUserBalance(){
        $res_balance = $this->check->getUserBalance($this->receive_data['custome_id']);
        if (!$res_balance) {
            $resultArr = build_resultArr('CUB001', false, 0,'获取用户余额失败', null );
            http_data(200, $resultArr, $this);
        }
        $user_balance = $res_balance[0]['custome_balance'];
        $order_price = $this->receive_data['product_price'];
        if($user_balance >= $order_price){
            $this->receive_data['new_balance'] = $user_balance - $order_price;
            $res_set_balance = $this->check->setNewBalance($this->receive_data);
            if(!$res_set_balance){
                $resultArr = build_resultArr('CUB002', false, 0,'更新用户余额失败', null );
                http_data(200, $resultArr, $this);
            }
            $time = date('Y-m-d H:i:s');
            $this->receive_data['pay_time'] = $time;
            $res_set = $this->check->setPayTime($this->receive_data);
            if(!$res_set){
                $resultArr = build_resultArr('CUB003', false, 0,'设置支付时间失败', null );
                http_data(200, $resultArr, $this);
            }
            $resultArr = build_resultArr('CUB004', true, 0,'更新用户余额成功', $time );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('CUB005', true, 0,'用户余额不足', null );
        http_data(200, $resultArr, $this);
    }

    /**
     *生成订单到数据库
     */
    public function setOrder(){
        //判断状态是带付款还是带审核
        $this->receive_data['order_statue'] = '待上传';
        if($this->receive_data['order_product'] === '牙髓干细胞储存'){
            $this->receive_data['order_statue'] = '待填写';
        }
        //获取代理商的部门id
        $res_dept = $this->check->getDeptId($this->receive_data['order_service']);
        if(!$res_dept){
            $resultArr = build_resultArr('SO001', false, 0,'获取用户专属客服信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $this->receive_data['UserDept'] = $res_dept[0]['UserDept'];
        $this->receive_data['order_id'] = time().rand(1000,9999);
        $res = $this->check->setOrder($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('SO002', false, 0,'生成订单失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('SO000', true, 0,'生成订单成功', $this->receive_data['order_id'] );
        http_data(200, $resultArr, $this);
    }

    /**
     *获取订单信息
     */
    public function getOrderInf(){
        $res = $this->check->getOrderInf($this->receive_data['order_id']);
        if(!$res){
            $resultArr = build_resultArr('GOI001', false, 0,'获取订单信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GOI000', true, 0,'获取订单信息成功', $res[0] );
        http_data(200, $resultArr, $this);
    }

    /**
     *更新订单表中的身份证信息
     */
    public function setUserInf(){
        $res = $this->check->setUserInf($this->receive_data);
        if(!$res){
            $resultArr = build_resultArr('GOI001', false, 0,'更新订单信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GOI000', true, 0,'更新订单信息成功', $res[0] );
        http_data(200, $resultArr, $this);
    }
}