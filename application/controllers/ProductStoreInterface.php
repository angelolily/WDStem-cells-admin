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
        $this->load->helper('tool');
    }


    /**
     *  小程序端登陆，根据login_type来判断：1=客户端小程序登陆，2=代理商小程序登陆。
     */
    public function ControlHomeProductList()
    {

        $requestData=$this->wproductstore->getHomeProductList();
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
        $requestData=array();


        if (array_key_exists("customer_agent", $info)  && array_key_exists("order_customer_name", $info)  && array_key_exists("order_autoid", $info) && array_key_exists("order_type", $info))
        {


            if($info['order_type']==1)
            {
                //代理商查看订单
                $requestData=$this->wproductstore->getAgentOrderList($info);


            }
            else
            {
                //客户查看订单
                $requestData=$this->wproductstore->getCustomeOrderList($info);
            }

            header("HTTP/1.1 200 Created");
            header("Content-type: application/json");
            echo json_encode($requestData);



        }
        else
        {
            $requestData['Data']='';
            $requestData["ErrorCode"]="parameter-error";
            $requestData["ErrorMessage"]="参数接收错误";
            $requestData["Success"]=false;
            $requestData["Status_Code"]="OSS203";

        }

    }


    /**
     *   首次添加订单
     */
    public function ControlOrderOneAdd()
    {
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData=array();
        if($agentinfo!=""){
            $keys="order_lowPrice,order_product,order_num,order_type,order_statue,order_price,order_customer,order_customer_name,order_user";
            $errorKey=existsArrayKey($keys,$info);
            if($errorKey=="")
            {



                $requestData=$this->wproductstore->OneAddOrder($info);



            }
            else
            {
                $requestData['Data']='';
                $requestData["ErrorCode"]="parameter-error";
                $requestData["ErrorMessage"]="参数接收错误";
                $requestData["Success"]=false;
                $requestData["Status_Code"]="OAD203";

            }

        }
        else
        {
            $requestData['Data']='';
            $requestData["ErrorCode"]="parameter-error";
            $requestData["ErrorMessage"]="参数接收错误";
            $requestData["Success"]=false;
            $requestData["Status_Code"]="OAD203";

        }

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);



    }


    /**
     *  短信接口
     */
    public function ControlSendSMS(){

        // 短信应用SDK AppID
        $appid = 1400159743; // 1400开头
        // 短信应用SDK AppKey
        $appkey = "49b360a1ba1a7dd2bac744bd0395658a";
        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $keys="sendPhone,sendName";
        $requestData=array();
        if($agentinfo!=""){
            $errorKey=existsArrayKey($keys,$info);
            if($errorKey=="")
            {

                $sendinfo[0]=$info['sendName'];
                $sendinfo[1]=rand(1111,9999);
                $ssender = new SmsSingleSender($appid, $appkey);
                $requestSMS= $ssender->sendWithParam("86",$info['sendPhone'],"952423",$sendinfo,"沃顿健康管理");
                $rsp = json_decode($requestSMS, true);
                if ($rsp["result"] === 0) {
                    $requestData['Data']=$sendinfo[1];
                    $requestData["ErrorCode"]="";
                    $requestData["ErrorMessage"]="";
                    $requestData["Success"]=true;
                    $requestData["Status_Code"]="SMS200";

                } else {
                    $requestData['Data']="";
                    $requestData["ErrorCode"]="";
                    $requestData["ErrorMessage"]=$rsp['errmsg'];
                    $requestData["Success"]=true;
                    $requestData["Status_Code"]="SMS201";

                }
            }
            else{
                $requestData['Data']='';
                $requestData["ErrorCode"]="parameter-error";
                $requestData["ErrorMessage"]="参数接收错误";
                $requestData["Success"]=false;
                $requestData["Status_Code"]="SMS203";
            }
        }
        else
        {
            $requestData['Data']='';
            $requestData["ErrorCode"]="parameter-error";
            $requestData["ErrorMessage"]="参数接收错误";
            $requestData["Success"]=false;
            $requestData["Status_Code"]="SMS203";

        }
        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);


    }







}