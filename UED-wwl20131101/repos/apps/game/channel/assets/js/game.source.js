;(function(iCat,$){
	iCat.app('Gapk', function(){
		iCat.PathConfig();
		function skipPage(data, selfPage){
			if(!data || data.indexOf(',')==-1) return;
			var infpage = data.split(','),
				len = infpage.length;
			if(navigator.gamehall){
				len==2 ?
					navigator.gamehall.startlistactivity(
						function(){},function(){},
						{title:infpage[0], url:infpage[1]}
					)
				:
					navigator.gamehall.startdetailsactivity(
						function(){},function(){},
						{title:infpage[0], url:infpage[1], gameid:infpage[2], downurl:infpage[3], packagename:infpage[4], filesize:infpage[5], sdkinfo:infpage[6], resolution:infpage[7]}
					);
			} else {
				location.href = infpage[1];
			}
		}
		var o = {
			openMore: function(){
				$('.wrap').delegate('.J_openBtnWrap', 'click', function(){
					$(this).toggleClass('open')
						.siblings('p').toggleClass('hidden');
				});
				return this;
			},

			slidePic: function(){
				var slideWrap = $('#J_screenshot');
				var panel=$(".pic-wrap");
				if(!slideWrap[0]) return this;
				iCat.include(['touchSwipe','./plugin/slidePic.js'], function(){
					slideWrap.slidePic(
						slideWrap.children().children().hasClass('J_arrow')? {
							slidePanel: '.pic-wrap',
							slideItem: '.pic-wrap span',
							specialWidth: true,
							isTouch:false,
						} : {
							slidePanel: '.pic-wrap',
							slideItem: '.pic-wrap span',
							specialWidth: true,
						}
					);
									
				o.displayImg();
				},true);
				return this;
			},

			loadMore: function(){
				if(!$(".J_loadMore")[0])return;
				var xhr,locked=false;
				$(window).scroll(function(event){
					var boxHeight=document.body.clientHeight,
						visibleHeight=document.documentElement.clientHeight,
						boxScrollTop=document.body.scrollTop;
					var btnMore = $(".J_loadMore"),
						hn = btnMore.attr('data-hasnext');
					var curpage = parseInt(btnMore.attr('data-curpage'));
					var isStrategy=false;
					if(hn==0 || hn=='false') {
						$(window).unbind('scroll');
					} else {
						if(locked==true) return;
						if(Math.abs(boxHeight-visibleHeight)<=boxScrollTop+50){
							btnMore.show();
							locked=true;
							$.ajax({
								type:"POST",
								url:btnMore.attr('data-ajaxUrl'),
								data:{page:curpage+1, token:token},
								dataType:'json',
								success:function(data){
									locked=false;
									isNext=data.data.hasnext;
									btnMore.attr('data-hasnext', data.data.hasnext).attr('data-curpage', data.data.curpage);
									var pNode = $('.J_gameItem ul'), s = '',
									data_type="";
									if(GameApk.ApiVersion!="v1"){
										data_type='data-type="{data-type}"';
									}
									strTemp = '<li>\
												<a '+data_type+' data-infpage="{profile}">\
													<span class="icon"><img src="'+GameApk.blankPic+'" data-src="{img}"></span>\
													<span class="desc">\
														<em>{name}</em>\
														<p><em>大小：{size}</em><em class="tips1"></em></p>\
														<b>{resume}</b>\
													</span>\
												</a>\
											</li>',
									/* template-data merge */
									tdMerge = function(t,d,r){
										//评测列表无img则不显示
										if(r){
											if(d.img&&d.img!=""){
												t=t.replace('<span class="pic"><img data-replace></span>','<span class="pic"><img src="'+GameApk.blankPic+'" data-src="{img}"></span>');
											} else{
												t=t.replace('<span class="pic"><img data-replace></span>','');
											}
										}
										if(!iCat.isString(t) || !/\{|\}/g.test(t)) return false;
										var phs = t.match(/(\{[a-zA-Z]+-[a-zA-Z]+\})|(\{[a-zA-Z]+[a-zA-Z]+\})|(\{[a-zA-Z]+_[a-zA-Z]+\})/g);//fixed bug 判断{字符-字符}
										if(!phs.length) return false;
										iCat.foreach(phs, function(){
											var key = this.replace(/\{|\}/g,''),
												regKey = new RegExp('\{'+key+'\}'),
												val = d[key];
											t = t.replace(regKey, val!==undefined? val:(r? '{'+key+'}':''));
										});
										//游戏列表上的礼包和评测信息
										if(GameApk.ApiVersion!="v1"&&((d.attach&&d.attach!="")||(d.device&&d.device!=0))){
											var tempStr='<em class="tips1">';
											if(d.attach){
												var attachs=d.attach.split(',');
												for(var i=0;i<attachs.length;i++){
													if(attachs[i]=='礼'){
														tempStr+='<span class="gift"></span>';
													}
													if(attachs[i]=='评'){
														tempStr+='<span class="comment"></span>';
													}
												}
												if(d.device==1){
													tempStr+='<span class="grip"></span>';
												}
											}
											else if(d.device==1){
												tempStr+='<span class="grip"></span>';
											}
											tempStr+="</em>";
											t=t.replace('<em class="tips1"></em>',tempStr);
										}
										return t;
									};
									if($('.J_giftItem')[0]){
										pNode=$('.J_giftItem ul');
										strTemp='<li>\
													<a '+data_type+' data-infpage="{data-infpage}">\
														<span class="icon"><img src="'+GameApk.blankPic+'" data-src="{img}"></span>\
														<span class="desc">\
															<em>{title}</em>\
															<p><em >{giftNum}</em></p>\
														</span>\
													</a>\
												</li>';
									}
									else if($('.J_stratgyItem')[0]){
										isStrategy=true;
										pNode=$('.J_stratgyItem ul');
										strTemp='<li>\
													<a data-type="1" data-infpage="{data-infpage}">\
														<div class="intro">\
															<span class="pic"><img data-replace></span><span class="title">\
																<b>{title}</b>\
																<em>{create_time}</em>\
															</span>\
														</div>\
														<div class="content">\
															<span class="desc">{resume}</span>\
														</div>\
													</a>\
												</li>';
									}
									iCat.foreach(data.data.list, function(v, i){
										s += tdMerge(strTemp, v,isStrategy);
									});
									pNode.append(s);
									o.lazyLoad(document.body,100);
									if(data.data.hasnext==0 || data.data.hasnext=='false'){
										btnMore.html('<span class="bottom">到底了，去其它页面看看吧</span>').show();
										$(window).unbind('scroll');
									}
									else{
										btnMore.hide();
									}
								},
								error:function(){
									locked=false;
								}
							})
						}
					}
				});
				return this;
			},

			openPage: function(argument){
				$('body').delegate('a[data-infpage]', 'click', function(){
					var data = $(this).attr('data-infpage') || '';
					skipPage(data);
				});
			},

			displayImg:function(){
				var screenshot=$("#J_screenshot"),
					imgs=screenshot.find('img');
				if(!screenshot[0]) return this;
				var srcArr=[];
				imgs.each(function(index){
					srcArr[index]=$(this).attr('data-bigpic');
					$(this).attr('data-index',index);
				});
				imgs.swipe({
					click:function(){
						var currentIndex=$(this).attr('data-index');
						var data=[];
					 	data=data.concat(currentIndex,srcArr);
						if(navigator.gamehall){
							navigator.gamehall.startimagescanactivity(
								function(){
									iCat.log('正在为您跳转页面');
								},
								function(){},
								{url:data.join('|')}
							);
						} 
					}
				})
			},

			lazyLoad: function(pNode, t){
				var imgs = $(pNode).find('img[src$="blank.gif"],img[src$="blank1.gif"]'),
					_fn = function(o){
						var src = o.getAttribute('data-src');
						iCat.__IMAGE_CACHE = iCat.__IMAGE_CACHE || {};
						if(!src) return;

						if(!iCat.__IMAGE_CACHE[src]){
							var oImg = new Image(); oImg.src = src;
							oImg.onload = function(){
								o.src = src;
								iCat.__IMAGE_CACHE[src] = true;
								$(o).attr('data-src',src);
								oImg = null;
							};
						} else {
							o.src = src;
							$(o).attr('data-src',src);
						}
					};
				
				iCat.foreach(imgs, function(i,v){
					t ? setTimeout(function(){ _fn(i); }, v*t) : _fn(v);
				});
			}
		};
		return {
			init: function(){
				o.slidePic();
				o.openMore().loadMore();
				o.openPage();
				o.lazyLoad(document.body,100);
			}
		};
	});
})(ICAT,jQuery);
