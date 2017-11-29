<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

//Route::auth();
/******************************************
****@AuThor:rubbish.boy@163.com
****@Title :公共方法访问控制
*******************************************/
Route::group(['middleware' => ['cors'], 'prefix' => 'captcha'], function() {
Route::get('/register/{tmp?}', 'CaptchaController@register')->name('get.captcha.register');
Route::get('/login/{tmp?}', 'CaptchaController@login')->name('get.captcha.login');
});

/******************************************
****@AuThor:rubbish.boy@163.com
****@Title :会员中心访问控制
*******************************************/
Route::group(['namespace' => 'User', 'middleware' => ['cors'], 'prefix' => 'user'], function() {
    /******************************************
	****@AuThor:rubbish.boy@163.com
	****@Title :会员注册
	*******************************************/
	Route::get('register/{type?}','RegisterController@register')->name('get.user.register');
	Route::post('register', 'RegisterController@store')->name('post.user.register');
	Route::post('register/exit_api', 'RegisterController@exit_api')->name('post.user.register.exit_api');
	/******************************************
	****@AuThor:rubbish.boy@163.com
	****@Title :会员登录
	*******************************************/
	Route::get('login/{type?}','LoginController@login')->name('get.user.login');
	Route::post('login', 'LoginController@login_action')->name('post.user.login');
	/******************************************
	****@AuThor:rubbish.boy@163.com
	****@Title :退出登录
	*******************************************/
	Route::get('logout','LoginController@logout')->name('get.user.logout');

	/******************************************
	****@AuThor:rubbish.boy@163.com
	****@Title :会员中心
	*******************************************/
	Route::get('/','UserController@index')->name('get.user.index');
	Route::get('userinfo','UserController@userinfo')->name('get.user.userinfo');
	Route::get('edit_pwd','UserController@edit_pwd')->name('get.user.edit_pwd');
	//日志管理
	Route::get('log', 'LogController@index')->name('get.user.log');
	//成长等级
	Route::get('experience', 'ExperienceController@index')->name('get.user.experience');
	//会员积分
	Route::get('score', 'ScoreController@index')->name('get.user.score');
	//信件管理
	Route::get('letter','LetterController@index')->name('get.user.letter');
	Route::get('letter/send', 'LetterController@send')->name('get.user.letter.send');
	Route::get('letter/star','LetterController@star')->name('get.user.letter.star');
	Route::get('letter/trash', 'LetterController@trash')->name('get.user.letter.trash');
	Route::get('letter/add', 'LetterController@add')->name('get.user.letter.add');



	//日志列表
	Route::post('log/api_list', 'LogController@api_list')->name('post.user.log.api_list');
	//成长等级
	Route::post('experience/api_list', 'ExperienceController@api_list')->name('post.user.experience.api_list');
	//会员积分
	Route::post('score/api_list', 'ScoreController@api_list')->name('post.user.score.api_list');
	Route::post('score/api_check_in', 'ScoreController@api_check_in')->name('post.user.score.api_check_in');
	Route::post('score/api_is_check_in', 'ScoreController@api_is_check_in')->name('post.user.score.api_is_check_in');
	//信件管理
	Route::post('letter/api_list', 'LetterController@api_list')->name('post.user.letter.api_list');
	Route::post('letter/api_add', 'LetterController@api_add')->name('post.user.letter.api_add');
	Route::post('letter/api_info', 'LetterController@api_info')->name('post.user.letter.api_info');
	Route::post('letter/api_count', 'LetterController@api_count')->name('post.user.letter.api_count');
	//删除操作
	Route::post('deleteapi/api_delete', 'DeleteapiController@api_delete')->name('post.user.deleteapi.api_delete');
	Route::post('deleteapi/api_del_image', 'DeleteapiController@api_del_image')->name('post.user.deleteapi.api_del_image');
	//用户信息
	Route::post('userinfo/api_edit_pwd', 'UserController@api_edit_pwd')->name('post.user.userinfo.api_edit_pwd');
	Route::post('userinfo/api_info', 'UserController@api_info')->name('post.user.userinfo.api_info');
	Route::post('userinfo/api_edit', 'UserController@api_edit')->name('post.user.userinfo.api_edit');
	//地区数据
	Route::post('district/api_area', 'DistrictController@api_area')->name('post.user.district.api_area');
	//一键操作
	Route::post('oneactionapi/api_one_action', 'OneactionapiController@api_one_action')->name('post.user.oneactionapi.api_one_action');

});


/******************************************
****@AuThor:rubbish.boy@163.com
****@Title :前台访问控制
*******************************************/
Route::group(['middleware' => ['cors']], function() {
	Route::get('/', 'HomeController@index');
	Route::post('/api_cache', 'CacheapiController@api_cache')->name('post.cacheapi.api_cache');
});

