<?php
require_once 'vendor/autoload.php';
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Faceid\V20180301\FaceidClient;
use TencentCloud\Faceid\V20180301\Models\IdCardOCRVerificationRequest;
use TencentCloud\Faceid\V20180301\Models\CheckIdCardInformationRequest;
use TencentCloud\Faceid\V20180301\Models\ImageRecognitionRequest;

class CheckIdCardInformation extends HTY_service
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('tool');
        $this->load->model('Custome_Model');
    }

    /**
     * 身份证真伪--公安部验证
     * @param $cardpath
     * @return array
     */
    public function isIdCard($cardpath,$applyname,$order_id,$custome_id)
    {
        $appdata=[];
        if(file_exists($cardpath))
        {


            //判断图片文件是否超过1M,进行压缩
            $content = file_get_contents($cardpath);
            $size = strlen($content);
            $size=$size/1024;
            $newFileName=rand(100000,999999);
            $des="./public/idCard/".$order_id."/".$newFileName.".jpg";
            if($size>900)
            {
                $this->imageSize($cardpath,$des);
            }
            else{
                $des=$cardpath;
            }

            try {

                $cred = new Credential("AKIDd5HzUjjiaFfrrdJRfkaBWoshgePvlEVZ", "yiy1p321nLg3JL9i3A2EQEn6AGDvPs77");
                $httpProfile = new HttpProfile();
                $httpProfile->setEndpoint("faceid.tencentcloudapi.com");

                $clientProfile = new ClientProfile();
                $clientProfile->setHttpProfile($httpProfile);
                $client = new FaceidClient($cred, "", $clientProfile);

                $req = new IdCardOCRVerificationRequest();

                $params = array(
                    "ImageBase64" => fileToBase64($des)
                );
                $req->fromJsonString(json_encode($params));

                $resp = $client->IdCardOCRVerification($req);


                $numName=json_decode($resp->toJsonString(),true);
                //身份证信息一致后，验证照片是否一致
                if($numName['Result']=="0")
                {



                    $reqImg = new CheckIdCardInformationRequest();

                    $params = array(
                        "ImageBase64" => fileToBase64($des)
                    );
                    $reqImg->fromJsonString(json_encode($params));

                    $reqImg = $client->CheckIdCardInformation($req);
                    $regImgarra=json_decode($reqImg->toJsonString(),true);
                    if($regImgarra['Sim']>70 && ($regImgarra['Name']==$applyname))
                    {
                        //身份证上照片一致后，更新订单表
                        $order_idInfo['order_idcard']=$regImgarra['IdNum'];
                        $order_idInfo['order_idAddress']=$regImgarra['Address'];
                        $order_idInfo['order_idName']=$regImgarra['Name'];
                        $order_idInfo['order_idSex']=$regImgarra['Sex'];
                        $order_idInfo['order_Nation']=$regImgarra['Nation'];
                        $order_idInfo['order_Birth']=$regImgarra['Birth'];
                        $order_idInfo['order_CardSave']=$cardpath;
                        $isAddtrue=$this->Custome_Model->table_updateRow("cell_order",$order_idInfo,['order_id'=>$order_id]);
                        if($isAddtrue>=0)
                        {
                            if($custome_id!="")
                            {
                                //如果是客户第一次实名制，要同步更新客户表
                                $custome['custome_idnum']=$regImgarra['IdNum'];
                                $custome['custome_idaddress']=$regImgarra['Address'];
                                $custome['custome_idSex']=$regImgarra['Sex'];
                                $custome['custome_Nation']=$regImgarra['Nation'];
                                $custome['custome_birthday']=$regImgarra['Birth'];
                                $custome['custome_cardPath']=$cardpath;
                                $isAddtrue=$this->Custome_Model->table_updateRow("cell_customer",$custome,['custome_id'=>$custome_id]);
                            }


                            if($isAddtrue>=0)
                            {
                                $appdata['Data']=$order_idInfo;
                                $appdata["ErrorCode"]="";
                                $appdata["ErrorMessage"]="身份证验证更新成功";
                                $appdata["Success"]=true;
                                $appdata["Status_Code"]="CCA200";

                            }
                            else
                            {
                                $appdata['Data']=[];
                                $appdata["ErrorCode"]="";
                                $appdata["ErrorMessage"]="身份证验证更新失败";
                                $appdata["Success"]=false;
                                $appdata["Status_Code"]="CCA208";
                            }


                        }
                        else
                        {
                            $appdata['Data']=[];
                            $appdata["ErrorCode"]="";
                            $appdata["ErrorMessage"]="身份证验证更新失败";
                            $appdata["Success"]=false;
                            $appdata["Status_Code"]="CCA201";
                        }

                    }
                    else
                    {
                        $appdata['Data']=[];
                        $appdata["ErrorCode"]="";
                        $appdata["ErrorMessage"]="身份证验证失败,照片不匹配";
                        $appdata["Success"]=false;
                        $appdata["Status_Code"]="CCA202";

                    }

                }
                else
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="身份证信息不一致";
                    $appdata["ErrorMessage"]="";
                    $appdata["Success"]=false;
                    $appdata["Status_Code"]="CCA204";

                }

            }
            catch(TencentCloudSDKException $e) {
                $appdata['Data']=$e->getMessage();
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="CCA205";
            }

        }


        return $appdata;
        
    }


    private function imageSize($source,$destination)
    {

        $image = imagecreatefromjpeg($source);  // 加载资源
        if (isset($image) && is_resource($image)) {
            imagejpeg($image, $destination, 40);   // 根据$quality比例进行
            imagedestroy($image);
        }

    }

    public function isFacetrue($facepath,$name,$idcard,$custome_id,$order_id)
    {
        try {


            //判断图片文件是否超过1M,进行压缩
            $content = file_get_contents($facepath);
            $size = strlen($content);
            $size=$size/1024;
            $newFileName=rand(10000,99999);
            $des="./public/idCard/".$order_id."/".$newFileName.".jpg";
            if($size>900)
            {
                $this->imageSize($facepath,$des);
            }
            else{
                $des=$facepath;
            }


            $cred = new Credential("AKIDd5HzUjjiaFfrrdJRfkaBWoshgePvlEVZ", "yiy1p321nLg3JL9i3A2EQEn6AGDvPs77");
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("faceid.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new FaceidClient($cred, "", $clientProfile);

            $req = new ImageRecognitionRequest();

            $params = array(
                "IdCard" => $idcard,
                "Name" => $name,
                "ImageBase64" => fileToBase64($des)
            );
            $req->fromJsonString(json_encode($params));

            $resp = $client->ImageRecognition($req);


            $numName=json_decode($resp->toJsonString(),true);
            if($numName['Sim']>70)
            {
                if($custome_id!="")
                {
                    //同步更新客户信息表，已通过实名
                    $isAddtrue=$this->Custome_Model->table_updateRow("cell_customer",['custome_isreal'=>'1'],['custome_id'=>$custome_id]);

                }
                //更新订单状态
                $isAddtrue=$this->Custome_Model->table_updateRow("cell_order",['order_statue'=>'进行中'],['order_id'=>$order_id]);
                if($isAddtrue>=0)
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="身份证照片匹配成功";
                    $appdata["Success"]=true;
                    $appdata["Status_Code"]="FAE200";
                }
                else
                {
                    $appdata['Data']=[];
                    $appdata["ErrorCode"]="";
                    $appdata["ErrorMessage"]="身份证照片匹配成功,但数据更新失败";
                    $appdata["Success"]=false;
                    $appdata["Status_Code"]="FAE201";
                }

            }
            else
            {
                $appdata['Data']=[];
                $appdata["ErrorCode"]="";
                $appdata["ErrorMessage"]="身份证照片匹配不上";
                $appdata["Success"]=false;
                $appdata["Status_Code"]="FAE202";
            }



        }
        catch(TencentCloudSDKException $e) {
            $appdata['Data']=$e->getMessage();
            $appdata["ErrorCode"]="";
            $appdata["ErrorMessage"]="";
            $appdata["Success"]=false;
            $appdata["Status_Code"]="FAE202";
        }

        return $appdata;
    }






}