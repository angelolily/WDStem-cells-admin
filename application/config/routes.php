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
$route['ag/getCustomer'] = 'AgentControl/getCustomer';//获取代理商客户
$route['ag/newCustomer'] = 'AgentControl/newCustomer';//代理商新增客户
$route['ag/updateCustomer'] = 'AgentControl/updateCustomer'; //代理商编辑客户






