(function(a){a.PathConfig();a.app("GameApk",function(){function b(){var f=window.location.search,c="";if(!f){return{version:"v1",sp:c};}else{f=f.substring(1);var d=f.split("&");for(var e=0;e<d.length;e++){if(!d[e]){continue;}if(d[e].split("=")[0]=="sp"){c=d[e].split("=")[1];break;}}}if(!c){return{version:"v1",sp:c};}else{if(c.split("_").length<2||c.split("_")[1]==""){return{version:"v1",sp:c};}else{var g=c.split("_")[1].split(".");if(g[0]>1||(g[0]==1&&g[1]>4)||(g[0]==1&&g[1]==4&&g[2]>7)){return{version:"v2",sp:c};}return{version:"v1",sp:c};}}}a.ModConfig({"$jQuery":"../../lib/jquery/jquery.js",phoneGap:"./plugin/cordova-2.0.0.js","$touchSwipe":"../../lib/jquery/touchSwipe.js"});return{spParam:b().sp,ApiVersion:b().version,blankPic:a.PathConfig.picPath+"blank.gif",loadingPic:a.PathConfig.picPath+"loading_img.gif"};});a.require({modName:"appmvc",domReady:false,isCombo:!(a.DebugMode||a.DemoMode),callback:function(){var b=new GameApk.controller("mc");a.include(["jQuery","phoneGap","./game.js"],function(c){b.bindEvents();Gapk.init();},true,!(a.DebugMode||a.DemoMode));}});})(ICAT);