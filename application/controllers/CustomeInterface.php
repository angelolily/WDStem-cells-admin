<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-type: text/html; charset=utf-8');

/**
 * Class CustomeInterface
 * 客户端接口类
 */
class CustomeInterface extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->service('WechatLoginRegister');
        $this->load->helper('tool');
    }


    /**
     *  小程序端登陆，根据login_type来判断：1=客户端小程序登陆，2=代理商小程序登陆。
     */
    public function wechat_login()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData=array();


        if (array_key_exists("code", $info)  && array_key_exists("clien_openid", $info)  && array_key_exists("UserPassword", $info) && array_key_exists("login_type", $info))
        {

            if($info['login_type']==1)
            {
                $requestData=$this->wechatloginregister->appLogin($info);
            }
            else{
                $requestData=$this->wechatloginregister->wechatLogin($info);
            }




        }
        else{
            $requestData['Data']='';
            $requestData["ErrorCode"]="parameter-error";
            $requestData["ErrorMessage"]="参数接收错误";
            $requestData["Success"]=false;
            $requestData["Status_Code"]="AL203";
        }
        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);




    }


    /**
     *   微信客服注册
     */
    public function wechat_custome_regist()
    {

        $agentinfo = file_get_contents('php://input');

        if($agentinfo!=""){
            $info = json_decode($agentinfo, true);
            $requestData=array();
            $keys="custome_openid,custome_name,custome_sex,custome_phone,custome_agent,UserPassword";
            $errorKey=existsArrayKey($keys,$info);
            if($errorKey=="")
            {



                $requestData=$this->wechatloginregister->wechatCustomerRegist($info);



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
     *  微信代理商注册
     */
    public function wechat_agent_regist()
    {

        $agentinfo = file_get_contents('php://input');

        if($agentinfo!="") {
            $info = json_decode($agentinfo, true);
            $requestData = array();
            $keys = "UserName,Mobile,Birthday,UserEmail,Sex,Avatar,openid,UserBank,UserBankNum";
            $errorKey = existsArrayKey($keys, $info);
            if ($errorKey == "") {

                $requestData=$this->wechatloginregister->wechatAgentRegist($info);

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











}