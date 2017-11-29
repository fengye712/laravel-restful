<?php
/******************************************
****AuThor:rubbish.boy@163.com
****Title :题目分类
*******************************************/
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Model\Classifyquestion;
use DB;
use URL;
use App\Common\lib\Cates; 

class ClassifyquestionController extends PublicController
{
    //
    /******************************************
	****AuThor:rubbish.boy@163.com
	****Title :列表
	*******************************************/
	public function index()  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_classifyquestion');
		$website['way']='name';
		$wayoption[]=array('text'=>trans('admin.fieldname_item_name'),'value'=>'name');
		$website['wayoption']=json_encode($wayoption);

		return view('admin/classifyquestion/index')->with('website',$website);
	}
	/******************************************
	****AuThor:rubbish.boy@163.com
	****Title :添加
	*******************************************/
	public function add()
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_classifyquestion');
		$website['id']=0;
		
		$list=object_array(DB::table('classifyquestions')->where('status','=','1')->orderBy('id', 'desc')->get());
		if($list)
		{
			$cates=new Cates();
			$cates->opt($list);
			$classopts = $cates->opt;
			$classoptsdata = $cates->optdata;
			$website['classlist']=json_encode($classoptsdata);
		}
		else
		{
			$classlist[]=array('text'=>trans('admin.website_select_default'),'value'=>'0');
			$website['classlist']=json_encode($classlist);
		}

		return view('admin/classifyquestion/add')->with('website',$website);
	}
	/******************************************
	****AuThor : rubbish.boy@163.com
	****Title  : 编辑信息
	*******************************************/
	public function edit($id)  
	{
		$website=$this->website;
		$website['cursitename']=trans('admin.website_navigation_classifyquestion');
		$website['id']=$id;

		$list=object_array(DB::table('classifyquestions')->where('status','=','1')->orderBy('id', 'desc')->get());
		if($list)
		{
			$cates=new Cates();
			$cates->opt($list);
			$classopts = $cates->opt;
			$classoptsdata = $cates->optdata;
			$website['classlist']=json_encode($classoptsdata);
		}
		else
		{
			$classlist[]=array('text'=>trans('admin.website_select_default'),'value'=>'0');
			$website['classlist']=json_encode($classlist);
		}
		return view('admin/classifyquestion/add')->with('website',$website);
	}
	/******************************************
	****AuThor:rubbish.boy@163.com
	****Title :列表接口
	*******************************************/
	public function api_list(Request $request)  
	{
		$search_field=$request->get('way')?$request->get('way'):'name';
		$keyword=$request->get('keyword');
		if($keyword)
		{
			$list=Classifyquestion::where($search_field, 'like', '%'.$keyword.'%')->paginate($this->pagesize);
			//分页传参数
			$list->appends(['keyword' => $keyword,'way' =>$search_field])->links();
		}
		else
		{
			$list=Classifyquestion::paginate($this->pagesize);
			
		}
		if($list && $list->total()>0)
		{
			$cates=new Cates();
			$cates->opt($list);
			$classoptlist = $cates->optlist;
			/*
			$list['cates']=$classoptlist;
			*/
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

		$params = new Classifyquestion;
		$params->topid 		= $request->get('topid');
		$params->name 		= $request->get('name');
		$params->ico 		= $request->get('ico');
		$params->orderid	= $request->get('orderid');
		$params->amount 	= $request->get('amount');
		$params->status		= $request->get('status');
		$params->user_id	= $this->user['id'];
		
		if($params->topid == 0)
		{
			$params->grade=1;
		}
		else
		{
			$info=Classifyquestion::find($params->topid);
			$params->grade=$info['grade']+1;	
		}

		//图片上传处理接口
		$attachment='attachment';
		$data_image=$request->get($attachment);
		if($data_image)
		{
			//上传文件归类：获取控制器名称
			$classname=getCurrentControllerName();
			$params->attachment=uploads_action($classname,$data_image,$this->thumb_width,$this->thumb_height,$this->is_thumb,$this->is_watermark,$this->root);
			$params->isattach=1;
		}

		if ($params->save()) 
		{
			

			if($params->topid ==0 && $params->grade==1)
			{ 
					$params->node='';
					$params->bcid=$params->id;
			}
			else
			{
				$params->bcid=$params->topid;
				if($params->grade==2)
				{
					$params->node=$params->topid.','.$params->id;
				}
				else
				{
					$params->node= $info['node'].','.$params->id;
				}
				$params->scid=$params->id;
			}
			$params->save();

			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_add_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']=route('get.admin.classifyquestion');
			$msg_array['resource']='';
		} 
		else 
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.message_add_failure');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";

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
		$info=object_array(DB::table('classifyquestions')->where($condition)->first());
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

		$params = Classifyquestion::find($request->get('id'));
		$params->topid 		= $request->get('topid');
		$params->name 		= $request->get('name');
		$params->ico 		= $request->get('ico');
		$params->orderid	= $request->get('orderid');
		$params->amount 	= $request->get('amount');
		$params->status		= $request->get('status');

		if($params->topid==0)
		{
			$params->grade=1;
			$params->node= '';
			$params->bcid=$request->get('id');
		}
		else
		{
			$info=Classifyquestion::find($params->topid);
			$params->grade=$info['grade']+1;	

			$params->bcid=$params->topid;	

			if($params->grade==2)
			{
				$params->node=$params->topid.','.$params->id;
			}
			else
			{
				$params->node= $info['node'].','.$params->id;
			}
			$params->scid=$params->id;	
		}

		//图片上传处理接口
		$attachment='attachment';
		$data_image=$request->get($attachment);
		if($data_image)
		{
			//上传文件归类：获取控制器名称
			$classname=getCurrentControllerName();
			$params->attachment=uploads_action($classname,$data_image,$this->thumb_width,$this->thumb_height,$this->is_thumb,$this->is_watermark,$this->root);
			$params->isattach=1;
		}

		if ($params->save()) 
		{
			$msg_array['status']='1';
			$msg_array['info']=trans('admin.message_save_success');
			$msg_array['is_reload']=0;
			$msg_array['curl']=route('get.admin.classifyquestion');
			$msg_array['resource']='';
		} 
		else 
		{
			$msg_array['status']='0';
			$msg_array['info']=trans('admin.message_save_failure');
			$msg_array['is_reload']=0;
			$msg_array['curl']='';
			$msg_array['resource']="";
		}
		return response()->json($msg_array);
	}
}
