<?php


class RechargeControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Recharge');
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
		$keys="pages,rows,begin,end,DataScope,powerdept,custome_phone,recharge_rate,recharge_money,recharge_status";
		$this->hedVerify($keys);
		$result = $this->recharge->getRecharge($this->dataArr,$this->userArr['Mobile']);
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
		$keys="recharge_id,recharge_f_rate,recharge_invoice_num,recharge_invoice_time,recharge_finanve_name,recharge_finanve";
		$this->hedVerify($keys);
		$result = $this->recharge->modifyRecharge($this->dataArr);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '修改成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '修改失败', []);
			http_data(200, $resulArr, $this);
		}
	}
	public function modifytimeRow()
	{
		$keys="recharge_id,recharge_overTime,recharge_status,recharge_finanve_name,recharge_finanve";
		$this->hedVerify($keys);
		$result = $this->recharge->modifytimeRecharge($this->dataArr);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '修改成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '修改失败', []);
			http_data(200, $resulArr, $this);
		}
	}
}
