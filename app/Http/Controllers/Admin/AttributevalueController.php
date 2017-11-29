<?php
/******************************************
****AuThor:rubbish.boy@163.com
****Title :属性值管理
*******************************************/
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Model\Wechat;
use App\Http\Model\Attributevalue;
use App\Http\Model\Attributegroup;
use DB;
use URL;
use Cache;

class AttributevalueController extends PublicController
{
    //
    /******************************************
	****AuThor:rubbish.boy@163.com
	****Title :列表
	*******************************************/
	public function index($id)  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_attributevalue');
		$website['way']='name';
		$wayoption[]=array('text'=>trans('admin.fieldname_item_name'),'value'=>'name');
		$website['wayoption']=json_encode($wayoption);
		$info = object_array(DB::table('attributegroups')->whereId($id)->first());
		$website['info']=$info;
		$website['attributegroup_id']=$id;
		return view('admin/attributevalue/index')->with('website',$website);
	}
    /******************************************
	****AuThor:rubbish.boy@163.com
	****Title :添加
	*******************************************/
	public function add($id)
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_attributevalue');
		$website['id']=0;
		$website['attributegroup_id']=$id;
		return view('admin/attributevalue/add')->with('website',$website);
	}
    /******************************************
	****AuThor : rubbish.boy@163.com
	****Title  : 编辑信息
	*******************************************/
	public function edit($id)  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_attributevalue');
		$website['id']=$id;
		$info = object_array(DB::table('attributevalues')->whereId($id)->first());
		$website['attributegroup_id']=$info['attributegroup_id'];

		return view('admin/attributevalue/add')->with('website',$website);
	}
    /******************************************
	****AuThor:rubbish.boy@163.com
	****Title :列表接口
	*******************************************/
	public function api_list(Request $request)  
	{
		$attributegroup_id=$request->get('attributegroup_id');
		$search_field=$request->get('way')?$request->get('way'):'name';
		$keyword=$request->get('keyword');
		if($keyword)
		{
			$list=Attributegroup::find($attributegroup_id)->hasManyAttributevalues()->where($search_field, 'like', '%'.$keyword.'%')->orderBy('orderid','asc')->paginate($this->pagesize);
			//分页传参数
			$list->appends(['keyword' => $keyword,'way' =>$search_field,'attributegroup_id'=>$attributegroup_id])->links();
		}
		else
		{
			$list=Attributegroup::find($attributegroup_id)->hasManyAttributevalues()->orderBy('orderid','asc')->paginate($this->pagesize);
			$list->appends(['attributegroup_id'=>$attributegroup_id])->links();
		}
		if($list && $list->total()>0)
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_get_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']=$list;
			$msg_array['way']=$search_field;
			$msg_array['keyword']=$keyword;
		}
		else
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_get_empty');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']=$list;
			$msg_array['way']=$search_field;
			$msg_array['keyword']=$keyword;
		}
        return response()->json($msg_array);
	}
    /******************************************
	****AuThor:rubbish.boy@163.com
	****Title :添加接口
	*******************************************/
	public function api_add(Request $request)  
	{
		DB::beginTransaction();
		try
		{ 
			$params = new Attributevalue;
			$params->name 			= $request->get('name');
			$params->val 			= $request->get('val');
			$params->orderid		= $request->get('orderid');
			$params->status			= $request->get('status');
			$params->user_id		= $this->user['id'];
			$params->attributegroup_id 		= $request->get('attributegroup_id');

			if ($params->save()) 
			{
				$msg_array['status']='1';
				$msg_array['info']=trans('admin.message_add_success');
				$msg_array['is_reload']=0;
				$msg_array['curl']=route('get.admin.attributevalue').'/'.$params->attributegroup_id;
				$msg_array['resource']='';
				DB::commit();

			} 
			else 
			{
				
				$msg_array['status']='0';
				$msg_array['info']=trans('admin.message_add_failure');
				$msg_array['is_reload']=0;
				$msg_array['curl']='';
				$msg_array['resource']="";
				DB::rollBack();
			}
		}
		catch (\Exception $e) 
		{ 
			//接收异常处理并回滚
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.message_add_failure');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";	

			DB::rollBack(); 
		}

        return response()->json($msg_array);
	}
    /******************************************
	****AuThor:rubbish.boy@163.com
	****Title :详情接口
	*******************************************/
	public function api_info(Request $request)  
	{

		$condition['id']=$request->get('id');
		$info=object_array(DB::table('attributevalues')->where($condition)->first());
		if($info)
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_get_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']=$info;
		}
		else
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.message_get_empty');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
		}
        return response()->json($msg_array);
	}
    /******************************************
	****@AuThor : rubbish.boy@163.com
	****@Title  : 更新数据接口
	****@return : Response
	*******************************************/
	public function api_edit(Request $request)
	{

		DB::beginTransaction();
		try
		{ 
			
			$params = Attributevalue::find($request->get('id'));
			$params->name 			= $request->get('name');
			$params->val 			= $request->get('val');
			$params->orderid		= $request->get('orderid');
			$params->status			= $request->get('status');

			if ($params->save()) 
			{

				$msg_array['status']='1';
				$msg_array['info']=trans('admin.message_save_success');
				$msg_array['is_reload']=0;
				$msg_array['curl']=route('get.admin.attributevalue').'/'.$params->attributegroup_id;
				$msg_array['resource']='';

				DB::commit();
			} 
			else 
			{
				$msg_array['status']='0';
				$msg_array['info']=trans('admin.message_save_failure');
				$msg_array['is_reload']=0;
				$msg_array['curl']='';
				$msg_array['resource']="";
				DB::rollBack();
			}
		}
		catch (\Exception $e) 
		{ 
			//接收异常处理并回滚
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.message_save_failure');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";	

			DB::rollBack(); 
		}
		return response()->json($msg_array);
	}
}
