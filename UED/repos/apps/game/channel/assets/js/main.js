(function(a){isAla=location.href.indexOf("ala")>-1?true:false;a.PathConfig();var c;var b;if(isAla){c=a.PathConfig.appRef.replace(/assets\/js\//g,"pic/blank.gif");b=a.PathConfig.appRef.replace(/assets\/js\//g,"diff/ala/img/loading_new.gif");a.include("../../diff/ala/css/patch.css");}a.app("GameApk",function(){return{version:"ala_1.4.7",blankPic:c,loadingPic:b};});a.include(["./plugin/cordova-2.0.0.js!","lib/jquery/jquery.js"],function(){a.require({modName:"appMVC",callback:function(){a.include("./game.js",function(){Gapk.init();},true);}});},true);})(ICAT);