/******************************************
****@AuThor:rubbish.boy@163.com
****@Title :Api接口相关业务逻辑
*******************************************/
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
	$api->group(['namespace' => 'App\Http\Controllers\Api\V1','domain' => env('API_DOMAIN', '')], function ($api) {
        $api->get('users', ['as' => 'api.users.list', 'uses' => 'UserController@api_list']);
    });
	$api->group(['namespace' => 'App\Http\Controllers\Api\V1\Xcx','domain' => env('API_DOMAIN', ''),'prefix' => 'xcx'], function ($api) {
		$api->post('login', ['as' => 'api.xcx.login.api_login', 'uses' => 'LoginController@api_login']);
		$api->post('userinfo', ['as' => 'api.xcx.user.api_userinfo', 'uses' => 'UserController@api_userinfo']);
		$api->post('deleteapi', ['as' => 'api.xcx.deleteapi.api_delete', 'uses' => 'DeleteapiController@api_delete']);
		$api->post('proxy', ['as' => 'api.xcx.proxyinterface.api_back', 'uses' => 'ProxyinterfaceController@api_back']);
		$api->post('getlocation', ['as' => 'api.xcx.getlocation.api_info', 'uses' => 'GetlocationController@api_info']);
		$api->any('district', ['as' => 'api.xcx.district.api_area', 'uses' => 'DistrictController@api_area']);
		//名片盒
		$api->post('businesscard/add', ['as' => 'api.xcx.businesscard.api_add', 'uses' => 'BusinesscardController@api_add']);
		$api->post('businesscard/info', ['as' => 'api.xcx.businesscard.api_info', 'uses' => 'BusinesscardController@api_info']);
		$api->post('businesscard/edit', ['as' => 'api.xcx.businesscard.api_edit', 'uses' => 'BusinesscardController@api_edit']);
		$api->post('businesscard', ['as' => 'api.xcx.businesscard.api_list', 'uses' => 'BusinesscardController@api_list']);
		//积分签到
		$api->post('is_check_in', ['as' => 'api.xcx.score.api_is_check_in', 'uses' => 'ScoreController@api_is_check_in']);
		$api->post('check_in', ['as' => 'api.xcx.score.api_check_in', 'uses' => 'ScoreController@api_check_in']);
		//产品
		$api->post('product', ['as' => 'api.xcx.product.api_list', 'uses' => 'ProductController@api_list']);
		$api->post('product/info', ['as' => 'api.xcx.product.api_info', 'uses' => 'ProductController@api_info']);
		//购物车
		$api->post('shoppingcart/add', ['as' => 'api.xcx.shoppingcart.api_add', 'uses' => 'ShoppingcartController@api_add']);
		$api->post('shoppingcart/edit', ['as' => 'api.xcx.shoppingcart.api_edit', 'uses' => 'ShoppingcartController@api_edit']);
		$api->post('shoppingcart', ['as' => 'api.xcx.shoppingcart.api_list', 'uses' => 'ShoppingcartController@api_list']);
		//收货地址
		$api->post('address/add', ['as' => 'api.xcx.address.api_add', 'uses' => 'AddressController@api_add']);
		$api->post('address/info', ['as' => 'api.xcx.address.api_info', 'uses' => 'AddressController@api_info']);
		$api->post('address/edit', ['as' => 'api.xcx.address.api_edit', 'uses' => 'AddressController@api_edit']);
		$api->post('address/set', ['as' => 'api.xcx.address.api_set', 'uses' => 'AddressController@api_set']);
		$api->post('address/default', ['as' => 'api.xcx.address.api_default', 'uses' => 'AddressController@api_default']);
		$api->post('address', ['as' => 'api.xcx.address.api_list', 'uses' => 'AddressController@api_list']);

    });
});


/******************************************
****@AuThor:rubbish.boy@163.com
****@Title :微信相关业务逻辑
*******************************************/
Route::group(['namespace' => 'Wechat','prefix' => 'wechat'], function() {
	Route::any('api/{id?}', 'ApiController@index')->name('wechat.api');
	Route::post('api/create_menu/{id?}', 'ApiController@create_menu')->name('post.wechat.api.create_menu');
});


