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




}