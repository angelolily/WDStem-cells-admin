<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Login';
$route['test']['put'] = 'test/ss';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['ul'] = 'LoginControl/loginRow';//登陆自定义路由
$route['dp/new'] = 'DeptControl/newRow';//新增部门
$route['dp/get'] = 'DeptControl/getRow';//获取部门信息
$route['dp/delete'] = 'DeptControl/delRow';//删除部门信息
$route['dp/modify'] = 'DeptControl/modifyRow';//修改部门信息
$route['dp/move'] = 'DeptControl/moveRow';//显示可移动部门信息
$route['dp/status'] = 'DeptControl/statusRow';//修改部门状态
$route['ps/new'] = 'PostControl/newRow';//新增岗位信息
$route['ps/get'] = 'PostControl/getRow';//获取岗位信息
$route['ps/modify'] = 'PostControl/modifyRow';//修改岗位信息
$route['ps/delete'] = 'PostControl/delRow';//删除岗位信息
$route['pm/new'] = 'ParameterControl/newRow';//新增参数信息
$route['pm/get'] = 'ParameterControl/getRow';//获取参数信息
$route['pm/modify'] = 'ParameterControl/modifyRow';//修改参数信息
$route['pm/delete'] = 'ParameterControl/delRow';//删除参数信息
$route['pm/show'] = 'ParameterControl/showRow';//显示下拉参数键名信息
$route['pe/headshow'] = 'PersonalControl/headPortraitRow';//显示头像
$route['pe/get'] = 'PersonalControl/getRow';//显示个人数据
$route['pe/modify'] = 'PersonalControl/modifyRow';//修改密码
$route['um/new'] = 'UserControl/newRow';//新增用户
$route['um/get'] = 'UserControl/getRow';//获取用户
$route['um/modify'] = 'UserControl/modifyRow';//修改用户数据
$route['um/reset'] = 'UserControl/resetRow';//充值密码
$route['um/delete'] = 'UserControl/delRow';//删除
$route['mu/get'] = 'MenuControl/getRow';//显示菜单
$route['mu/new'] = 'MenuControl/newRow';//新增菜单
$route['mu/del'] = 'MenuControl/delRow';//删除菜单
$route['mu/all'] = 'MenuControl/allRefresh';//移动层级
$route['mu/modify'] = 'MenuControl/updateRow';//修改菜单
$route['rl/modify'] = 'RoleControl/modifyRow';//修改角色菜单权限
$route['rl/new'] = 'RoleControl/newRow';//新增角色
$route['rl/get'] = 'RoleControl/getRow';//获取或刷新角色
$route['rl/del'] = 'RoleControl/delRow';//删除角色
$route['rl/dis'] = 'RoleControl/distriRow';//设置角色数据权限
$route['pl/ulp'] = 'CommodityControl/Uploadpic';//多上传图片
$route['pl/fp'] = 'CommodityControl/findpic';//查看图片
$route['pl/dlp'] = 'CommodityControl/delpic';//删除单张图片 三种图片共用
$route['pl/uld'] = 'CommodityControl/Uploaddetail';//上传图片详情
$route['pl/fd'] = 'CommodityControl/finddetail';//查看图片详情
$route['pl/ulc'] = 'CommodityControl/Uploadcover';//上传图片封面
$route['pl/fc'] = 'CommodityControl/findcover';//查看图片封面
$route['cc/new'] = 'CommodityControl/newRow';//新增商品
$route['cc/get'] = 'CommodityControl/getRow';//获取或刷新商品
$route['cc/del'] = 'CommodityControl/delRow';//删除商品
$route['cc/modify'] = 'CommodityControl/modifyRow';//修改商品
//$route['cc/show'] = 'CommodityControl/showRow';//显示下拉商品类型信息
$route['cc/check'] = 'CommodityControl/checkingrow';//验证密码  验证正确后再访问删除接口
$route['wlr/wl'] = 'CustomeInterface/wechat_login';//小程序登陆
$route['wps/hps'] = 'ProductStoreInterface/ControlHomeProductList';//首页商品列表获取
$route['wps/odadd'] = 'ProductStoreInterface/ControlOrderOneAdd';// 首次添加订单
$route['wps/odlst'] = 'ProductStoreInterface/ControlProductList';// 获取订单列表，代理商与客户
$route['wps/sms'] = 'ProductStoreInterface/ControlSendSMS';// 获取订单列表，代理商与客户
$route['wps/reg'] = 'CustomeInterface/wechat_custome_regist';// 微信客户注册
$route['wps/agreg'] = 'CustomeInterface/wechat_agent_regist';// 代理商微信端注册
$route['wps/sbuget'] = 'ProductStoreInterface/getSubscribe';// 获取预定信息
$route['wps/advget'] = 'ProductStoreInterface/getAdvice';// 获取投诉信息
$route['wps/advadd'] = 'ProductStoreInterface/addAdvice';// 新增投诉信息
$route['wps/accget'] = 'ProductStoreInterface/getMyAccount';// 获取客户账户额度
$route['wps/rechadd'] = 'ProductStoreInterface/addRecharge';// 添加充值记录
$route['wps/rechget'] = 'ProductStoreInterface/getRachargeList';// 获取充值记录历史
$route['wps/rechmod'] = 'ProductStoreInterface/modifyRechargeState';// 修改充值记录状态
$route['wps/delod'] = 'ProductStoreInterface/delOrder';// 删除订单
$route['wps/iscard'] = 'ProductStoreInterface/isCardTrue';// 上传身份证信息验证
$route['wps/isface'] = 'ProductStoreInterface/isFaceTrue';// 上传人脸验证
$route['wps/upback'] = 'ProductStoreInterface/saveCardBack';// 上传身份证背面信息
$route['wps/express'] = 'ProductStoreInterface/getExpress';// 获取物流信息
$route['wps/uphealth'] = 'ProductStoreInterface/uploadfileMedical';// 上传体检报告
$route['wps/delrech'] = 'ProductStoreInterface/delRecharge';// 删除汇款中的充值记录
$route['ag/getCustomer'] = 'AgentControl/getCustomer';//获取代理商客户
$route['ag/newCustomer'] = 'AgentControl/newCustomer';//代理商新增客户
$route['ag/updateCustomer'] = 'AgentControl/updateCustomer'; //代理商编辑客户
$route['ag/updateCustomer'] = 'AgentControl/updateCustomer';//代理商编辑客户
$route['ag/getMessage'] = 'AgentControl/getMessage';//代理商获取提醒消息
$route['ag/getMoney'] = 'AgentControl/getMoney';//代理商获取可提现金额
$route['ag/getWithdraw'] = 'AgentControl/getWithdraw';//代理商获取提现历史
$route['ag/addWithdraw'] = 'AgentControl/addWithdraw';//代理商发起提现
$route['ag/getInfo'] = 'AgentControl/getInfo';//代理商获取个人信息、重要消息数、报备客户数和成交客户数
$route['ag/getAgent'] = 'AgentControl/getAgent';//获取代理商信息
$route['ag/getChart'] = 'AgentControl/getChart';//获取代理商产订单图表信息
$route['ag/updatePrice'] = 'AgentControl/updatePrice';//代理商修改订单价格
$route['ag/getQrCode'] = 'AgentControl/getQrCode';//代理商生成二维码并保存
$route['ct/get'] = 'CustomerControl/getRow';//获取或刷新客户管理
$route['ct/modify'] = 'CustomerControl/modifyRow';//修改客户管理
$route['ct/getshow'] = 'CustomerControl/getshowRow';//显示下拉代理商姓名
$route['ct/gethealth'] = 'CustomerControl/showHealth';//显示客户健康档案
$route['ct/getadd'] = 'CustomerControl/showAdress';//显示地址
$route['ct/getcer'] = 'CustomerControl/showCertificate';//查看电子凭证
$route['od/modmon'] = 'OrderControl/modifypriceRow';//修改价格
$route['od/modadd'] = 'OrderControl/modifyaddressRow';//修改地址等
$route['od/modlog'] = 'OrderControl/modifylogisticsRow';//修改物流公司等
$route['od/showque'] = 'OrderControl/showQuestion';//显示电子问卷
$route['od/showcontract'] = 'OrderControl/showContract';//显示协议
$route['od/addcer'] = 'OrderControl/addCertificate';//上传电子凭证其他信息
$route['od/pdfuplo'] = 'OrderControl/pdfuploaddetail';//上传电子凭证PDF
$route['od/pdf'] = 'OrderControl/getpdf';//查看电子凭证
$route['od/upload'] = 'OrderControl/uploadhealth';//下载体检报告
$route['od/showhealth'] = 'OrderControl/showhealth';//查看体检报告
$route['od/get'] = 'OrderControl/getRow';//获取或刷新订单首页
//$route['od/del'] = 'OrderControl/delRow';//删除订单
//$route['od/show'] = 'OrderControl/showRow';//显示下拉订单类型信息
//$route['od/check'] = 'OrderControl/checkingrow';//验证密码  验证正确后再访问删除接口
$route['yqc/save'] = 'QuestionnaireControl/saveQuestionnaire'; //保存调查问卷
$route['yqc/set'] = 'QuestionnaireControl/setOrderUserInf'; //存储购买人与保存人信息
$route['yqc/get'] = 'QuestionnaireControl/getProductInf'; //存储购买人与保存人信息
$route['ysc/set'] = 'SignControl/setProtocolModels'; //存储购买人与保存人信息
$route['ysc/s'] = 'SignControl/sign'; // 存储签名
$route['yac/list'] = 'AddressControl/getUserAddress'; //获取用户地址列表
$route['yac/aim'] = 'AddressControl/getAimAddress'; //获取目标收货地址
$route['yac/save'] = 'AddressControl/saveAddress'; //保存用户收获地址
$route['yac/de'] = 'AddressControl/getDefaultAddress'; //获取用户默认收获地址
$route['ycc/p'] = 'CheckControl/getProductInf'; //获取当前商品信息
$route['ycc/b'] = 'CheckControl/checkUserBalance'; //判断用户是否足够支付
$route['ycc/c'] = 'CheckControl/getCover'; //获取封面
$route['ycc/set'] = 'CheckControl/setOrder'; //生成订单
$route['ycc/get'] = 'CheckControl/getOrderInf'; //获取订单信息
$route['ycc/card'] = 'CheckControl/setUserInf'; //设置用户实名信息到订单中
$route['ad/get'] = 'AdviceControl/getRow';//获取或刷新投诉页面
$route['ad/modify'] = 'AdviceControl/modifyRow';//投诉状态改变
$route['rc/get'] = 'RechargeControl/getRow';//获取或刷新充值记录页面
$route['rc/modify'] = 'RechargeControl/modifyRow';//填写充值状态为 已完成，财务备注信息，发票号码
$route['rc/modifytime'] = 'RechargeControl/modifytimeRow';//填写开票时间
$route['wd/get'] = 'WithdrawControl/getRow';//获取或刷新提现申请信息
$route['wd/modify'] = 'WithdrawControl/modifyRow';//填写充值状态为已放款，汇款时间，上传凭证，汇款人姓名
$route['wd/uld'] = 'WithdrawControl/Uploadcertificate';//上传汇款凭证
$route['wd/fd'] = 'WithdrawControl/findcertificate';//查看汇款凭证
$route['rs/new'] = 'ReserveControl/newRow';//新增预约
$route['rs/show'] = 'ReserveControl/showRow';//显示预约
$route['rs/modify'] = 'ReserveControl/modifyRow';//修改预约
$route['rp/get'] = 'ReportControl/getRow';//获取服务商业绩统计表
$route['rp/getall'] = 'ReportControl/getallRow';//获取销售统计表
