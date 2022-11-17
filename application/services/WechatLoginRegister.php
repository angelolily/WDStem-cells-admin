<?php


/**
 * Class WechatLoginRegist
 * 微信登陆注册接口
 */
class WechatLoginRegister extends HTY_service
{
	/**
	 * Dept constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Custome_Model');
        $this->load->helper('tool');
        $this->load->library('encryption');
	}


    //get获取JSON
    private function getJson($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }


    //APP登陆

    public function appLogin($info){

        $assdata=[];

	    if(count($info)>0)
        {

            //判断是代理商还是客户登陆，不同App
            if($info['login_type']==1)
            {

                $clien_info = $this->Custome_Model->table_seleRow("*", 'cell_customer', array('custome_openid' => $info['clien_openid']));
            }
            else{

                $clien_info = $this->Custome_Model->table_seleRow("*", 'base_user', array('openid' => $info['clien_openid']));
            }

            if(count($clien_info)>0)
            {
                $pwd = $this->encryption->decrypt($clien_info[0]['UserPassword']);
                if($pwd==$info['UserPassword'])
                {
                    $assdata["Data"]='';
                    $assdata["ErrorCode"]="";
                    $assdata["ErrorMessage"]="登陆成功";
                    $assdata["Success"]=true;
                    $assdata["Status_Code"]="AL200";

                }
                else{
                    $assdata["Data"]='';
                    $assdata["ErrorCode"]="";
                    $assdata["ErrorMessage"]="登陆失败，密码不符合";
                    $assdata["Success"]=false;
                    $assdata["Status_Code"]="AL201";

                }

            }
            else{

                $assdata["Data"]='';
                $assdata["ErrorCode"]="user-error";
                $assdata["ErrorMessage"]="登陆失败，用户不存在";
                $assdata["Success"]=false;
                $assdata["Status_Code"]="AL202";



            }



            return $assdata;



        }


    }

    //微信小程序登陆
    public function wechatLogin($info)
    {
        $assdata=[];
        if($info['code']!=""){


            //判断是代理商还是客户登陆，不同小程序
            if($info['login_type']==1)
            {
                $appid = "wx49967b15e5550331";
                $secret = "6fd4fc295a92bb3ab3f9c69066d66b3c";
            }
            else{
                $appid = "wx49967b15e5550331";
                $secret = "6fd4fc295a92bb3ab3f9c69066d66b3c";
            }


            //第一步:取全局access_token

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
            $token = $this->getJson($url);

            if(array_key_exists("errcode", $token)){
                $assdata["Data"]='';
                $assdata["ErrorCode"]="user-error";
                $assdata["ErrorMessage"]=$token['errmsg'];
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WL201";
                header("HTTP/1.1 200 Created");
                header("Content-type: application/json");
                log_message("error",$token['errmsg']);
                return $assdata;


            }

            //第二步:取得openid
            $oauth2Url = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code={$info['code']}&grant_type=authorization_code";
            $oauth2 = $this->getJson($oauth2Url);

            if(array_key_exists("errcode", $oauth2)){
                $assdata["Data"]='';
                $assdata["ErrorCode"]="user-error";
                $assdata["ErrorMessage"]=$oauth2['errmsg'];
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WL202";
                log_message("error",$oauth2['errmsg']);
                return $assdata;


            }



            $info['clien_openid'] = $oauth2['openid'];


        }

        //判断是代理商还是客户登陆，不同小程序
        if($info['login_type']==1)
        {

            $clien_info = $this->Custome_Model->table_seleRow("*", 'cell_customer', array('custome_openid' => $info['clien_openid']));
        }
        else{

            $clien_info = $this->Custome_Model->table_seleRow("*", 'base_user', array('openid' => $info['clien_openid']));
        }

        if(count($clien_info)>0)
        {

            $assdata['Data']=$clien_info[0];
            $assdata["ErrorCode"]="";
            $assdata["ErrorMessage"]="登陆成功";
            $assdata["Success"]=true;
            $assdata["Status_Code"]="WL200";



        }
        else{
            $assdata['Data']=$info;
            $assdata["ErrorCode"]="";
            $assdata["ErrorMessage"]="登陆失败，请注册";
            $assdata["Success"]=false;
            $assdata["Status_Code"]="WL205";

        }

        return $assdata;


    }

    //微信客户注册
    public function wechatCustomerRegist($info)
    {

        $assdata=[];
        $customeinfo=[];
        $isAddUpdate=0;
        $cusid=1;

        if(count($info)>0){

            if($info['custome_agent']=="")
            {
                //查询是否有报备过客户
                $custome_id=$this->Custome_Model->table_seleRow('custome_id,custome_agent',"cell_customer",['custome_phone'=>$info['custome_phone']]);

                if(count($custome_id)>0)
                {
                    $info['custome_agent']=$custome_id[0]['custome_agent'];
                    $isAddUpdate=1;
                    $cusid=$custome_id[0]['custome_id'];
                }
                else
                {
                    $info['custome_agent']="13325289965";//没有服务商绑定的，默认1号公司服务商
                }



            }

            //查询代理商部门id
            $dept=$this->Custome_Model->table_seleRow('UserDept',"base_user",['Mobile'=>$info['custome_agent']]);

            if(count($dept)>0)
            {
                $info['custome_deptid']=$dept[0]['UserDept'];
                //查询部门客服
                $Mobile=$this->Custome_Model->table_seleRow('Mobile',"base_user",['UserDept'=>$info['custome_deptid'],'UserRole'=>'HTY60a237e70f7023.01140607']);
                if(count($Mobile)>0)
                {
                    $info['custome_serivce']=$Mobile[0]['Mobile'];
                }
                else
                {
                    $info['custome_serivce']="13325289965";
                }
                $info['custome_created_time']=date('Y-m-d H:i');
                if($isAddUpdate==1)
                {
                    $isok=$this->Custome_Model->table_updateRow('cell_customer',$info,['custome_id'=>$cusid]);
                }
                else
                {
                    $info['UserPassword']=$pwd = $this->encryption->encrypt($info['UserPassword']);
                    $isok=$this->Custome_Model->table_addRow('cell_customer',$info);
                }

            }
            else{
                $isok=-1;
            }



            if($isok>=0){

                $customeinfo=$this->Custome_Model->table_seleRow('*',"cell_customer",['custome_openid'=>$info['custome_openid']]);
                $assdata['Data']=$customeinfo[0];
                $assdata["ErrorCode"]="";
                $assdata["ErrorMessage"]="插入成功";
                $assdata["Success"]=true;
                $assdata["Status_Code"]="WR200";

            }
            else
            {
                $assdata['Data']=[];
                $assdata["ErrorCode"]="";
                $assdata["ErrorMessage"]="插入失败";
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WR202";

            }



        }
        else
        {
            $assdata['Data']=[];
            $assdata["ErrorCode"]="";
            $assdata["ErrorMessage"]="无接收数据";
            $assdata["Success"]=false;
            $assdata["Status_Code"]="WR202";
        }


        return $assdata;



    }



    //微信代理商注册
    public function wechatAgentRegist($info){

        $assdata=[];

	    if(count($info)>0)
        {

            $savePath="./public/agentAvatar";
            //以代理商手机号作为头像保存文件名
            $filename=$info['Mobile'];
            $isSave=base64_file_content_type($info['Avatar'],$savePath,$filename);
            if($isSave!="")
            {
                //图片保存成功
                $info['Avatar']="https://wdcells.fjspacecloud.com/wdstem-cells-admin/public/agentAvatar/".$filename.".jpg";
                $info['UserStatus']=1;
                $info['Userid']=uniqid("HTY", 4);

                $isok=$this->Custome_Model->table_addRow('base_user',$info);


                if($isok>=0){
                    $assdata['Data']=$info;
                    $assdata["ErrorCode"]="";
                    $assdata["ErrorMessage"]="插入成功";
                    $assdata["Success"]=true;
                    $assdata["Status_Code"]="WAR200";

                }
                else
                {
                    $assdata['Data']=[];
                    $assdata["ErrorCode"]="";
                    $assdata["ErrorMessage"]="插入失败";
                    $assdata["Success"]=false;
                    $assdata["Status_Code"]="WAR202";

                }


            }
            else
            {
                $assdata['Data']=[];
                $assdata["ErrorCode"]="";
                $assdata["ErrorMessage"]="图片保存失败";
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WAR203";

            }





        }
        else
        {
            $assdata['Data']=[];
            $assdata["ErrorCode"]="";
            $assdata["ErrorMessage"]="无接收数据";
            $assdata["Success"]=false;
            $assdata["Status_Code"]="WR202";
        }






        return $assdata;


    }











}







