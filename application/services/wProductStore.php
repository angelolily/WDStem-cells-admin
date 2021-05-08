<?php


/**
 * Class wProductStore
 * 产品商城操作类
 */
class wProductStore extends HTY_service
{
	/**
	 * Dept constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Custome_Model');
        $this->load->helper('url');
	}


    /**
     *
     * 获取首月商品列表
     */
    public function getHomeProductList()
    {

        $product_list=[];
        $appdata=[];
        $product_list = $this->Custome_Model->table_seleRow_limit("product_id,product_name,product_type,product_price,
                                                      product_lowPrice,product_format,
                                                      product_weights,product_details,product_cover,product_ppt,product_details", 'cell_product',[],[],10,0,'product_weights','DESC');

        if(count($product_list)>0)
        {
            $product_list[0]['product_cover']=site_url("/public/prodcutimage/".$product_list[0]['product_cover']);
            $pptfiles=explode(",",$product_list[0]['product_ppt']);
            $pptfiles = array_map(function ($item){
                return site_url("/public/prodcutimage/").$item;
            },$pptfiles);
            $product_list[0]['product_ppt']=$pptfiles;
            $appdata['Data']=$product_list[0];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="信息获取成功";
            $appdata["Success"]=true;
            $appdata["Status_Code"]="WPS200";
        }
        else
        {
            $appdata['Data']=[];
            $appdata["ErrorCode"]="nothing-data";
            $appdata["ErrorMessage"]="无数据";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="WPS201";
        }




        return $appdata;
        
    }


    /**
     * 查看订单列表
     * order_type:1:代理商查看订单，2：客户查看订单
     */
    public function getOrderList()
    {


        

    }

    
    

    





}







