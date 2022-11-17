<?php
class QuestionnaireControl extends CI_Controller{
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('Questionnaire');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, true);
    }
    /**
     *获取订单信息判断告知书类型
     */
    public function getProductInf(){
        $res = $this->questionnaire->getProductInf($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('GPI001', false, 0,'获取订单信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $order_product = $res[0]['order_type'];
        $resultArr = build_resultArr('GPI000', true, 0,'获取订单信息成功', $order_product);
        http_data(200, $resultArr, $this);
    }

    /**
     *存储购买人和存储人信息
     */
    public function setOrderUserInf(){
        $res_user_id = $this->questionnaire->getUserId($this->receive_data);
        if(!$res_user_id){
            $resultArr = build_resultArr('SOUI001', false, 0,'获取用户id失败', null );
            http_data(200, $resultArr, $this);
        }
        $this->receive_data['custome_id'] = $res_user_id[0]['custome_id'];
        $this->receive_data['custome_name'] = $res_user_id[0]['custome_name'];
        $res = $this->questionnaire->setOrderUserInf($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('SOUI002', false, 0,'存储人物信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('SOUI000', true, 0,'存储人物信息成功', $res);
        http_data(200, $resultArr, $this);
    }

    /**
     *保存电子问卷信息
     */
    public function saveQuestionnaire(){
        $res = $this->questionnaire->saveQuestionnaire($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('SQ002', false, 0,'存储问卷信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('SQ000', true, 0,'存储问卷信息成功', $res);
        http_data(200, $resultArr, $this);
    }
    //仅测试
    public function test(){
        $resultArr = build_resultArr('GPI000', true, 0,'测试成功', json_encode($this->receive_data));
        http_data(200, $resultArr, $this);
    }
}