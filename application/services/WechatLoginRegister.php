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

	}

    public function wechatLogin($info)
    {
        $assdata=[];
        if($info['code']!=""){


            //判断是代理商还是客户登陆，不同小程序
            if($info['login_type']==1)
            {
                $appid = "wx71b2317f2015c429";
                $secret = "c94eb5b0801ee5d5046a6ff84069a2ac";
            }
            else{
                $appid = "wx71b2317f2015c429";
                $secret = "c94eb5b0801ee5d5046a6ff84069a2ac";
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
            $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code={$info['code']}&grant_type=authorization_code";
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


            //第三步:根据全局access_token和openid查询用户信息
            $access_token = $token["access_token"];
            $openid = $oauth2['openid'];
            $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
            $userinfo = $this->getJson($get_user_info_url);
            if(array_key_exists("errcode", $userinfo)){
                $assdata['Data']='';
                $assdata["ErrorCode"]="user-error";
                $assdata["ErrorMessage"]=$userinfo['errmsg'];
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WL203";
                log_message("error",$userinfo['errmsg']);
                return $assdata;


            }
            if(count($userinfo)>0){

                $info['clien_nickname']=array_key_exists('nickname',$userinfo)?$userinfo['nickname']:'';
                $info['clien_sex']=array_key_exists('sex',$userinfo)?$userinfo['sex']:'';
                $info['clien_openid']=$openid;
                $info['clien_city']=array_key_exists('city',$userinfo)?$userinfo['city']:'';
                $info['clien_photo']=array_key_exists('headimgurl',$userinfo)?$userinfo['headimgurl']:'';


            }
            else{
                $assdata['Data']='';
                $assdata["ErrorCode"]="user-error";
                $assdata["ErrorMessage"]="获取用户信息失败";
                $assdata["Success"]=false;
                $assdata["Status_Code"]="WL204";
                return $userinfo;

            }

        }

        //判断是代理商还是客户登陆，不同小程序
        if($info['login_type']==1)
        {

            $clien_info = $this->SysModel->table_seleRow("*", 'cell_customer', array('custome_openid' => $info['clien_openid']));
        }
        else{

            $clien_info = $this->SysModel->table_seleRow("*", 'base_user', array('openid' => $info['clien_openid']));
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

        if(count($info)>0){

            if($info['custome_agent']=="")
            {
                $info['custome_agent']="1";//没有服务商绑定的，默认1号公司服务商
            }


            $isok=$this->Custome_Model->table_addRow('cell_customer',$info);


            if($isok>=0){
                $assdata['Data']=[];
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








}







