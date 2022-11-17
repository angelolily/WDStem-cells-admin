<?php


class CustomerControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Customer');
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
	 * Notes:获取客户信息
	 * User: ljx
	 * DateTime: 2020/12/31 10:51
	 */
	public function getRow()
	{
		$keys="pages,rows,custome_vip,custome_phone,DataScope,powerdept,custome_name,powerdept,Mobile";
		$this->hedVerify($keys);
		$result = $this->customer->getCustomer($this->dataArr, $this->userArr['Mobile']);
//        $new=[];
//        $results=[];
//		foreach ($result['data'] as $key=>$value)
//        {
//                $value['custome_agent_name']=$value["(select base_user.UserName from base_user,cell_customer where base_user.Mobile = cell_customer.custome_agent )"];
//                $value["custome_serivce_name"]=$value["(select base_user.UserName from base_user,cell_customer where base_user.Mobile = cell_customer.custome_serivce )"];
//            $value=bykey_reitem($value,"(select base_user.UserName from base_user,cell_customer where base_user.Mobile = cell_customer.custome_agent )");
//            $value=bykey_reitem($value,"(select base_user.UserName from base_user,cell_customer where base_user.Mobile = cell_customer.custome_serivce )");
//            array_push($new,$value);
//        }
//        $results['total']=$result['total'];
//        $results['data']=$new;
		if (count($result) >= 0) {
			$resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '获取失败', []);
			http_data(200, $resulArr, $this);
		}


	}


	public function modifyRow()
	{
		$keys="custome_id,custome_agent,custome_serivce,custome_name,custome_sex,custome_birthday";
		$this->hedVerify($keys);
//		$this->hedVerify();
		$result = $this->customer->modifyCustomer($this->dataArr, $this->userArr['Mobile']);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '修改成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '修改失败', []);
			http_data(200, $resulArr, $this);
		}


	}

    public function getshowRow()
    {
        $keys="ID,DataScope,powerdept";
        $this->hedVerify($keys);
        $result = $this->customer->showagent($this->dataArr);
        if (count($result) > 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }


    }
    public function showHealth()
    {
        $keys="custome_id";
        $this->hedVerify($keys);
        $result = $this->customer->showhealth($this->dataArr);
        if (count($result) > 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }


    }
    public function showAdress()
    {
        $keys="custome_id";
        $this->hedVerify($keys);
        $result = $this->customer->showaddress($this->dataArr);
        if (count($result) > 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }


    }
    public function showCertificate()
    {
        $keys="custome_id";

        $this->hedVerify($keys);
        $result = $this->customer->showcertificate($this->dataArr);
        if (count($result) > 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D003', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }


    }


}
