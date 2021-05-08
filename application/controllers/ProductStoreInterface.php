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
     */
    public function ControlProductList()
    {

        $agentinfo = file_get_contents('php://input');
        $info = json_decode($agentinfo, true);
        $requestData=array();


        if (array_key_exists("custome_agent", $info)  && array_key_exists("order_cutstome", $info)  && array_key_exists("order_autoid", $info) && array_key_exists("order_type", $info))
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

    }







}