<?php


class ParameterControl extends CI_Controller
{
	private $dataArr = [];//操作数据
	private $userArr = [];//用户数据

	function __construct()
	{
		parent::__construct();
		$this->load->service('Parameter');
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
	 * Notes:参数新增记录
	 * User: ljx
	 * DateTime: 2020/12/31 10：40
	 */
	public function newRow()
	{


		$keys="ParameterTitle,ParameterKey,Parametervalue,ParameterRem";
		$this->hedVerify($keys);//前置验证
//		$this->hedVerify();//前置验证
		$resultNum = $this->parameter->addData($this->dataArr, $this->userArr['Mobile']);
		if (count($resultNum )> 0) {
			$resulArr = build_resulArr('D000', true, '插入成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D002', false, '插入失败', []);
			http_data(200, $resulArr, $this);
		}


	}


	/**
	 * Notes:获取参数信息
	 * User: ljx
	 * DateTime: 2020/1/5 9:51
	 */
	public function getRow()
	{

		$keys="ParameterTitle,ParameterKey,pages,rows,begin,end,DataScope,powerdept";
		$this->hedVerify($keys);//前置验证
//		$this->hedVerify();//前置验证
		$result = $this->parameter->getParameter($this->dataArr);
		if (count($result) >0) {
			$resulArr = build_resulArr('D000', true, '获取成功', json_encode($result));
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '获取失败', []);
			http_data(200, $resulArr, $this);
		}


	}


	public function delRow()
	{
		$keys="ParameterId";
		$this->hedVerify($keys);//前置验证
//		$this->hedVerify();//前置验证
		$result = $this->parameter->delParameter($this->dataArr);
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
		$keys="ParameterId,ParameterTitle,ParameterKey,Parametervalue,ParameterRem";
		$this->hedVerify($keys);//前置验证
//		$this->hedVerify();//前置验证
		$result = $this->parameter->modifyParameter($this->dataArr, $this->userArr['Mobile']);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '修改成功', []);
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '修改失败,检查一下参数名称以及键名是否重复', []);
			http_data(200, $resulArr, $this);
		}


	}
	public function showRow()
	{
		$keys="ID";
		$this->hedVerify($keys);//前置验证
		$result = $this->parameter->showKey($this->dataArr);
		if (count($result) > 0) {
			$resulArr = build_resulArr('D000', true, '显示成功', json_encode($result));
			http_data(200, $resulArr, $this);
		} else {
			$resulArr = build_resulArr('D003', false, '显示失败', []);
			http_data(200, $resulArr, $this);
		}


	}


}
