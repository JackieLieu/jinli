$(function(){if($("#sliderPlay").length&&$("#sliderPlay").find("ul.slide_list").children("li").length>1){$("#sliderPlay").find("div.banner_slide").unslider({dots:true});}lazyload(100);$(".rank_list li").hover(function(){$(this).addClass("first").siblings().removeClass("first");});if($(".other_fixed_assist").length>0){if("undefined"==typeof(document.body.style.maxHeight)&&!($.support.style)){var e=$(".other_fixed_assist").position().top;var d=$(window).height();$(window).scroll(function(){var f=$(window).scrollTop();if($(window).scrollTop()>200){$(".other_fixed_assist").show().css({top:e+f-80});$("#top").show().css("display","block");}else{$(".other_fixed_assist").show().css({top:e+f});$("#top").hide();}});}else{$(window).scroll(function(){if($(window).scrollTop()>200){$("#top").show().css("display","block");}else{$("#top").hide();}});}$("#top").click(function(){$("html,body").animate({scrollTop:0},100);});}$(".edit_list input").focus(function(){$(this).parent().addClass("cur").siblings().removeClass("cur");});$(".edit_list li .ico,.edit_list li .input_txt").click(function(){$(this).parents("li").addClass("cur").siblings().removeClass("cur");var f=$(this).parents("li").siblings("li").children("input");f.each(function(){$(this).val($(this).attr("placeholder"));});});$("#btn_feedback_submit").removeAttr("disabled");$("#form_feedback").submit(function(){$(".feedback_error").hide();var j=$.trim($("#content").val());var m=$.trim($("#mobile").val());var i=$.trim($("#email").val());var k=$.trim($("#qq").val());var f=/^(0|86|17951)?(13[0-9]|15[012356789]|18[02356789]|14[57])[0-9]{8}$/g;var l=/\w@\w*\.\w/g;var h=/^[1-9]\d{5,11}$/g;var g=$("li.cur").children("input").attr("id");if(j==""||j=="请填写反馈内容"){$("#feedback_error_content_empty").show();return false;}if(j.length>1000){$("#feedback_error_content_max").show();return false;}if(g=="mobile"){if(m==""||m==$("#mobile").attr("placeholder")||!f.test(m)){$("#feedback_error_mobile").show();return false;}else{$("#feedback_error_mobile").hide();}}if(g=="email"){if(i==""||i==$("#email").attr("placeholder")||!l.test(i)){$("#feedback_error_email").show();return false;}else{$("#feedback_error_email").hide();}}if(g=="qq"){if(k==""||k==$("#qq").attr("placeholder")||!h.test(k)){$("#feedback_error_qq").show();return false;}else{$("#feedback_error_qq").hide();}}});$("#form1").submit(function(){var f=$.trim($("#header_search_content").val());if(f==""||f=="搜索游戏名称/标签"){alert("输入关键字不能为空");return false;}return true;});$("#j_toggle").click(function(){if($("#j_toggle").hasClass("show")){$("#j_toggle").html("收起");$("#j_content").addClass("auto");$("#j_toggle").removeClass("show");$("#j_toggle").addClass("hide");return;}if($("#j_toggle").hasClass("hide")){$("#j_toggle").html("展开更多");$("#j_content").removeClass("auto");$("#j_toggle").removeClass("hide");$("#j_toggle").addClass("show");return;}});equal_height();window.jiathis_config={};share();var b=$("input[placeholder],textarea[placeholder]");if(b.length!==0&&!supports_input_placeholder()){var c="#A9A9A9";$.each(b,function(h){var f=0;var j=$(this).attr("placeholder");var g=$(this).css("color");$(this).val(j).css("color",c);$(this).focus(function(){if($(this).val()==j&&!f){$(this).val("").css("color",g);}}).blur(function(){if($(this).val()==""){$(this).val(j).css("color",c);f=0;}}).keyup(function(){if($(this).val()!==j){f=1;}});});}$("#top").click(function(){$("body,html").animate({scrollTop:0},500);return false;});$("#weixin").mouseover(function(){$("#wx").show();});$("#weixin").mouseout(function(){$("#wx").hide();});var a;$(".share_c").mouseover(function(){$(".share_c .share_show").show();clearTimeout(a);});$(".share_c").mouseout(function(){a=setTimeout("shareHide()",1000);});});$(window).resize(function(){lazyload(100);});$(window).bind("scroll",function(){var a=$(".head").height();if($(window).scrollTop()>a-10){$(".menu").addClass("fixed");}else{$(".menu").removeClass("fixed");}lazyload(100);});function shareHide(){$(".share_c .share_show").hide();}function supports_input_placeholder(){var a=document.createElement("input");return"placeholder" in a;}function share(){var container=$("[data-tag=share]");if(!container[0]){return;}with(document){0[(getElementsByTagName("head")[0]||body).appendChild(createElement("script")).src="http://v1.jiathis.com/code/jia.js"];}container.on("mouseover",function(){jiathis_config.title=$(this).attr("data-text");jiathis_config.url=$(this).attr("data-url");jiathis_config.pic=$(this).attr("data-pic");jiathis_config.summary=" ";});}function AddFavorite(b,a){b=encodeURI(b);try{window.external.addFavorite(b,a);}catch(c){try{window.sidebar.addPanel(a,b,"");}catch(c){alert("加入收藏失败，请使用Ctrl+D进行添加,或手动在浏览器里进行设置.");}}}function SetHome(a){if(document.all){document.body.style.behavior="url(#default#homepage)";document.body.setHomePage(a);}else{alert("您好,您的浏览器不支持自动设置页面为首页功能,请您手动在浏览器里设置该页面为首页!");}}function ToDesktop(c,f){try{var b=new ActiveXObject("WScript.Shell");var a=b.CreateShortcut(b.SpecialFolders("Desktop")+"\\"+f+".url");a.TargetPath=c;a.Save();}catch(d){alert("当前浏览器安全级别不允许操作！");}}var HEIGHT_RANK_BOX=$(".rank_box").innerHeight();var HEIGHT_MAIN=$(".main").innerHeight();function equal_height(){$(".notice_box").css("height","auto");var a=$(".main").innerHeight();if(HEIGHT_RANK_BOX>a){$(".notice_box").innerHeight(HEIGHT_RANK_BOX+20);}else{$(".rank_box").innerHeight(HEIGHT_RANK_BOX);}}function lazyload(a){var d={};var c=$("img[data-original]"),b=function(f){var e=f.getAttribute("data-original");if(!e){return;}f.src=e;f.removeAttribute("data-original");};c.each(function(f,e){a?setTimeout(function(){b(e);},f*a):b(e);});}