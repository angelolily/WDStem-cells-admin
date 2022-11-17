<?php
class SignControl extends CI_Controller{
    private $receive_data;
    public function __construct(){
        parent::__construct();
        $this->load->helper('tool');
        $this->load->service('Sign');
        $receive = file_get_contents('php://input');
        $this->receive_data = json_decode($receive, true);
    }

    /**
     *获取用户信息与当前订单信息生成协议文件
     */
    public function setProtocolModels(){
        $base_url='http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/index.php')+1);
        $res = $this->sign->getOrderInf($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('SPM001', false, 0,'获取订单信息失败', null );
            http_data(200, $resultArr, $this);
        }
        $time = time();
        $order_inf = $res[0];
        $price_c = shift_chinese($order_inf['order_price']);
        $file_path = BASE_PATH . 'public/protocol/protocol/' . $time;
        $out_path = BASE_PATH . 'public/protocol/protocol/' . $time . '.docx';
        $out_path_pdf = BASE_PATH . 'public/protocol/protocol/' . $time . '.pdf';
        $res_path_pdf = $base_url . 'public/protocol/protocol/' . $time . '.pdf';
        switch ($order_inf['order_type']) {
            case '牙髓干细胞储存':
                $doc_path = BASE_PATH . '/public/protocol/models/ysgxbcc.docx';
                $value = array(
                    'name'=>$order_inf['order_customer_name'],
                    'date'=>date('Y年m月d日',strtotime("+".$order_inf['order_format'].'year')),
                    'now'=>date('Y年m月d日'),
                    'year' => $order_inf['order_format'],
                    'price' => $order_inf['order_price'],
                    'totalpb' => $price_c
                );
                break;
            case '牙髓干细胞应用':
                $doc_path = BASE_PATH . '/public/protocol/models/ysgxbyy.docx';
                $value = array(
                    'now'=>date('Y年m月d日'),
                );
                break;
            case '免疫干细胞储存':
                $doc_path = BASE_PATH . '/public/protocol/models/myxbcc.docx';
                $value = array(
                    'name'=>$order_inf['order_customer_name'],
                    'date'=>date('Y年m月d日',strtotime("+".$order_inf['order_format'].'year')),
                    'now'=>date('Y年m月d日'),
                    'year' => $order_inf['order_format'],
                    'price' => $order_inf['order_price'],
                    'totalpb' => $price_c
                );
                break;
            case '免疫干细胞应用':
                $doc_path = BASE_PATH . '/public/protocol/models/myxbyy.docx';
                $value = array(
                    'now'=>date('Y年m月d日')
                );
                break;
        }
        setWordBuild($doc_path, $value, $out_path);
        $res_to_pdf = docToPdf($out_path,$out_path_pdf);
        if($res_to_pdf['success'] === 1){
            $resultArr = build_resultArr('SPM002', false, 0,'获取协议失败', null);
            http_data(200, $resultArr, $this);
        }
        $data = [$res_path_pdf,date('Y年m月d日'),$file_path];
        $resultArr = build_resultArr('SPM000', true, 0,'获取协议信息成功', $data);
        http_data(200, $resultArr, $this);
    }

    /**
     *生成签名图片并合并到协议文件中
     */
    public function sign(){
        $time = time();
        $path = $this->input->post('path');
        $file = $_FILES['sign'];
        $img_url = BASE_PATH . 'public/protocol/sign/'.$time.'.jpg';
        $source =imagecreatefromjpeg($file["tmp_name"]);
        $rotate = imagerotate($source,-270, 0);
        imagejpeg($rotate,$img_url);
        $values = array();
        $doc_path = $path . '.docx';
        $out_path_pdf = BASE_PATH . 'public/protocol/protocol/' . $time . '.pdf';
        setWord($doc_path,$values,'sign',$doc_path,$img_url);
        $res_to_pdf = docToPdf($doc_path,$out_path_pdf);
        if($res_to_pdf['success'] === 1){
            $resultArr = build_resultArr('S002', false, 0,'获取协议失败', null);
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('S000', true, 0,'存储签名成功', $time);
        http_data(200, $resultArr, $this);
    }

    /**
     *存储协议文件到订单表
     */
    public function setSign(){
        $res = $this->sign->setSign($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('SS001', false, 0,'存储协议失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('SS000', true, 0,'存储协议成功', null );
        http_data(200, $resultArr, $this);
    }

    /**
     *获取订单列表
     */
    public function getProtocolList(){
        $res = $this->sign->getProtocolList($this->receive_data);
        if (!$res) {
            $resultArr = build_resultArr('GPL001', false, 0,'获取协议失败', null );
            http_data(200, $resultArr, $this);
        }
        $resultArr = build_resultArr('GPL000', true, 0,'获取协议成功', $res );
        http_data(200, $resultArr, $this);
    }

    public function test(){
        $file = $_FILES['sign'];
        $img_sign = $file;
        $resultArr = build_resultArr('GC000', true, 0,'获取商品封面成功', null );
        http_data(200, $resultArr, $this);
    }
}