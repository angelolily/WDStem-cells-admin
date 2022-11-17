<?php


class CommodityControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Commodity');
		$this->load->helper('tool');
		$this->load->service('HtyJwt');
		$receiveArr = file_get_contents('php://input');
		$this->OldDataArr = json_decode($receiveArr, true);

	}


	/**
	 * Notes:前置验证，将用户信息与数据分离
	 * User: lchangelo
	 * DateTime: 2020/12/24 14:39
	 */
	private function hedVerify($keys="")
	{

		if ($this->OldDataArr) {
			if (count($this->OldDataArr) > 0) {
				if($keys!="")
				{
					$errorKey=existsArrayKey($keys,$this->OldDataArr);
					if($errorKey=="")
					{
						$this->userArr['Mobile'] = $this->OldDataArr['phone'];
					}
					else
					{
						$resulArr = build_resulArr('S003', false, '参数缺失', []);
						http_data(200, $resulArr, $this);
					}
				}
				$this->dataArr = bykey_reitem($this->OldDataArr, 'phone');
				$this->dataArr = bykey_reitem($this->dataArr, 'timestamp');
				$this->dataArr = bykey_reitem($this->dataArr, 'signature');
			} else {
				$resulArr = build_resulArr('S002', false, '无接收', []);
				http_data(200, $resulArr, $this);
			}
		} else {
			$resulArr = build_resulArr('S002', false, '无接收', []);
			http_data(200, $resulArr, $this);

		}
	}


	/**
	 * Notes:岗位新增记录
	 * User: ljx
	 * DateTime: 2020/12/31 10：40
	 */
	public function newRow()
	{

		$keys="product_name,product_type,product_price,product_lowPrice,product_format,product_weights,product_details,product_ppt,product_cover,product_describe";
		$this->hedVerify($keys);
//		$this->hedVerify();
		$resultNum = $this->commodity->addData($this->dataArr, $this->userArr['Mobile']);
		if (count($resultNum )> 0) {
			$resulArr = build_resulArr('D000', true, '插入成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D002', false, '插入失败', []);
			http_data(200, $resulArr, $this);
		}


	}

    /**
     * Notes:参数新增记录
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function Uploadpic()
    {
//		$this->userArr['Mobile']="17659059578";
        $result = $this->commodity->manyimageupload($this->dataArr);
        if (count($result )> 0) {
            $resulArr = build_resulArr('D000', true, '导入成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '导入失败', []);
            http_data(200, $resulArr, $this);
        }
    }


    /**
     * Notes:参数新增记录
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function delpic()
    {
        $this->hedVerify();
//		$this->userArr['Mobile']="17659059578";
        $result = $this->commodity->delallimgfile($this->dataArr);
        if (count($result )> 0) {
            $resulArr = build_resulArr('D000', true, '删除成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '删除失败', []);
            http_data(200, $resulArr, $this);
        }
    }
    /**
     * Notes:参数新增记录
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function findpic()
    {
        $this->hedVerify();//前置验证
//		$this->userArr['Mobile']="17659059578";
        $result = $this->commodity->getimagefilename($this->dataArr);
        if (count($result)> 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }
    }
    /**
     * Notes:参数新增记录
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function Uploaddetail()
    {
//		$this->userArr['Mobile']="17659059578";
        $result = $this->commodity->imageuploaddetail($this->dataArr);
        if (count($result )> 0) {
            $resulArr = build_resulArr('D000', true, '导入成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '导入失败', []);
            http_data(200, $resulArr, $this);
        }
    }

    /**
     * Notes:参数新增记录
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function finddetail()
    {
        $this->hedVerify();//前置验证
        $result = $this->commodity->getimagedetail($this->dataArr);
        if (count($result)> 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }
    }


    /**
     * Notes:参数新增记录
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function Uploadcover()
    {
//		$this->userArr['Mobile']="17659059578";
        $result = $this->commodity->imageuploadcover($this->dataArr);
        if (count($result )> 0) {
            $resulArr = build_resulArr('D000', true, '导入成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '导入失败', []);
            http_data(200, $resulArr, $this);
        }
    }

    /**
     * Notes:参数新增记录
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function findcover()
    {
        $this->hedVerify();//前置验证
        $result = $this->commodity->getimagecover($this->dataArr);
        if (count($result)> 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }
    }
	/**
	 * Notes:获取岗位信息
	 * User: ljx
	 * DateTime: 2020/12/31 10:51
	 */
	public function getRow()
	{
		$keys="product_name,product_type,rows,pages";
		$this->hedVerify($keys);
		$result = $this->commodity->getproduct($this->dataArr);
		if (count($result) >= 0) {
			$resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '获取失败', []);
			http_data(200, $resulArr, $this);
		}


	}
    public function checkingrow()
    {
        $keys="password";
        $this->hedVerify($keys);
//		$this->hedVerify();
        $result = $this->commodity->checking($this->dataArr);
        if ($result) {
            $resulArr = build_resulArr('D000', true, '密码正确', []);
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '密码错误', []);
            http_data(200, $resulArr, $this);
        }


    }
	public function delRow()
	{
		$keys="product_id";
		$this->hedVerify($keys);
//		$this->hedVerify();
		$result = $this->commodity->delproduct($this->dataArr);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '删除成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '删除失败', []);
			http_data(200, $resulArr, $this);
		}


	}
	public function modifyRow()
	{
		$keys="product_id,product_name,product_type,product_price,product_lowPrice,product_format,product_weights,product_details,product_ppt,product_cover,product_describe";
		$this->hedVerify($keys);
//		$this->hedVerify();
		$result = $this->commodity->modifyproduct($this->dataArr, $this->userArr['Mobile']);
		if ($result!=0) {
			$resulArr = build_resulArr('D000', true, '修改成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '修改失败', []);
			http_data(200, $resulArr, $this);
		}


	}
//    public function showRow()
//    {
//        $keys="ID";
//        $this->hedVerify($keys);//前置验证
//        $result = $this->commodity->showKey($this->dataArr);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '显示失败', []);
//            http_data(200, $resulArr, $this);
//        }
//
//
//    }


}
