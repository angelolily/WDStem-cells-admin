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

    }


    /**
     *  小程序端登陆，根据login_type来判断：1=客户端小程序登陆，2=代理商小程序登陆。
     */
    public function wechat_login()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData=array();


        if (array_key_exists("code", $info)  && array_key_exists("clien_openid", $info) && array_key_exists("login_type", $info))
        {


            $requestData=$this->wechatloginregister->wechatLogin($info);


        }
        else{
            $requestData['Data']='';
            $requestData["ErrorCode"]="parameter-error";
            $requestData["ErrorMessage"]="参数接收错误";
            $requestData["Success"]=false;
            $requestData["Status_Code"]="WL206";
        }
        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);




    }




}