/******************************************
****@AuThor:rubbish.boy@163.com
****@Title :后台访问需登录控制
*******************************************/
Route::group(['middleware' => 'auth_admin', 'namespace' => 'Admin', 'prefix' => 'admin'], function() {
	/*
	 ***********************************************************************
	 *	   get 路由
	 ***********************************************************************
	 */
    Route::get('/', 'HomeController@index')->name('get.admin');
	Route::get('setting', ['middleware' => ['ability:admin,model_setting'], 'uses' => 'SettingController@index'])->name('get.admin.setting');
	Route::get('user', ['middleware' => ['ability:admin,model_user'], 'uses' => 'UserController@index'])->name('get.admin.user');
	Route::get('userinfo', ['middleware' => ['ability:admin,set_userinfo'], 'uses' => 'UserController@userinfo'])->name('get.admin.userinfo');
	Route::get('edit_pwd', ['middleware' => ['ability:admin,edit'], 'uses' => 'UserController@edit_pwd'])->name('get.admin.edit_pwd');
	Route::get('user/set/{id?}', ['middleware' => ['ability:admin,set_role'], 'uses' => 'UserController@set'])->name('get.admin.user.set');
	//用户角色
	Route::get('userrole', ['middleware' => ['ability:admin,model_role'], 'uses' => 'UserroleController@index'])->name('get.admin.userrole');
	Route::get('userrole/add', ['middleware' => ['ability:admin,add'], 'uses' => 'UserroleController@add'])->name('get.admin.userrole.add');
	Route::get('userrole/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'UserroleController@edit'])->name('get.admin.userrole.edit');
	Route::get('userrole/set/{id?}', ['middleware' => ['ability:admin,set_permission'], 'uses' => 'UserroleController@set'])->name('get.admin.userrole.set');
	//角色权限
	Route::get('userpermission', ['middleware' => ['ability:admin,model_permission'], 'uses' => 'UserpermissionController@index'])->name('get.admin.userpermission');
	Route::get('userpermission/add', ['middleware' => ['ability:admin,add'], 'uses' => 'UserpermissionController@add'])->name('get.admin.userpermission.add');
	Route::get('userpermission/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'UserpermissionController@edit'])->name('get.admin.userpermission.edit');
	//主导航栏
	Route::get('navigation', ['middleware' => ['ability:admin,model_navigation'], 'uses' => 'NavigationController@index'])->name('get.admin.navigation');
	Route::get('navigation/add', ['middleware' => ['ability:admin,add'], 'uses' => 'NavigationController@add'])->name('get.admin.navigation.add');
	Route::get('navigation/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'NavigationController@edit'])->name('get.admin.navigation.edit');
	//文章分类
	Route::get('classify', ['middleware' => ['ability:admin,model_classify'], 'uses' => 'ClassifyController@index'])->name('get.admin.classify');
	Route::get('classify/add', ['middleware' => ['ability:admin,add'], 'uses' => 'ClassifyController@add'])->name('get.admin.classify.add');
	Route::get('classify/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ClassifyController@edit'])->name('get.admin.classify.edit');
	//文章资讯
	Route::get('article', ['middleware' => ['ability:admin,model_article'], 'uses' => 'ArticleController@index'])->name('get.admin.article');
	Route::get('article/add', ['middleware' => ['ability:admin,add'], 'uses' => 'ArticleController@add'])->name('get.admin.article.add');
	Route::get('article/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ArticleController@edit'])->name('get.admin.article.edit');
	//产品分类
	Route::get('classifyproduct', ['middleware' => ['ability:admin,model_classifyproduct'], 'uses' => 'ClassifyproductController@index'])->name('get.admin.classifyproduct');
	Route::get('classifyproduct/add', ['middleware' => ['ability:admin,add'], 'uses' => 'ClassifyproductController@add'])->name('get.admin.classifyproduct.add');
	Route::get('classifyproduct/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ClassifyproductController@edit'])->name('get.admin.classifyproduct.edit');
	//属性分组
	Route::get('attributegroup', ['middleware' => ['ability:admin,model_attributegroup'], 'uses' => 'AttributegroupController@index'])->name('get.admin.attributegroup');
	Route::get('attributegroup/add', ['middleware' => ['ability:admin,add'], 'uses' => 'AttributegroupController@add'])->name('get.admin.attributegroup.add');
	Route::get('attributegroup/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'AttributegroupController@edit'])->name('get.admin.attributegroup.edit');
	//属性值管理
	Route::get('attributevalue/{id?}', ['middleware' => ['ability:admin,model_attributevalue'], 'uses' => 'AttributevalueController@index'])->name('get.admin.attributevalue');
	Route::get('attributevalue/add/{id?}', ['middleware' => ['ability:admin,add'], 'uses' => 'AttributevalueController@add'])->name('get.admin.attributevalue.add');
	Route::get('attributevalue/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'AttributevalueController@edit'])->name('get.admin.attributevalue.edit');
	//产品内容
	Route::get('product', ['middleware' => ['ability:admin,model_product'], 'uses' => 'ProductController@index'])->name('get.admin.product');
	Route::get('product/add', ['middleware' => ['ability:admin,add'], 'uses' => 'ProductController@add'])->name('get.admin.product.add');
	Route::get('product/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ProductController@edit'])->name('get.admin.product.edit');
	//产品价格属性管理
	Route::get('productattribute/{id?}', ['middleware' => ['ability:admin,model_productattribute'], 'uses' => 'ProductattributeController@index'])->name('get.admin.productattribute');
	Route::get('productattribute/add/{id?}', ['middleware' => ['ability:admin,add'], 'uses' => 'ProductattributeController@add'])->name('get.admin.productattribute.add');
	Route::get('productattribute/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ProductattributeController@edit'])->name('get.admin.productattribute.edit');
	//运费模板
	Route::get('expresstemplate', ['middleware' => ['ability:admin,model_expresstemplate'], 'uses' => 'ExpresstemplateController@index'])->name('get.admin.expresstemplate');
	Route::get('expresstemplate/add', ['middleware' => ['ability:admin,add'], 'uses' => 'ExpresstemplateController@add'])->name('get.admin.expresstemplate.add');
	Route::get('expresstemplate/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ExpresstemplateController@edit'])->name('get.admin.expresstemplate.edit');
	//运费管理
	Route::get('expressvalue/{id?}', ['middleware' => ['ability:admin,expressvalue'], 'uses' => 'ExpressvalueController@index'])->name('get.admin.expressvalue');
	Route::get('expressvalue/add/{id?}', ['middleware' => ['ability:admin,add'], 'uses' => 'ExpressvalueController@add'])->name('get.admin.expressvalue.add');
	Route::get('expressvalue/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ExpressvalueController@edit'])->name('get.admin.expressvalue.edit');
	//广告图片
	Route::get('picture', ['middleware' => ['ability:admin,model_picture'], 'uses' => 'PictureController@index'])->name('get.admin.picture');
	Route::get('picture/add', ['middleware' => ['ability:admin,add'], 'uses' => 'PictureController@add'])->name('get.admin.picture.add');
	Route::get('picture/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'PictureController@edit'])->name('get.admin.picture.edit');
	//链接分类
	Route::get('classifylink', ['middleware' => ['ability:admin,model_classifylink'], 'uses' => 'ClassifylinkController@index'])->name('get.admin.classifylink');
	Route::get('classifylink/add', ['middleware' => ['ability:admin,add'], 'uses' => 'ClassifylinkController@add'])->name('get.admin.classifylink.add');
	Route::get('classifylink/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ClassifylinkController@edit'])->name('get.admin.classifylink.edit');
	//友情链接
	Route::get('link', ['middleware' => ['ability:admin,model_link'], 'uses' => 'LinkController@index'])->name('get.admin.link');
	Route::get('link/add', ['middleware' => ['ability:admin,add'], 'uses' => 'LinkController@add'])->name('get.admin.link.add');
	Route::get('link/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'LinkController@edit'])->name('get.admin.link.edit');
	//题目分类
	Route::get('classifyquestion', ['middleware' => ['ability:admin,model_classifyquestion'], 'uses' => 'ClassifyquestionController@index'])->name('get.admin.classifyquestion');
	Route::get('classifyquestion/add', ['middleware' => ['ability:admin,add'], 'uses' => 'ClassifyquestionController@add'])->name('get.admin.classifyquestion.add');
	Route::get('classifyquestion/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ClassifyquestionController@edit'])->name('get.admin.classifyquestion.edit');
	//题目类型
	Route::get('question/{type?}', ['middleware' => ['ability:admin,model_question'], 'uses' => 'QuestionController@index'])->name('get.admin.question');
	Route::get('question/add/{type?}', ['middleware' => ['ability:admin,add'], 'uses' => 'QuestionController@add'])->name('get.admin.question.add');
	Route::get('question/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'QuestionController@edit'])->name('get.admin.question.edit');
	//题目选项
	Route::get('questionoption/{id?}', ['middleware' => ['ability:admin,model_questionoption'], 'uses' => 'QuestionoptionController@index'])->name('get.admin.questionoption');
	Route::get('questionoption/add/{id?}', ['middleware' => ['ability:admin,add'], 'uses' => 'QuestionoptionController@add'])->name('get.admin.questionoption.add');
	Route::get('questionoption/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'QuestionoptionController@edit'])->name('get.admin.questionoption.edit');
	//日志管理
	Route::get('log', ['middleware' => ['ability:admin,model_log'], 'uses' => 'LogController@index'])->name('get.admin.log');
	//信件管理
	Route::get('letter', ['middleware' => ['ability:admin,model_letter'], 'uses' => 'LetterController@index'])->name('get.admin.letter');
	Route::get('letter/send', ['middleware' => ['ability:admin,model_letter'], 'uses' => 'LetterController@send'])->name('get.admin.letter.send');
	Route::get('letter/star', ['middleware' => ['ability:admin,model_letter'], 'uses' => 'LetterController@star'])->name('get.admin.letter.star');
	Route::get('letter/trash', ['middleware' => ['ability:admin,model_letter'], 'uses' => 'LetterController@trash'])->name('get.admin.letter.trash');
	Route::get('letter/add', ['middleware' => ['ability:admin,add'], 'uses' => 'LetterController@add'])->name('get.admin.letter.add');

	//测试模块管理
	Route::get('admintest', ['middleware' => ['ability:admin,model_test'], 'uses' => 'LetterController@index'])->name('get.admintest.letter');
	Route::get('admintest/send', ['middleware' => ['ability:admin,model_test'], 'uses' => 'LetterController@send'])->name('get.admin.admintest.send');
	Route::get('admintest/star', ['middleware' => ['ability:admin,model_test'], 'uses' => 'LetterController@star'])->name('get.admin.admintest.star');
	Route::get('admintest/trash', ['middleware' => ['ability:admin,model_test'], 'uses' => 'LetterController@trash'])->name('get.admin.admintest.trash');
	Route::get('admintest/add', ['middleware' => ['ability:admin,add'], 'uses' => 'LetterController@add'])->name('get.admin.admintest.add');



	//微信管理
	Route::get('wechat', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'WechatController@index'])->name('get.admin.wechat');
	Route::get('wechat/add', ['middleware' => ['ability:admin,add'], 'uses' => 'WechatController@add'])->name('get.admin.wechat.add');
	Route::get('wechat/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'WechatController@edit'])->name('get.admin.wechat.edit');
	Route::get('wechat/manage/{id?}', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'WechatController@manage'])->name('get.admin.wechat.manage');
	Route::get('wechat/subscribe/{id?}', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'WechatController@subscribe'])->name('get.admin.wechat.subscribe');
	Route::get('wechat/defaultreply/{id?}', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'WechatController@defaultreply'])->name('get.admin.wechat.defaultreply');
	Route::get('wechat/messagetpl/{id?}', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'WechatController@messagetpl'])->name('get.admin.wechat.messagetpl');
	//微信-文本回复
	Route::get('wechatreplytext/{id?}', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'WechatreplytextController@index'])->name('get.admin.wechatreplytext.index');
	Route::get('wechatreplytext/add/{id?}', ['middleware' => ['ability:admin,add'], 'uses' => 'WechatreplytextController@add'])->name('get.admin.wechatreplytext.add');
	Route::get('wechatreplytext/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'WechatreplytextController@edit'])->name('get.admin.wechatreplytext.edit');
	//微信-图文回复
	Route::get('wechatreplyimagetext/{id?}', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'WechatreplyimagetextController@index'])->name('get.admin.wechatreplyimagetext.index');
	Route::get('wechatreplyimagetext/add/{id?}', ['middleware' => ['ability:admin,add'], 'uses' => 'WechatreplyimagetextController@add'])->name('get.admin.wechatreplyimagetext.add');
	Route::get('wechatreplyimagetext/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'WechatreplyimagetextController@edit'])->name('get.admin.wechatreplyimagetext.edit');
	//微信菜单分类
	Route::get('classifywechat/{id?}', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'ClassifywechatController@index'])->name('get.admin.classifywechat.index');
	Route::get('classifywechat/add/{id?}', ['middleware' => ['ability:admin,add'], 'uses' => 'ClassifywechatController@add'])->name('get.admin.classifywechat.add');
	Route::get('classifywechat/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'ClassifywechatController@edit'])->name('get.admin.classifywechat.edit');
	//微信（会员)粉丝信息列表
	Route::get('wechatuser/{id?}', ['middleware' => ['ability:admin,model_wechat'], 'uses' => 'WechatuserController@index'])->name('get.admin.wechatuser.index');
	//小程序管理
	Route::get('xcxmp', ['middleware' => ['ability:admin,model_xcxmp'], 'uses' => 'XcxmpController@index'])->name('get.admin.xcxmp');
	Route::get('xcxmp/add', ['middleware' => ['ability:admin,add'], 'uses' => 'XcxmpController@add'])->name('get.admin.xcxmp.add');
	Route::get('xcxmp/edit/{id?}', ['middleware' => ['ability:admin,edit'], 'uses' => 'XcxmpController@edit'])->name('get.admin.xcxmp.edit');


	/*
	 ***********************************************************************
	 *	   post 路由
	 ***********************************************************************
	 */
	//系统配置
	Route::post('home/api_setting', 'HomeController@api_setting')->name('post.admin.home.api_setting');
	//系统设置
	Route::post('setting/api_info', 'SettingController@api_info')->name('post.admin.setting.api_info');
	//一键缓存
	Route::post('cacheapi/api_cache', 'CacheapiController@api_cache')->name('post.admin.cacheapi.api_cache');
	//地区数据
	Route::post('district/api_area', 'DistrictController@api_area')->name('post.admin.district.api_area');
	//删除操作
	Route::post('deleteapi/api_delete', 'DeleteapiController@api_delete')->name('post.admin.deleteapi.api_delete');
	Route::post('deleteapi/api_clear', 'DeleteapiController@api_clear')->name('post.admin.deleteapi.api_clear');
	Route::post('deleteapi/api_del_image', 'DeleteapiController@api_del_image')->name('post.admin.deleteapi.api_del_image');
	//markdownup请求
	Route::post('markdownupload', 'MarkdownapiController@upload')->name('post.admin.markdownupload');
	//一键操作
	Route::post('oneactionapi/api_one_action', 'OneactionapiController@api_one_action')->name('post.admin.oneactionapi.api_one_action');
	//日志列表
	Route::post('log/api_list', 'LogController@api_list')->name('post.admin.log.api_list');
	//用户列表
	Route::post('user/api_list', 'UserController@api_list')->name('post.admin.user.api_list');
	Route::post('user/api_get_one', 'UserController@api_get_one')->name('post.admin.user.api_get_one');
	Route::post('user/api_edit_pwd', 'UserController@api_edit_pwd')->name('post.admin.user.api_edit_pwd');
	Route::post('userinfo/api_info', 'UserController@api_info')->name('post.admin.user.api_info');
	Route::post('userinfo/api_edit', 'UserController@api_edit')->name('post.admin.user.api_edit');
	//用户角色
	Route::post('userrole/api_list', 'UserroleController@api_list')->name('post.admin.userrole.api_list');
	Route::post('userrole/api_get_role', 'UserroleController@api_get_role')->name('post.admin.userrole.api_get_role');
	Route::post('userrole/api_cancel_role', 'UserroleController@api_cancel_role')->name('post.admin.userrole.api_cancel_role');
	Route::post('userrole/api_list_related', 'UserroleController@api_list_related')->name('post.admin.userrole.api_list_related');
	Route::post('userrole/api_add', 'UserroleController@api_add')->name('post.admin.userrole.api_add');
	Route::post('userrole/api_info', 'UserroleController@api_info')->name('post.admin.userrole.api_info');
	Route::post('userrole/api_edit', 'UserroleController@api_edit')->name('post.admin.userrole.api_edit');
	//用户权限
	Route::post('userpermission/api_list', 'UserpermissionController@api_list')->name('post.admin.userpermission.api_list');
	Route::post('userpermission/api_get_permission', 'UserpermissionController@api_get_permission')->name('post.admin.userpermission.api_get_permission');
	Route::post('userpermission/api_cancel_permission', 'UserpermissionController@api_cancel_permission')->name('post.admin.userpermission.api_cancel_permission');
	Route::post('userpermission/api_list_related', 'UserpermissionController@api_list_related')->name('post.admin.userpermission.api_list_related');
	Route::post('userpermission/api_add', 'UserpermissionController@api_add')->name('post.admin.userpermission.api_add');
	Route::post('userpermission/api_info', 'UserpermissionController@api_info')->name('post.admin.userpermission.api_info');
	Route::post('userpermission/api_edit', 'UserpermissionController@api_edit')->name('post.admin.userpermission.api_edit');
	//主导航
	Route::post('navigation/api_list', 'NavigationController@api_list')->name('post.admin.navigation.api_list');
	Route::post('navigation/api_add', 'NavigationController@api_add')->name('post.admin.navigation.api_add');
	Route::post('navigation/api_info', 'NavigationController@api_info')->name('post.admin.navigation.api_info');
	Route::post('navigation/api_edit', 'NavigationController@api_edit')->name('post.admin.navigation.api_edit');
	//文章分类
	Route::post('classify/api_list', 'ClassifyController@api_list')->name('post.admin.classify.api_list');
	Route::post('classify/api_add', 'ClassifyController@api_add')->name('post.admin.classify.api_add');
	Route::post('classify/api_info', 'ClassifyController@api_info')->name('post.admin.classify.api_info');
	Route::post('classify/api_edit', 'ClassifyController@api_edit')->name('post.admin.classify.api_edit');
	//文章
	Route::post('article/api_list', 'ArticleController@api_list')->name('post.admin.article.api_list');
	Route::post('article/api_add', 'ArticleController@api_add')->name('post.admin.article.api_add');
	Route::post('article/api_info', 'ArticleController@api_info')->name('post.admin.article.api_info');
	Route::post('article/api_edit', 'ArticleController@api_edit')->name('post.admin.article.api_edit');
	//产品分类
	Route::post('classifyproduct/api_list', 'ClassifyproductController@api_list')->name('post.admin.classifyproduct.api_list');
	Route::post('classifyproduct/api_add', 'ClassifyproductController@api_add')->name('post.admin.classifyproduct.api_add');
	Route::post('classifyproduct/api_info', 'ClassifyproductController@api_info')->name('post.admin.classifyproduct.api_info');
	Route::post('classifyproduct/api_edit', 'ClassifyproductController@api_edit')->name('post.admin.classifyproduct.api_edit');
	//属性分组
	Route::post('attributegroup/api_list', 'AttributegroupController@api_list')->name('post.admin.attributegroup.api_list');
	Route::post('attributegroup/api_add', 'AttributegroupController@api_add')->name('post.admin.attributegroup.api_add');
	Route::post('attributegroup/api_info', 'AttributegroupController@api_info')->name('post.admin.attributegroup.api_info');
	Route::post('attributegroup/api_edit', 'AttributegroupController@api_edit')->name('post.admin.attributegroup.api_edit');
	//属性值管理
	Route::post('attributevalue/api_list', 'AttributevalueController@api_list')->name('post.admin.attributevalue.api_list');
	Route::post('attributevalue/api_add', 'AttributevalueController@api_add')->name('post.admin.attributevalue.api_add');
	Route::post('attributevalue/api_info', 'AttributevalueController@api_info')->name('post.admin.attributevalue.api_info');
	Route::post('attributevalue/api_edit', 'AttributevalueController@api_edit')->name('post.admin.attributevalue.api_edit');
	//产品
	Route::post('product/api_list', 'ProductController@api_list')->name('post.admin.product.api_list');
	Route::post('product/api_add', 'ProductController@api_add')->name('post.admin.product.api_add');
	Route::post('product/api_info', 'ProductController@api_info')->name('post.admin.product.api_info');
	Route::post('product/api_edit', 'ProductController@api_edit')->name('post.admin.product.api_edit');
	//产品价格属性管理
	Route::post('productattribute/api_list', 'ProductattributeController@api_list')->name('post.admin.productattribute.api_list');
	Route::post('productattribute/api_add', 'ProductattributeController@api_add')->name('post.admin.productattribute.api_add');
	Route::post('productattribute/api_info', 'ProductattributeController@api_info')->name('post.admin.productattribute.api_info');
	Route::post('productattribute/api_edit', 'ProductattributeController@api_edit')->name('post.admin.productattribute.api_edit');
	//运费模板
	Route::post('expresstemplate/api_list', 'ExpresstemplateController@api_list')->name('post.admin.expresstemplate.api_list');
	Route::post('expresstemplate/api_add', 'ExpresstemplateController@api_add')->name('post.admin.expresstemplate.api_add');
	Route::post('expresstemplate/api_info', 'ExpresstemplateController@api_info')->name('post.admin.expresstemplate.api_info');
	Route::post('expresstemplate/api_edit', 'ExpresstemplateController@api_edit')->name('post.admin.expresstemplate.api_edit');
	//运费管理
	Route::post('expressvalue/api_list', 'ExpressvalueController@api_list')->name('post.admin.expressvalue.api_list');
	Route::post('expressvalue/api_add', 'ExpressvalueController@api_add')->name('post.admin.expressvalue.api_add');
	Route::post('expressvalue/api_info', 'ExpressvalueController@api_info')->name('post.admin.expressvalue.api_info');
	Route::post('expressvalue/api_edit', 'ExpressvalueController@api_edit')->name('post.admin.expressvalue.api_edit');
	//广告图片
	Route::post('picture/api_list', 'PictureController@api_list')->name('post.admin.picture.api_list');
	Route::post('picture/api_add', 'PictureController@api_add')->name('post.admin.picture.api_add');
	Route::post('picture/api_info', 'PictureController@api_info')->name('post.admin.picture.api_info');
	Route::post('picture/api_edit', 'PictureController@api_edit')->name('post.admin.picture.api_edit');
	//链接分类
	Route::post('classifylink/api_list', 'ClassifylinkController@api_list')->name('post.admin.classifylink.api_list');
	Route::post('classifylink/api_add', 'ClassifylinkController@api_add')->name('post.admin.classifylink.api_add');
	Route::post('classifylink/api_info', 'ClassifylinkController@api_info')->name('post.admin.classifylink.api_info');
	Route::post('classifylink/api_edit', 'ClassifylinkController@api_edit')->name('post.admin.classifylink.api_edit');
	//链接
	Route::post('link/api_list', 'LinkController@api_list')->name('post.admin.link.api_list');
	Route::post('link/api_add', 'LinkController@api_add')->name('post.admin.link.api_add');
	Route::post('link/api_info', 'LinkController@api_info')->name('post.admin.link.api_info');
	Route::post('link/api_edit', 'LinkController@api_edit')->name('post.admin.link.api_edit');
	//题目分类
	Route::post('classifyquestion/api_list', 'ClassifyquestionController@api_list')->name('post.admin.classifyquestion.api_list');
	Route::post('classifyquestion/api_add', 'ClassifyquestionController@api_add')->name('post.admin.classifyquestion.api_add');
	Route::post('classifyquestion/api_info', 'ClassifyquestionController@api_info')->name('post.admin.classifyquestion.api_info');
	Route::post('classifyquestion/api_edit', 'ClassifyquestionController@api_edit')->name('post.admin.classifyquestion.api_edit');
	//题目
	Route::post('question/api_list', 'QuestionController@api_list')->name('post.admin.question.api_list');
	Route::post('question/api_add', 'QuestionController@api_add')->name('post.admin.question.api_add');
	Route::post('question/api_info', 'QuestionController@api_info')->name('post.admin.question.api_info');
	Route::post('question/api_edit', 'QuestionController@api_edit')->name('post.admin.question.api_edit');
	//题目选项
	Route::post('questionoption/api_list', 'QuestionoptionController@api_list')->name('post.admin.questionoption.api_list');
	Route::post('questionoption/api_add', 'QuestionoptionController@api_add')->name('post.admin.questionoption.api_add');
	Route::post('questionoption/api_info', 'QuestionoptionController@api_info')->name('post.admin.questionoption.api_info');
	Route::post('questionoption/api_edit', 'QuestionoptionController@api_edit')->name('post.admin.questionoption.api_edit');
	//信件
	Route::post('letter/api_list', 'LetterController@api_list')->name('post.admin.letter.api_list');
	Route::post('letter/api_add', 'LetterController@api_add')->name('post.admin.letter.api_add');
	Route::post('letter/api_info', 'LetterController@api_info')->name('post.admin.letter.api_info');
	Route::post('letter/api_count', 'LetterController@api_count')->name('post.admin.letter.api_count');
	//微信-公众号
	Route::post('wechat/api_list', 'WechatController@api_list')->name('post.admin.wechat.api_list');
	Route::post('wechat/api_add', 'WechatController@api_add')->name('post.admin.wechat.api_add');
	Route::post('wechat/api_info', 'WechatController@api_info')->name('post.admin.wechat.api_info');
	Route::post('wechat/api_edit', 'WechatController@api_edit')->name('post.admin.wechat.api_edit');

	//微信-文本回复
	Route::post('wechatreplytext/api_list', 'WechatreplytextController@api_list')->name('post.admin.wechatreplytext.api_list');
	Route::post('wechatreplytext/api_add', 'WechatreplytextController@api_add')->name('post.admin.wechatreplytext.api_add');
	Route::post('wechatreplytext/api_info', 'WechatreplytextController@api_info')->name('post.admin.wechatreplytext.api_info');
	Route::post('wechatreplytext/api_edit', 'WechatreplytextController@api_edit')->name('post.admin.wechatreplytext.api_edit');

	//微信-图文回复
	Route::post('wechatreplyimagetext/api_list', 'WechatreplyimagetextController@api_list')->name('post.admin.wechatreplyimagetext.api_list');
	Route::post('wechatreplyimagetext/api_add', 'WechatreplyimagetextController@api_add')->name('post.admin.wechatreplyimagetext.api_add');
	Route::post('wechatreplyimagetext/api_info', 'WechatreplyimagetextController@api_info')->name('post.admin.wechatreplyimagetext.api_info');
	Route::post('wechatreplyimagetext/api_edit', 'WechatreplyimagetextController@api_edit')->name('post.admin.wechatreplyimagetext.api_edit');

	//微信-菜单分类
	Route::post('classifywechat/api_list', 'ClassifywechatController@api_list')->name('post.admin.classifywechat.api_list');
	Route::post('classifywechat/api_add', 'ClassifywechatController@api_add')->name('post.admin.classifywechat.api_add');
	Route::post('classifywechat/api_info', 'ClassifywechatController@api_info')->name('post.admin.classifywechat.api_info');
	Route::post('classifywechat/api_edit', 'ClassifywechatController@api_edit')->name('post.admin.classifywechat.api_edit');

	//微信-（会员)粉丝信息
	Route::post('wechatuser/api_list', 'WechatuserController@api_list')->name('post.admin.wechatuser.api_list');
	Route::post('wechatuser/api_info', 'WechatuserController@api_info')->name('post.admin.wechatuser.api_info');

	//微信小程序
	Route::post('xcxmp/api_list', 'XcxmpController@api_list')->name('post.admin.xcxmp.api_list');
	Route::post('xcxmp/api_add', 'XcxmpController@api_add')->name('post.admin.xcxmp.api_add');
	Route::post('xcxmp/api_info', 'XcxmpController@api_info')->name('post.admin.xcxmp.api_info');
	Route::post('xcxmp/api_edit', 'XcxmpController@api_edit')->name('post.admin.xcxmp.api_edit');

});
