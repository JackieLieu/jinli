(function(a){a.PathConfig();var c=a.namespace("GNapp");c.models={};c.collections={};c.views={};c.routers={};var f=(location.search.indexOf("source=wap")>-1)?true:false,d=navigator.userAgent,e=false,b={android:/android/i.test(d),gionee:/Gionee/i.test(d)};
e=b.android&&f==false;a.include(["lib/zepto/zepto.js","lib/underscore/underscore.js","lib/backbone/backbone.js"],function(){var i={};i.hasTouch="ontouchstart" in window;
i.TAP_EV=i.hasTouch?"tap":"mousedown";i.START_EV=i.hasTouch?"touchstart":"mousedown";i.MOVE_EV=i.hasTouch?"touchmove":"mousemove";i.END_EV=i.hasTouch?"touchend":"mouseup";
i.CANCEL_EV=i.hasTouch?"touchcancel":"mouseup";$.extend($.fn,{hover:function(j){var l=$(this),j=j||"hover";var k;function m(){i.hasActive=true;$(i.touchTarget).parents(".item").addClass(j);
}l.on(i.START_EV,function(n){i.touchTarget=n.target;k=setTimeout(m,50);});l.on(i.MOVE_EV+" "+i.END_EV+" "+i.CANCEL_EV,function(){if(k){clearTimeout(k);
}if(i.hasActive){$(i.touchTarget).removeClass(j);$(i.touchTarget).parents(".item").removeClass(j);i.hasActive=false;}});}});var h=$(".module").eq(2);h.append('<div class="app-list J_app_list"></div>');
var g=$(".J_app_list");c.models.app=Backbone.Model.extend({urlRoot:"./root/api",});c.collections.app=Backbone.Collection.extend({model:c.models.app,url:function(){return"./app/"+this.models[0].get("data").curnav+"_more";
},});c.routers.app=Backbone.Router.extend({_index:null,_data:null,_apps:null,routes:{"":"index","module/:name":"showAppModule","category/:id":"showAppCateDetail",},initialize:function(j){this._header=new c.views.header();
this._selectedMenu="recommend";this._data=JSON.parse($(".index_data").html());this._apps=new c.collections.app(this._data[this._selectedMenu]);this._index=new c.views.appList({model:this._apps,module:this._selectedMenu});
},index:function(){this._header.render(this._selectedMenu);this._header.showTopBanner();this._index.render();},showAppModule:function(j){var l=j;this._selectedMenu=j;
this._header.render(this._selectedMenu);this._header.showTopBanner();var k=new c.collections.app(this._data[this._selectedMenu]);if(this._selectedMenu=="category"){g.html(_.template($("#J_appCateView").html()));
return;}g.html(new c.views.appList({model:k,module:this._selectedMenu}).render().el);},showAppCateDetail:function(l){var j=this;new c.views.backNav();g.html('<div class="loading">数据加载...</div>');
var k=new c.collections.app();k.fetch({url:"./app/list_more",success:function(){g.html(new c.views.appList({model:k,module:"cate",typeId:l}).render().el);
},data:{type_id:l,page:1}});}});c.views.header=Backbone.View.extend({el:"body",template:_.template($("#J_tabMenuView").html()),initialize:function(){this.$el.delegate(".in-tabs li",i.TAP_EV,this.swichTab);
this.render();},render:function(j){$(".module").eq(1).html(this.template());this.selectMenuItem(j);},showTopBanner:function(){$(".module").eq(0).html(_.template($("#J_topBannerView").html()));
},selectMenuItem:function(j){$("#app-tabs li").removeClass("actived");if(j){$("#app-tabs li[data-target="+j+"]").addClass("actived");}},swichTab:function(){var j=$(this).attr("data-link");
gnappRoute.navigate(j,true);}});c.views.backNav=Backbone.View.extend({el:$(".module").eq(0),template:_.template($("#J_backNavView").html()),initialize:function(){this.render();
},render:function(){$(this.el).html(this.template({cateName:""}));this.hideTabMenu();return this;},hideTabMenu:function(){$("#app-tabs").remove();},hideAppList:function(){$(".app-list").remove();
}});c.views.appList=Backbone.View.extend({tagName:"ul",events:{"click .add-btn":"addAppIcon","click .list-more":"showAppMore","click .item-cont":"openAppLink","click .open-btn":"openUrlLink"},initialize:function(){this.listenTo(this.model,"reset",this.render);
},render:function(){var k=[],j=0;if(e){k=this.getLocalData();}_.each(this.model.models,function(m,l){if(this.options.module=="cate"){$("#J_backNavTitle").text(m.get("data").cateName);
}m.get("data").curnav=this.options.module;_.each(m.get("data").list,function(n){n.status=e?k.indexOf(n.id)>-1?1:0:1;this.$el.append(new c.views.appItem({model:m,module:this.options.module,index:j++}).render().el);
},this);$(".list-more").remove();if(m.get("data").hasnext==true){this.$el.append('<li class="item list-more">加载更多</li>');}},this);this.$el.appendTo(g);
return this;},getLocalData:function(){try{localData=JSON.parse(window.prompt("gn://GNNavSiteData/select",""));}catch(j){localData=0;console.log("GNapp.views.appList:getLocalData get interface data error.");
}return localData.data;},addAppIcon:function(j){var l=$(j.target),k=l.attr("data-addUrl");if(b.android&&f==false){var m=window.prompt("gn://GNNavSiteData/insert",k);
if(JSON.parse(m).result=="0"){window.prompt("gn://GNNavSiteData/tips","添加成功");l.text("打开").removeClass("add-btn").addClass("open-btn lock");setTimeout(function(){l.removeClass("lock");
},50);}else{console.log("GNapp.views.appList:addAppIcon get interface data error.");}}},openAppLink:function(j){var l=$(j.target).parents("li").find(".button .btn");
var k=l.attr("data-addUrl")==null?l.attr("href"):l.attr("data-link");window.location.href=k;},openUrlLink:function(j){var k=$(j.target);if(k.attr("data-addUrl")&&k.attr("data-link")&&!k.hasClass("lock")){window.location.href=k.attr("data-link");
}},showAppMore:function(j){var l=this;var m=j.target;var k=this.model.at(0);var n=k.get("data").curpage+1;if($(m).hasClass("locked")||$(m).parent().hasClass("locked")){return;
}if(k.get("data").hasnext==false){this.$el.find(".list-more").remove();}this.loadStatus();if(this.options.module=="cate"){this.model.fetch({data:{type_id:this.options.typeId,page:n}});
}else{this.model.fetch({data:{page:n}});}},loadStatus:function(){this.$el.find(".list-more").addClass("locked");this.$el.find(".list-more").html("正在加载数据...");
return this;}});c.views.appItem=Backbone.View.extend({tagName:"li",className:"item",template:_.template($("#J_itemView").html()),initialize:function(){this.model.bind("change",this.render,this);
this.model.bind("destroy",this.close,this);},render:function(){this.$el.append(this.template(this.model.get("data").list[this.options.index]));return this;
},close:function(){this.$el.unbind();this.$el.remove();},});gnappRoute=new c.routers.app();Backbone.history.start();},true);})(ICAT);