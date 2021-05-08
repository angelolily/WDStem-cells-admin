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


            for($i=0;$i<count($product_list);$i++)
            {
                $product_list[$i]['product_cover']=site_url("/public/prodcutimage/".$product_list[$i]['product_cover']);
                $pptfiles=explode(",",$product_list[$i]['product_ppt']);
                $pptfiles = array_map(function ($item){
                    return site_url("/public/prodcutimage/").$item;
                },$pptfiles);
                $product_list[$i]['product_ppt']=$pptfiles;
            }
            

            $appdata['Data']=$product_list;
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
     * 查看代理商订单列表
     *
     */
    public function getAgentOrderList($info=[])
    {

        $ag_custome=[];
        $appdata=[];
        $oid=[];
        if(count($info)>0)
        {
            if($info['order_autoid']==0)
            {
                $oid=[];
            }
            else
            {
                $oid=['order_autoid <='=>$info['order_autoid']];
            }

            if($info['order_cutstome']=="")
            {
                //查询该代理商下的所有客户
                $ag_custome=$this->Custome_Model->table_seleRow('custome_id',"cell_customer",['custome_agent'=>$info['custome_agent']]);
                if(count($ag_custome)>0)
                {


                    $order_list=$this->Custome_Model->table_seleRow_limit("*","cell_customer",
                                    $oid,[],10,0,"order_datetime","DESC",$ag_custome,"order_cutstome");

                    if(count($order_list)>0)
                    {
                        $appdata['Data']=$order_list[0];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="订单获取成功";
                        $appdata["Success"]=true;
                        $appdata["Status_Code"]="AOS200";
                    }
                    else
                    {
                        $appdata['Data']=[];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="无订单数据";
                        $appdata["Success"]=true;
                        $appdata["Status_Code"]="AOS201";
                    }

                }
                else
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="该代理商无客户";
                    $appdata["Success"]=true;
                    $appdata["Status_Code"]="AOS202";
                }




            }
            else
            {
                $order_list=$this->Custome_Model->table_seleRow_limit("*","cell_customer",['order_cutstome'=>$info['order_cutstome']],
                    $oid,[],10,0,"order_datetime","DESC");

                if(count($order_list)>0)
                {
                    $appdata['Data']=$order_list[0];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="订单获取成功";
                    $appdata["Success"]=true;
                    $appdata["Status_Code"]="AOS200";
                }
                else
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="无订单数据";
                    $appdata["Success"]=true;
                    $appdata["Status_Code"]="AOS201";
                }
            }


        }

        return $appdata;

        

    }


    /**
     * 获取客户订单列表
     */
    public function getCustomeOrderList($info=[])
    {
        $appdata=[];
        $oid=[];
        if(count($info)>0)
        {
            if($info['order_autoid']==0)
            {
                $oid=[];
            }
            else
            {
                $oid=['order_autoid <='=>$info['order_autoid']];
            }


            $order_list=$this->Custome_Model->table_seleRow_limit("*","cell_order",['order_cutstome'=>$info['order_cutstome']],
                $oid,[],10,0,"order_datetime","DESC");

            if(count($order_list)>0)
            {
                $appdata['Data']=$order_list[0];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="订单获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="COS200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无订单数据";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="COS201";
            }



        }

        return $appdata;
        
    }

    
    

    





}







