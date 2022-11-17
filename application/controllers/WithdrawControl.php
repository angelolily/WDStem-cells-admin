<?php


class WithdrawControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Withdraw');
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
	 * Notes:获取充值信息
	 * User: ljx
	 * DateTime: 2020/12/31 10:51
	 */
	public function getRow()
	{
		$keys="pages,rows,begin,end,DataScope,powerdept,withdraw_custome";
		$this->hedVerify($keys);
		$result = $this->withdraw->getWithdraw($this->dataArr,$this->userArr['Mobile']);
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
		$keys="withdraw_id,withdraw_money_time,withdraw_transfer_name,withdraw_imgpath,withdraw_apply_staues,withdraw_verify_name,withdraw_verify";
		$this->hedVerify($keys);
		$result = $this->withdraw->modifyWithdraw($this->dataArr, $this->userArr['Mobile']);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '修改成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '修改失败', []);
			http_data(200, $resulArr, $this);
		}
	}

    /**
     * Notes:上传
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function Uploadcertificate()
    {
//		$this->userArr['Mobile']="17659059578";
        $result = $this->withdraw->uploadcertificate($this->dataArr);
        if (count($result )> 0) {
            $resulArr = build_resulArr('D000', true, '导入成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '导入失败', []);
            http_data(200, $resulArr, $this);
        }
    }
    /**
     * Notes:显示
     * User: ljx
     * DateTime: 2020/12/31 10：40
     */
    public function findcertificate()
    {
        $this->hedVerify();//前置验证
//		$this->userArr['Mobile']="17659059578";
        $result = $this->withdraw->findcertificate($this->dataArr);
        if (count($result)> 0) {
            $resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
            http_data(200, $resulArr, $this);
        } else {
            $resulArr = build_resulArr('D002', false, '显示失败', []);
            http_data(200, $resulArr, $this);
        }
    }

//    public function disRow()
//    {
//        $keys="withdraw_id,withdraw_money_time,withdraw_transfer_name,withdraw_imgpath,withdraw_apply_staues,withdraw_verify_name,withdraw_verify";
//        $this->hedVerify($keys);
//        $result = $this->withdraw->disWithdraw($this->dataArr, $this->userArr['Mobile']);
//        if (count($result) > 0) {
//            $resulArr = build_resulArr('D000', true, '修改成功', []);
//            http_data(200, $resulArr, $this);
//        } else {
//            $resulArr = build_resulArr('D003', false, '修改失败', []);
//            http_data(200, $resulArr, $this);
//        }
//    }
}
