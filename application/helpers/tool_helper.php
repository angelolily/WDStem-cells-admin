<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//关联数组删除key
function bykey_reitem($arr, $key){
	if(!array_key_exists($key, $arr)){
		return $arr;
	}
	$keys = array_keys($arr);
	$index = array_search($key, $keys);
	if($index !== FALSE){
		array_splice($arr, $index, 1);
	}
	return $arr;

}

function build_resulArr($code,$success,$msg,$data)
{
	$resulArr['code']=$code;
	$resulArr['success'] = $success;
	$resulArr['msg'] = $msg;
	$resulArr['data'] =$data;

	return $resulArr;

}
function arrayGbkToUtf8($val=[])
{
	$result=[];
	foreach ($val as $row)
	{
		$row=iconv("GBK","UTF-8",$row);
		array_push($result,$row);
	}
	return $result;
}

function http_data($statue,$HttpData=[],$CI)
{
	$CI->output
		->set_header('access-control-allow-headers: Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With')
		->set_header('access-control-allow-methods: GET, POST, PUT, DELETE, HEAD, OPTIONS')
		->set_header('access-control-allow-credentials: true')
		->set_header('access-control-allow-origin: *')
		->set_header('X-Powered-By: WAF/2.0')
		->set_status_header($statue)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($HttpData))
		->_display();
	exit;
}
function existsArrayKey($keys,$arr=[])
{

	$arrkeys=[];
	$errorKeys="";
	$arrkeys=explode(",",$keys);
	foreach ($arrkeys as $row)
	{
		if(!(array_key_exists($row,$arr))){

			$errorKeys.=",".$row;

		}
	}

	return $errorKeys;

}
function build_resultArr($code,$success,$status_code,$msg=null,$data=[])
{
    $resultArr['ErrorCode']=$code;
    $resultArr['Success'] = $success;
    $resultArr['Status_Code'] = $status_code;
    $resultArr['ErrorMessage'] = $msg;
    $resultArr['Data'] =$data;

    return $resultArr;

}

//function Data_rights($keys)//所有搜索接口的数据权限限制,返回值是用户的数据搜索限制
//{
//	if($keys['DataScope']=="1"){
//		$item['phone']=$keys['Mobile'];
//		return $item;
//	}
//	if($keys['DataScope']=="2"){
//
//	}
//	if($keys['DataScope']=="3"){
//
//	}
//	if($keys['DataScope']=="4"){
//
//	}
//	if($keys['DataScope']=="5"){
//
//	}
//
//
//
//}

