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
                $product_list[$i]['product_cover']="http://wdcells.fjspacecloud.com/public/prodcutimage/".$product_list[$i]['product_cover'];
                $pptfiles=explode(",",$product_list[$i]['product_ppt']);
                $pptfiles = array_map(function ($item){
                    return "http://wdcells..fjspacecloud.com/public/prodcutimage/".$item;
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
                $oid=['order_autoid <'=>$info['order_autoid']];
            }

            if($info['order_customer_name']=="")
            {
                //查询该代理商下的所有客户
                $ag_custome=$this->Custome_Model->table_seleRow('custome_id',"cell_customer",['custome_agent'=>$info['customer_agent']]);
                if(count($ag_custome)>0)
                {

                    $ag_custome = array_column($ag_custome, 'custome_id');
                    $order_list=$this->Custome_Model->table_seleRow_limit("*","cell_order",
                                    $oid,[],10,0,"order_datetime,order_id","DESC",$ag_custome,"order_customer");

                    if(count($order_list)>0)
                    {
                        $appdata['Data']=$order_list;
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



                //查询该客户是否是服务商客户
                $ag_custome=$this->Custome_Model->table_seleRow('custome_id',"cell_customer",['custome_agent'=>$info['customer_agent'],'custome_name'=>$info['order_customer_name']]);
                if(count($ag_custome)>0)
                {

                    if($info['order_autoid']==0)
                    {
                        $oid=[];
                    }
                    else
                    {
                        $oid=['order_autoid <'=>$info['order_autoid'],'order_customer'=>$ag_custome[0]['custome_id']];
                    }



                    $order_list=$this->Custome_Model->table_seleRow_limit("*","cell_order",
                        $oid,[],10,0,"order_datetime,order_id","DESC");

                    if(count($order_list)>0)
                    {
                        $appdata['Data']=$order_list;
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
                    $appdata["ErrorMessage"]="该客户的服务商不是您";
                    $appdata["Success"]=true;
                    $appdata["Status_Code"]="AOS204";
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
                $oid=['order_customer'=>$info['order_customer']];
            }
            else
            {
                $oid=['order_autoid <'=>$info['order_autoid'],'order_customer'=>$info['order_customer']];
            }


            $order_list=$this->Custome_Model->table_seleRow_limit("*","cell_order",
                $oid,[],10,0,"order_datetime,order_id","DESC");

            if(count($order_list)>0)
            {
                $appdata['Data']=$order_list;
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


    /**
     * 首次添加订单
     * @param array $info 添加订单信息
     * @return array
     */
    public function OneAddOrder($info=[])
    {
        $appdata=[];
        if(count($info)>0)
        {
            $info['order_datetime']=date('Y-m-d H:i:s');
            $info['order_id']=time().rand(1111,9999);
            $isAddtrue=$this->Custome_Model->table_addRow("cell_order",$info);
            if($isAddtrue>0)
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="订单添加成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="ODA200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="订单添加失败";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="ODA201";

            }

        }


        return $appdata;

    }


    /**
     * 获取预约记录
     * @param $info
     * @return array
     */
    public function getSubscribe($info)
    {
        $appdata=[];
        $oid=[];
        if(count($info)>0)
        {
            if($info['subscribe_id']==0)
            {
                $oid=['subscribe_created_by'=>$info['subscribe_created_by']];
            }
            else
            {
                $oid=['subscribe_id <='=>$info['subscribe_id'],'subscribe_created_by'=>$info['subscribe_created_by']];
            }


            $subscribe=$this->Custome_Model->table_seleRow_limit("*","cell_subscribe",
                $oid,[],10,0,"subscribe_created_time","DESC");

            if(count($subscribe)>0)
            {
                $appdata['Data']=$subscribe;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="预定信息获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="SUB200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无预定数据";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="SUB201";
            }



        }

        return $appdata;

        
    }

    /**
     * @param $info
     * @return array
     */
    public function getAdvice($info)
    {
        $appdata=[];
        $oid=[];
        if(count($info)>0)
        {
            if($info['advice_id']==0)
            {
                $oid=['advice_custome'=>$info['advice_custome']];
            }
            else
            {
                $oid=['advice_id <'=>$info['advice_id'],'advice_custome'=>$info['advice_custome']];
            }


            $Advice=$this->Custome_Model->table_seleRow_limit("*","cell_advice",
                $oid,[],10,0,"advice_created_time","DESC");

            if(count($Advice)>0)
            {
                $appdata['Data']=$Advice;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="预定信息获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="SUB200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无预定数据";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="SUB201";
            }



        }

        return $appdata;


    }

    /**
     * 添加投诉建议
     * @param array $info
     * @return array
     */
    public function AddAdvice($info=[])
    {
        $appdata=[];
        if(count($info)>0)
        {
            $info['advice_created_time']=date('Y-m-d H:i:s');
            //获取要分配的客服
            $service=$this->Custome_Model->table_seleRow('Userid',"base_user",['UserDept'=>$info['custome_deptid'],'UserPost'=>'HTY60a243c88b9003.00110708']);
            $info['advice_service']=$service[0]['Userid'];
            $info['advice_status']='待处理';
            $isAddtrue=$this->Custome_Model->table_addRow("cell_advice",$info);
            if($isAddtrue>0)
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="添加成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="ADDA200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="添加失败";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="ADDA201";

            }

        }


        return $appdata;

    }


    /**
     * 获取账号额度
     * @param $info
     * @return array
     */
    public function getAccount($info=[])
    {
        $appdata=[];
        if(count($info)>0)
        {

            //获取要分配的客服
            $service=$this->Custome_Model->table_seleRow('custome_balance',"cell_customer",['custome_id'=>$info['custome_id']]);

            if(count($service)>0)
            {
                $appdata['Data']=$service[0]['custome_balance'];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="查询成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="ACNT200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="查询成功";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="ACNT201";

            }

        }


        return $appdata;


    }


    /**
     * 获得充值历史记录
     * @param $info
     */
    public function getRechargeList($info=[])
    {
        $appdata=[];
        $oid=[];
        if(count($info)>0)
        {
            if($info['recharge_id']==0)
            {
                $oid=['recharge_custome'=>$info['recharge_custome']];
            }
            else
            {
                $oid=['recharge_id <='=>$info['recharge_id'],'recharge_custome'=>$info['recharge_custome']];
            }


            $Advice=$this->Custome_Model->table_seleRow_limit("*","call_racharge",
                $oid,[],10,0,"recharge_created_time","DESC");

            if(count($Advice)>0)
            {
                $appdata['Data']=$Advice;
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="充值记录获取成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="RCH200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="无充值数据";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="RCH201";
            }



        }

        return $appdata;

    }


    /**
     * 新增充值记录
     * @param array $info
     * @return array
     */
    public function addRecharge($info=[])
    {
        $appdata=[];
        if(count($info)>0)
        {
            $info['recharge_created_time']=date('Y-m-d H:i:s');
            $info['recharge_status']="汇款中";
            $isAddtrue=$this->Custome_Model->table_addRow("call_racharge",$info);
            if($isAddtrue>0)
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="添加成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="ARECH200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="添加失败";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="ARECH201";

            }

        }


        return $appdata;

    }


    /**
     * 将汇款中的状态改为已汇款
     * @param array $info
     * @return array
     */
    public function modifyRecharge($info=[])
    {
        $appdata=[];
        if(count($info)>0)
        {

            $service=$this->Custome_Model->table_seleRow('recharge_status',"call_racharge",['recharge_id'=>$info['recharge_id']]);

            if(count($service)>0)
            {
                if($service[0]['recharge_status']=="汇款中")
                {
                    $mod=['recharge_status'=>'已汇款'];
                    $mod['recharge_updated_time']=date('Y-m-d H:i:s');
                    $isAddtrue=$this->Custome_Model->table_updateRow("call_racharge",$mod,['recharge_id'=>$info['recharge_id']]);
                    if($isAddtrue>0)
                    {
                        $appdata['Data']=[];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="修改成功";
                        $appdata["Success"]=true;
                        $appdata["Status_Code"]="ARECH200";
                    }
                    else
                    {
                        $appdata['Data']=[];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="修改失败";
                        $appdata["Success"]=false;
                        $appdata["Status_Code"]="ARECH201";

                    }

                }
                else
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="状态不是汇款中，无法改变状态";
                    $appdata["Success"]=false;
                    $appdata["Status_Code"]="ARECH200";
                }
            }


        }


        return $appdata;

    }



    /**
     * 上传成功体检报告修改数据表信息
     * @param array $info
     * @return array
     */
    public function modifyHealth($orderid)
    {
        $appdata=[];
        if($orderid!="")
        {
            $mod['order_health']=$orderid;
            $isAddtrue=$this->Custome_Model->table_updateRow("cell_order",$mod,['order_id'=>$orderid]);
            if($isAddtrue>0)
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="修改成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="MDH200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="修改失败";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="MDH201";

            }

        }
        else
        {
            $appdata['Data']=[];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="参数接收失败";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="MDH202";
        }


        return $appdata;

    }


    public function delAdvice($info)
    {
        $appdata=[];
        if(count($info)>0)
        {

            $ag_custome=$this->Custome_Model->table_seleRow('recharge_status',"call_racharge",['recharge_id'=>$info['recharge_id']]);
            if(count($ag_custome)>0)
            {
                if($ag_custome[0]['recharge_status']=="汇款中")
                {
                    $isAddtrue=$this->Custome_Model->table_del("call_racharge",['recharge_id'=>$info['recharge_id']]);
                    if($isAddtrue>0)
                    {
                        $appdata['Data']=[];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="删除成功";
                        $appdata["Success"]=true;
                        $appdata["Status_Code"]="DRCH200";
                    }
                    else
                    {
                        $appdata['Data']=[];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="删除失败";
                        $appdata["Success"]=false;
                        $appdata["Status_Code"]="DRCH201";

                    }
                }
                else
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="订单状态不是汇款中";
                    $appdata["Success"]=false;
                    $appdata["Status_Code"]="DRCH202";

                }
            }



        }
        else
        {
            $appdata['Data']=[];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="参数接收失败";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="DRCH203";
        }


        return $appdata;

    }

    public function delOrder($info)
    {
        $appdata=[];
        if(count($info)>0)
        {

            $ag_custome=$this->Custome_Model->table_seleRow('order_statue',"cell_order",['order_id'=>$info['order_id']]);
            if(count($ag_custome)>0)
            {
                if($ag_custome[0]['order_statue']!="进行中" || $ag_custome[0]['order_statue']!="已完成" || $ag_custome[0]['order_statue']!="待实名")
                {
                    $isAddtrue=$this->Custome_Model->table_del("cell_order",['order_id'=>$info['order_id']]);
                    if($isAddtrue>0)
                    {
                        $appdata['Data']=[];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="删除成功";
                        $appdata["Success"]=true;
                        $appdata["Status_Code"]="DRCH200";
                    }
                    else
                    {
                        $appdata['Data']=[];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="删除失败";
                        $appdata["Success"]=false;
                        $appdata["Status_Code"]="DRCH201";

                    }
                }
                else
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="订单状态不对";
                    $appdata["Success"]=false;
                    $appdata["Status_Code"]="DRCH202";

                }
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="订单不存在";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="DRCH202";

            }



        }
        else
        {
            $appdata['Data']=[];
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="参数接收失败";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="DRCH203";
        }


        return $appdata;

    }

    /**
     * 身份证背面上传
     * @param array $info
     * @return array
     */
    public function updateBackup($order_id,$cardback)
    {
        $appdata=[];
        if($order_id && $cardback)
        {

            $isAddtrue=$this->Custome_Model->table_updateRow("cell_order",['order_CardSaveBack'=>$cardback],['order_id'=>$order_id]);
            if($isAddtrue>0)
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="修改成功";
                $appdata["Success"]=true;
                $appdata["Status_Code"]="ARECH200";
            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="修改失败";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="ARECH201";

            }


        }


        return $appdata;

    }


    
    

    





}







