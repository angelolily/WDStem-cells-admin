<?php
require './qcloudsms_php/src/index.php';

use Qcloud\Sms\SmsSingleSender;
header('Content-type: text/html; charset=utf-8');


/**
 * Class ProductStoreInterface
 * 客户端商品功能接口
 */
class ProductStoreInterface extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->service('wProductStore');
        $this->load->service('CheckIdCardInformation');
        $this->load->service('Express');
        $this->load->helper('tool');
        $this->load->helper('mail');
    }


    /**
     *  小程序端登陆，根据login_type来判断：1=客户端小程序登陆，2=代理商小程序登陆。
     */
    public function ControlHomeProductList()
    {

        $requestData = $this->wproductstore->getHomeProductList();
        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);

    }


    /**
     * 获取商品列表 order_type=1，代理商获取商品列表，2，客户获取商品列表
     *
     */
    public function ControlProductList()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();


        if (array_key_exists("customer_agent", $info) && array_key_exists("order_customer_name", $info) && array_key_exists("order_autoid", $info) && array_key_exists("order_type", $info)) {


            if ($info['order_type'] == 1) {
                //代理商查看订单
                $requestData = $this->wproductstore->getAgentOrderList($info);


            } else {
                //客户查看订单
                $requestData = $this->wproductstore->getCustomeOrderList($info);
            }

            header("HTTP/1.1 200 Created");
            header("Content-type: application/json");
            echo json_encode($requestData);


        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "OSS203";

        }

    }


    /**
     *   首次添加订单
     */
    public function ControlOrderOneAdd()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "order_lowPrice,order_product,order_num,order_type,order_statue,order_price,order_customer,order_customer_name,order_user";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->OneAddOrder($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "OAD203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "OAD203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }


    /**
     *  短信接口
     */
    public function ControlSendSMS()
    {

        // 短信应用SDK AppID
        $appid = 1400159743; // 1400开头
        // 短信应用SDK AppKey
        $appkey = "49b360a1ba1a7dd2bac744bd0395658a";
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $keys = "sendPhone,sendName";
        $requestData = array();
        if ($agentinfo != "") {
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {

                $sendinfo[0] = $info['sendName'];
                $sendinfo[1] = rand(1111, 9999);
                $ssender = new SmsSingleSender($appid, $appkey);
                $requestSMS = $ssender->sendWithParam("86", $info['sendPhone'], "952423", $sendinfo, "沃顿健康管理");
                $rsp = json_decode($requestSMS, true);
                if ($rsp["result"] === 0) {
                    $sendinfo[0] = $info['sendPhone'];
                    $requestData['Data'] = $sendinfo;
                    $requestData["ErrorCode"] = "";
                    $requestData["ErrorMessage"] = "";
                    $requestData["Success"] = true;
                    $requestData["Status_Code"] = "SMS200";

                } else {
                    $requestData['Data'] = "";
                    $requestData["ErrorCode"] = "";
                    $requestData["ErrorMessage"] = $rsp['errmsg'];
                    $requestData["Success"] = true;
                    $requestData["Status_Code"] = "SMS201";

                }
            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "SMS203";
            }
        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "SMS203";

        }
        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }


    /**
     * 获取当前登陆客户的所有预约记录
     */
    public function getSubscribe()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "subscribe_custome,subscribe_id";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->getSubscribe($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "SUB203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "SUB203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }


    /**
     * 获取当前登陆客户的所有投诉建议记录
     */
    public function getAdvice()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "advice_custome,advice_id";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->getAdvice($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "ADV203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ADV203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }


    /**
     *   新增投诉建议
     */
    public function addAdvice()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "advice_type,advice_custome,advice_center,advice_phone,custome_deptid";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->AddAdvice($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "ADV203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ADV203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);

    }


    /**
     *  获取我的账户余额
     */
    public function getMyAccount()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "custome_id";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->getAccount($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "ACNT203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ACNT203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);

    }

    /**
     *  获取充值记录
     */
    public function getRachargeList()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "recharge_custome,recharge_id";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->getRechargeList($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "ACNT203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ACNT203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);

    }

    /**
     *  添加充值记录
     */
    public function addRecharge()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "recharge_money,recharge_custome,recharge_rate";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->addRecharge($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "ADV203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ADV203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);
    }

    /**
     *  修改充值记录状态
     */
    public function modifyRechargeState()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "recharge_id";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->modifyRecharge($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "ADV203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ADV203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);
    }

    /**
     *  上传身份证照片进行验证
     */
    public function isCardTrue()
    {
        $files = $_FILES;
        $resulArr = [];
        $saveFileName = "";
        $ordernum = $this->input->post('order_id');//订单id
        $applyname = $this->input->post('applyname');//验证姓名
        $custome_ide = $this->input->post('custome_id');//判断是否要同步更新客户表

        $requestData = array();
        if (count($files) > 0) {
            $dirpath = "./public/idCard/" . $ordernum;
            //判断目录是否存在，如果不存在就新建
            if (is_dir($dirpath) or mkdir($dirpath)) {

            }


            $file_tmp = $files['cardfile']['tmp_name'];
            $savePath = $dirpath . "/" . $files['cardfile']['name'];
            $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件

            if ($move_result) {
                //识别身份证
                $requestData = $this->checkidcardinformation->isIdCard($savePath, $applyname, $ordernum, $custome_ide);
            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "CCA205";
            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ADV203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }


    /**
     *  上传身份证背面照片
     */
    public function saveCardBack()
    {
        $files = $_FILES;
        $resulArr = [];
        $saveFileName = "";
        $ordernum = $this->input->post('order_id');//订单id

        $requestData = array();
        if (count($files) > 0) {
            $dirpath = "./public/idCard/" . $ordernum;
            //判断目录是否存在，如果不存在就新建
            if (is_dir($dirpath) or mkdir($dirpath)) {


            }


            $file_tmp = $files['cardfileback']['tmp_name'];
            $savePath = $dirpath . "/" . $files['cardfileback']['name'];
            $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件

            if ($move_result) {

                //修改身份证照片
                $requestData = $this->wproductstore->updateBackup($ordernum, $savePath);

            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "CCA205";
            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ADV203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }


    /**
     *  上传身份证照片进行验证
     */
    public function isFaceTrue()
    {
        $files = $_FILES;
        $resulArr = [];
        $saveFileName = "";
        $ordernum = $this->input->post('order_id');//订单id
        $applyname = $this->input->post('name');//验证姓名
        $acard = $this->input->post('card');//验证身份证号码
        $custome_id = $this->input->post('custome_id');//判断是否要同步更新客户表

        $requestData = array();
        if (count($files) > 0) {
            $dirpath = "./public/idCard/" . $ordernum;
            //判断目录是否存在，如果不存在就新建
            if (is_dir($dirpath) or mkdir($dirpath)) {

            }

            //默认上传识别第一张图片
            $file_tmp = $files['face']['tmp_name'];
            $savePath = $dirpath . "/" . $files['face']['name'];
            $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件

            if ($move_result) {


                $requestData = $this->checkidcardinformation->isFacetrue($savePath, $applyname, $acard, $custome_id, $ordernum);
            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "CCA205";
            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ADV203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }

    /**
     * 获取物流信息
     */
    public function getExpress()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "order_logistics";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->express->getExpressinfo($info['order_logistics']);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "ACNT203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ACNT203";

        }

        http_data("200", $requestData, $this);


    }

    /**
     * 上传体检报告
     */
    public function uploadfileMedical()
    {
        $files = $_FILES;
        $i = 0;
        $ordernum = $this->input->post('order_id');//订单id

        if (count($files) > 0) {
            $dirpath = "./public/medical/" . $ordernum;
            //判断目录是否存在，如果不存在就新建
            if (is_dir($dirpath) or mkdir($dirpath)) {

            }
            //默认上传识别第一张图片


            foreach ($files as $file) {
                //图片按顺序保存

                $file_tmp = $file['tmp_name'];
                $savePath = $dirpath . "/" . rand(111, 222) . ".jpg";
                $move_result = move_uploaded_file($file_tmp, $savePath);//上传文件

            }


            if ($i == count($files)) {


                $requestData = $this->wproductstore->modifyHealth($ordernum);
            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "CCA205";
            }
        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "ADV203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }

    /**
     * 删除汇款中的充值记录
     */
    public function delRecharge()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "recharge_id";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->delAdvice($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "DRCH203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "DRCH203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);

    }


    /**
     * 删除订单
     *
     */
    public function delOrder()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {
            $keys = "order_id";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = $this->wproductstore->delOrder($info);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "DRCH203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "DRCH203";

        }

        http_data("200", $requestData, $this);

    }




    public function sendMail()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData = array();
        if ($agentinfo != "") {

            $keys = "outmail,theme，attachment";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {


                $requestData = sendMail($info['outmail'], $info['theme'], $info['attachment']);


            } else {
                $requestData['Data'] = '';
                $requestData["ErrorCode"] = "parameter-error";
                $requestData["ErrorMessage"] = "参数接收错误";
                $requestData["Success"] = false;
                $requestData["Status_Code"] = "DRCH203";

            }

        } else {
            $requestData['Data'] = '';
            $requestData["ErrorCode"] = "parameter-error";
            $requestData["ErrorMessage"] = "参数接收错误";
            $requestData["Success"] = false;
            $requestData["Status_Code"] = "DRCH203";

        }

        http_data("200", $requestData, $this);


    }

    
    
    













}