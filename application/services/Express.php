<?php


class Express extends HTY_service
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('tool');
    }


    public function getExpressinfo($num)
    {
        $appdata=[];
        // 云市场分配的密钥Id
        $secretId = 'AKIDev8S7xbdkgdGpzb3R3kBzlwavn4eApcWQ0tv';
        // 云市场分配的密钥Key
        $secretKey = 'j753yOF5i073nwr6bGfn4lyxWmwE7UCqunUqh5Cp';
        $source = 'market';

        // 签名
        $datetime = gmdate('D, d M Y H:i:s T');
        $signStr = sprintf("x-date: %s\nx-source: %s", $datetime, $source);
        $sign = base64_encode(hash_hmac('sha1', $signStr, $secretKey, true));
        $auth = sprintf('hmac id="%s", algorithm="hmac-sha1", headers="x-date x-source", signature="%s"', $secretId, $sign);

        // 请求方法
        $method = 'POST';
        // 请求头
        $headers = array(
            'X-Source' => $source,
            'X-Date' => $datetime,
            'Authorization' => $auth,
        );
        // 查询参数
        $queryParams = array (
            'express_id' => $num,
            'express_name' => '',
        );
        // body参数（POST方法下）
        $bodyParams = array (
        );
        // url参数拼接
        $url = 'https://service-m1lhix6w-1253285064.gz.apigw.tencentcs.com/release/qxt_express/';
        if (count($queryParams) > 0) {
            $url .= '?' . http_build_query($queryParams);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function ($v, $k) {
            return $k . ': ' . $v;
        }, array_values($headers), array_keys($headers)));
        if (in_array($method, array('POST', 'PUT', 'PATCH'), true)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyParams));
        }

        $data = curl_exec($ch);
        if (curl_errno($ch)) {

            $appdata['Data']=curl_error($ch);
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="";
            $appdata["Success"]=true;
            $appdata["Status_Code"]="CAD200";
        } else {
            $appdata['Data']=json_decode($data,true);
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="";
            $appdata["Success"]=true;
            $appdata["Status_Code"]="CAD200";

        }
        curl_close($ch);

        return $appdata;

    }

}