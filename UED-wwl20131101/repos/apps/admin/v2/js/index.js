
var tabsMgr={
	addPanel:function(obj){
		obj.closable=typeof(obj.closable)=='undefined'?true:obj.closable;
		$('#admin-tabs').tabs('add',obj);
	},
	removePanel:function(){
		var tab=$('#admin-tabs').tabs('getSelected');
		if(tab){
			var index=$('#admin-tabs').tabs('getTabIndex',tab);
			$('#admin-tabs').tabs('close',index);}
		},
	tabClickHandle:function(node,m){
		var obj={id:node.id,title:node.text,url:node.url,iconCls:node.iconCls};
		var loadType=typeof(m)!='undefined'&&m=='ajax'?m:'iframe';loadType=='iframe'?obj.content=this.createIframeContent(obj.url):obj.href=obj.url;
		var t=$("#admin-tabs").tabs('exists',obj.title);
		if(t){
			$("#admin-tabs").tabs('select',obj.title);
			var refreshObj=loadType=="ajax"?{href:obj.href}:{href:null,content:obj.content};
			$("#admin-tabs").tabs('update',{tab:$("#admin-tabs").tabs('getSelected'),options:refreshObj});}else{this.addPanel(obj);}},createIframeContent:function(url){return'<iframe scrolling="auto" width="100%" height="99.7%" frameborder="0"  src="'+url+'"></iframe>';}};function menu_dt_click(obj){obj.parent().parent().children("dd").slideUp();$("#dd-"+obj.attr("id")).is(":visible")?$("#dd-"+obj.attr("id")).slideUp():$("#dd-"+obj.attr("id")).slideDown();obj.parent().hasClass('current')?obj.parent().removeClass('current'):obj.parent().addClass('current');$("#menu-accordion dl > dt.current a").each(function(i){if($(this).attr("id")!=obj.attr("id"))$(this).parent().removeClass("current");});}
function menu_a_click(obj){var node={id:obj.attr("id"),text:obj.attr("text"),url:obj.attr("url"),iconCls:obj.attr("iconCls")};tabsMgr.tabClickHandle(node);}
var MENU_HTML="";var _MenuMgr_={"genMenuList":function(id){MENU_HTML='<dl>';
var obj=JSON_MENU;for(var o in obj){if(typeof(id)!="undefined"&&o!=id)continue;var url=typeof(obj[o].url)!='undefined'&&obj[o].url!=null?obj[o].url:'';var t={id:obj[o].id,text:obj[o].name,url:url};if(typeof(obj[o].items)!='undefined'){_MenuMgr_.getChildren(obj[o].items);}
if(typeof(id)=="undefined")break;}
MENU_HTML+='</dl>';$("#menu-accordion").html(MENU_HTML);},"getChildren":function(obj){for(var o in obj){MENU_HTML+='<dt><a href="javascript:;" id="dt-'+obj[o].id+'"';var url=typeof(obj[o].url)!='undefined'&&obj[o].url!=null?obj[o].url:'';if(url!=""){MENU_HTML+=' text="'+obj[o].name+'" url="'+url+'" iconCls="icon-treenode1"';MENU_HTML+=' onClick="menu_a_click($(this))"';}else{MENU_HTML+=' onClick="menu_dt_click($(this))"';}
MENU_HTML+='>'+obj[o].name+(typeof(obj[o].items)!="undefined"?'<i>&gt;</i><span class="dt-child-count">'+obj[o].items.length+'</span>':"")+'</a></dt>';if(typeof(obj[o].items)!='undefined'){_MenuMgr_.getChildrenItem(obj[o].items,obj[o].id);}}},"getChildrenItem":function(items,id){MENU_HTML+='<dd style="display:none;" id="dd-dt-'+id+'"><ul>';for(var o in items){if(typeof(items[o].items)=='undefined'){var url=typeof(items[o].url)!='undefined'&&items[o].url!=null?items[o].url:'';MENU_HTML+='<li><a href="javascript:;"';MENU_HTML+=' id="'+items[o].id+'" text="'+items[o].name+'" url="'+url+'" iconCls="icon-treenode2"';MENU_HTML+=' onClick="menu_a_click($(this))"';MENU_HTML+='>'+items[o].name+'</a></li>';}else{MENU_HTML+='<dt><a href="javascript:;" id="dt-'+id+items[o].id+'"';var url=typeof(items[o].url)!='undefined'&&items[o].url!=null?items[o].url:'';if(url!=""){MENU_HTML+=' text="'+items[o].name+'" url="'+url+'" iconCls="icon-treenode1"';MENU_HTML+=' onClick="menu_a_click($(this))"';}else{MENU_HTML+=' onClick="menu_dt_click($(this))"';}
MENU_HTML+='>'+items[o].name+(typeof(items[o].items)!="undefined"?'<i>&gt;</i><span class="dt-child-count">'+items[o].items.length+'</span>':"")+'</a></dt>';_MenuMgr_.getChildrenItem(items[o].items,id+''+items[o].id);}}
MENU_HTML+='<ul></dd>';}};$(document).ready(function(){_MenuMgr_.genMenuList();$("#navMenu ul li").click(function(){$("#navMenu ul li").removeClass("selected");$(this).addClass("selected");_MenuMgr_.genMenuList($(this).attr("id"));});$("#admin-tabs").tabs("add",{title:"欢迎使用",content:tabsMgr.createIframeContent('/Admin/Index/default')});$(".tabs-header").bind('contextmenu',function(e){e.preventDefault();$('#admin-tabs-menu').menu('show',{left:e.pageX,top:e.pageY});});$(".tabs").delegate(".tabs-inner","dblclick",function(){var tab=$('#admin-tabs').tabs('getSelected');var index=$('#admin-tabs').tabs('getTabIndex',tab);$('#admin-tabs').tabs('close',index);});$("#refreshCtab").bind("click",function(){$("#admin-tabs").tabs('update',{tab:$("#admin-tabs").tabs('getSelected'),options:{}});});$("#closecur").bind("click",function(){var tab=$('#admin-tabs').tabs('getSelected');var index=$('#admin-tabs').tabs('getTabIndex',tab);$('#admin-tabs').tabs('close',index);});$("#closeall").bind("click",function(){var tablist=$('#admin-tabs').tabs('tabs');for(var i=tablist.length-1;i>=0;i--){$('#admin-tabs').tabs('close',i);}});$("#closeother").bind("click",function(){var tablist=$('#admin-tabs').tabs('tabs');var tab=$('#admin-tabs').tabs('getSelected');var index=$('#admin-tabs').tabs('getTabIndex',tab);for(var i=tablist.length-1;i>index;i--){$('#admin-tabs').tabs('close',i);}
var num=index-1;for(var i=num;i>=0;i--){$('#admin-tabs').tabs('close',0);}});$("#closeright").bind("click",function(){var tablist=$('#admin-tabs').tabs('tabs');var tab=$('#admin-tabs').tabs('getSelected');var index=$('#admin-tabs').tabs('getTabIndex',tab);for(var i=tablist.length-1;i>index;i--){$('#admin-tabs').tabs('close',i);}});$("#closeleft").bind("click",function(){var tab=$('#admin-tabs').tabs('getSelected');var index=$('#admin-tabs').tabs('getTabIndex',tab);var num=index-1;for(var i=0;i<=num;i++){$('#admin-tabs').tabs('close',0);}});});
