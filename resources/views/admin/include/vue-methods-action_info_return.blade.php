/*返回信息处理*/
return_info_action:function(response)
{
    layer.close(loadi);
    var statusinfo=response.data;
    if(statusinfo.status==1)
    {
        if(statusinfo.is_reload==1)
        {
        layermsg_success_reload(statusinfo.info);
        }
        else
        {
        if(statusinfo.curl)
        {
            layermsg_s(statusinfo.info,statusinfo.curl);
        }
        else
        {
            layermsg_success(statusinfo.info);
            this.get_list_action();
        }
        }
    }
    else
    {
        if(statusinfo.curl)
        {
        layermsg_e(statusinfo.info,statusinfo.curl);
        }
        else
        {

        layermsg_error(statusinfo.info);
        }
    }
},