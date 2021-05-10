<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

        header("HTTP/1.1 200 Created");
        header("Content-type: application/json");
        echo json_encode($requestData);



    }







}