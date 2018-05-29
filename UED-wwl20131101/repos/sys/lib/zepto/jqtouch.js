(function(){$.jQTouch=function(v){var C=s(),B,r=C("head"),l=[],G=0,m={},D="",u="portrait",h=[],x={},i=100,J=C.jQTouch.prototype.extensions,N=[],q="",E={addGlossToIcon:true,backSelector:".back, .cancel, .goback",cacheGetRequests:true,debug:true,defaultAnimation:"slideleft",fixedViewport:true,formSelector:"form",fullScreen:true,fullScreenClass:"fullscreen",icon:null,icon4:null,preloadImages:false,startupScreen:null,statusBar:"default",submitSelector:".submit",touchSelector:"a, .touch",trackScrollPositions:true,useAnimations:true,useFastTouch:true,useTouchScroll:true,animations:[{name:"cubeleft",selector:".cubeleft, .cube",is3d:true},{name:"cuberight",selector:".cuberight",is3d:true},{name:"dissolve",selector:".dissolve"},{name:"fade",selector:".fade"},{name:"flipleft",selector:".flipleft, .flip",is3d:true},{name:"flipright",selector:".flipright",is3d:true},{name:"pop",selector:".pop",is3d:true},{name:"swapleft",selector:".swap",is3d:true},{name:"slidedown",selector:".slidedown"},{name:"slideright",selector:".slideright"},{name:"slideup",selector:".slideup"},{name:"slideleft",selector:".slideleft, .slide, #jqt > * > ul li a"}]};function s(){var O;if(!!window.Zepto){O=window.Zepto;O.fn.prop=O.fn.attr;Event.prototype.isDefaultPrevented=function(){return this.defaultPrevented}}else{if(!!window.jQuery){O=window.jQuery}else{throw ("Either Zepto or jQuery is required but neither can be found.")}}return O}function j(O){if(window.console!==undefined&&m.debug===true){console.warn(O)}}function b(O){if(typeof(O.selector)==="string"&&typeof(O.name)==="string"){N.push(O)}}function k(P,O){l.unshift({page:P,animation:O,hash:"#"+P.attr("id"),id:P.attr("id")})}function o(P){var O=C(P.target);if(!O.is(h.join(", "))){O=C(P.target).closest(h.join(", "))}if(O&&O.prop("href")&&!O.isExternalLink()){j("Need to prevent default click behavior");P.preventDefault()}else{j("No need to prevent default click behavior")}if(C.support.touch){j("Not converting click to a tap event because touch handler is on the job")}else{j("Converting click event to a tap event because touch handlers are not present or off");C(P.target).trigger("tap",P)}}function c(V,Q,U,P){P=P?P:false;if(Q===undefined||Q.length===0){C.fn.unselect();j("Target element is missing.");return false}if(Q.hasClass("current")){C.fn.unselect();j("You are already on the page you are trying to navigate to.");return false}C(":focus").trigger("blur");V.trigger("pageAnimationStart",{direction:"out",back:P});Q.trigger("pageAnimationStart",{direction:"in",back:P});if(C.support.animationEvents&&U&&m.useAnimations){if(!C.support.transform3d&&U.is3d){j("Did not detect support for 3d animations, falling back to "+m.defaultAnimation);U.name=m.defaultAnimation}var O=U.name,T=U.is3d?"animating3d":"";if(P){O=O.replace(/left|right|up|down|in|out/,e)}j("finalAnimationName is "+O);V.bind("webkitAnimationEnd",R);B.addClass("animating "+T);var S=window.pageYOffset;if(m.trackScrollPositions===true){Q.css("top",window.pageYOffset-(Q.data("lastScroll")||0))}Q.addClass(O+" in current");V.addClass(O+" out");if(m.trackScrollPositions===true){V.data("lastScroll",S);C(".scroll",V).each(function(){C(this).data("lastScroll",this.scrollTop)})}}else{Q.addClass("current in");R()}function R(X){var W=i;if(C.support.animationEvents&&U&&m.useAnimations){V.unbind("webkitAnimationEnd",R);V.removeClass("current "+O+" out");Q.removeClass(O);B.removeClass("animating animating3d");if(m.trackScrollPositions===true){Q.css("top",-Q.data("lastScroll"));setTimeout(function(){Q.css("top",0);window.scroll(0,Q.data("lastScroll"));C(".scroll",Q).each(function(){this.scrollTop=-C(this).data("lastScroll")})},0)}}else{V.removeClass(O+" out current");W+=260}setTimeout(function(){Q.removeClass("in")},W);D=Q;if(P){l.shift()}else{k(D,U)}V.unselect();n(D.attr("id"));Q.trigger("pageAnimationEnd",{direction:"in",animation:U});V.trigger("pageAnimationEnd",{direction:"out",animation:U})}return true}function e(O){var P={up:"down",down:"up",left:"right",right:"left","in":"out",out:"in"};return P[O]||O}function M(){return u}function g(){if(l.length<1){j("History is empty.")}if(l.length===1){j("You are on the first panel.");window.history.go(-1)}var P=l[0],O=l[1];if(c(P.page,O.page,P.animation,true)){return x}else{j("Could not go back.");return false}}function I(R,S){var T=l[0].page;if(typeof S==="string"){for(var Q=0,O=N.length;Q<O;Q++){if(N[Q].name===S){S=N[Q];break}}}if(typeof R==="string"){var P=C(R);if(P.length<1){d(R,{animation:S});return}else{R=P}}if(c(T,R,S)){return x}else{j("Could not animate pages.");return false}}function L(O){if(location.hash===l[0].hash){j("We are on the right panel");return true}else{if(location.hash===""){g();return true}else{if((l[1]&&location.hash===l[1].hash)){g();return true}else{j("Could not find ID in history, just forwarding to DOM element.");I(C(location.hash),m.defaultAnimation)}}}}function K(O){m=C.extend({},E,O);if(m.preloadImages){for(var P=m.preloadImages.length-1;P>=0;P--){(new Image()).src=m.preloadImages[P]}}var Q=(m.addGlossToIcon)?"":"-precomposed";if(m.icon){q+='<link rel="apple-touch-icon'+Q+'" href="'+m.icon+'" />'}if(m.icon4){q+='<link rel="apple-touch-icon'+Q+'" sizes="114x114" href="'+m.icon4+'" />'}if(m.startupScreen){q+='<link rel="apple-touch-startup-image" href="'+m.startupScreen+'" />'}if(m.fixedViewport){q+='<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>'}if(m.fullScreen){q+='<meta name="apple-mobile-web-app-capable" content="yes" />';if(m.statusBar){q+='<meta name="apple-mobile-web-app-status-bar-style" content="'+m.statusBar+'" />'}}if(q){r.prepend(q)}}function a(Q){var R;for(var P=0,O=N.length;P<O;P++){if(Q.is(N[P].selector)){R=N[P];break}}if(!R){j("Animation could not be found. Using "+m.defaultAnimation+".");R=m.defaultAnimation}return R}function z(O,P){var Q=null;var R=document.createElement("div");R.innerHTML=O;C(R).children().each(function(T,U){var S=C(this);if(!S.attr("id")){S.attr("id","page-"+(++G))}C("#"+S.attr("id")).remove();B.append(S);B.trigger("pageInserted",{page:S});if(S.hasClass("current")||!Q){Q=S}});if(Q!==null){I(Q,P);return Q}else{return false}}function f(){B.css("minHeight",1000);scrollTo(0,0);var O=window.innerHeight;B.css("minHeight",O);u=Math.abs(window.orientation)==90?"landscape":"portrait";B.removeClass("portrait landscape").addClass(u).trigger("turn",{orientation:u})}function n(O){location.hash="#"+O.replace(/^#/,"")}function d(O,P){var R={data:null,method:"GET",animation:null,callback:null,$referrer:null};var Q=C.extend({},R,P);if(O!="#"){C.ajax({url:O,data:Q.data,type:Q.method,success:function(S){var T=z(S,Q.animation);if(T){if(Q.method=="GET"&&m.cacheGetRequests===true&&Q.$referrer){Q.$referrer.attr("href","#"+T.attr("id"))}if(Q.callback){Q.callback(true)}}},error:function(S){if(Q.$referrer){Q.$referrer.unselect()}if(Q.callback){Q.callback(false)}}})}else{if(Q.$referrer){Q.$referrer.unselect()}}}function p(P,Q){C(":focus").trigger("blur");P.preventDefault();var O=(typeof(P)==="string")?C(P).eq(0):(P.target?C(P.target):C(P));if(O.length&&O.is(m.formSelector)&&O.attr("action")){d(O.attr("action"),{data:O.serialize(),method:O.attr("method")||"POST",animation:a(O),callback:Q});return false}return true}function H(P){var O=P.closest("form");if(O.length===0){j("No parent form found")}else{j("About to submit parent form");O.trigger("submit");return false}return true}function A(){var Q,P,R,S,O;Q=document.getElementsByTagName("head")[0];P=document.body;R=document.createElement("style");R.textContent="@media (transform-3d),(-o-transform-3d),(-moz-transform-3d),(-webkit-transform-3d){#jqt-3dtest{height:3px}}";S=document.createElement("div");S.id="jqt-3dtest";Q.appendChild(R);P.appendChild(S);O=S.offsetHeight===3;R.parentNode.removeChild(R);S.parentNode.removeChild(S);j("Support for 3d transforms: "+O);return O}function y(){var P=false;var O=/OS (\d+)(_\d+)* like Mac OS X/i;var Q=window.navigator.userAgent;if(O.test(Q)){P=(O.exec(Q)[1]>=5)}return P}function t(Q){var P=C(Q.target),O=h.join(", ");if(!P.is(O)){P=P.closest(O)}if(P.length&&P.prop("href")){P.addClass("active")}P.on(C.support.touch?"touchmove":"mousemove",function(){P.removeClass("active")});P.on("touchend",function(){P.unbind("touchmove mousemove")})}function F(T){if(T.isDefaultPrevented()){return true}var P=C(T.target);if(!P.is(h.join(", "))){P=P.closest(h.join(", "))}if(!P.length||!P.prop("href")){j("Could not find a link related to tapped element");return false}var S=P.attr("target"),R=P.prop("hash"),O=P.prop("href"),Q=null;if(P.isExternalLink()){P.unselect();return true}else{if(P.is(m.backSelector)){g(R)}else{if(P.is(m.submitSelector)){H(P)}else{if(S==="_webapp"){window.location=O;return false}else{if(O==="#"){P.unselect();return true}else{Q=a(P);if(R&&R!=="#"){P.addClass("active");j(P.attr("data-animation"));I(C(R).data("referrer",P),P.attr("data-animation")||Q,P.hasClass("reverse"));return false}else{P.addClass("loading active");d(P.prop("href"),{animation:Q,callback:function(){P.removeClass("loading");setTimeout(C.fn.unselect,250,P)},$referrer:P});return false}}}}}}}K(v);C(document).ready(function w(){if(!C.support){C.support={}}C.support.animationEvents=(typeof window.WebKitAnimationEvent!="undefined");C.support.touch=(typeof window.TouchEvent!="undefined")&&(window.navigator.userAgent.indexOf("Mobile")>-1)&&m.useFastTouch;C.support.transform3d=A();C.support.ios5=y();if(!C.support.touch){j("This device does not support touch interaction, or it has been deactivated by the developer. Some features might be unavailable.")}if(!C.support.transform3d){j("This device does not support 3d animation. 2d animations will be used instead.")}C.fn.isExternalLink=function(){var W=C(this);return(W.attr("target")=="_blank"||W.attr("rel")=="external"||W.is('a[href^="http://maps.google.com"], a[href^="mailto:"], a[href^="tel:"], a[href^="javascript:"], a[href*="youtube.com/v"], a[href*="youtube.com/watch"]'))};C.fn.makeActive=function(){return C(this).addClass("active")};C.fn.unselect=function(W){if(W){W.removeClass("active")}else{C(".active").removeClass("active")}};for(var R=0,O=J.length;R<O;R++){var U=J[R];if(C.isFunction(U)){C.extend(x,U(x))}}for(var Q=0,P=E.animations.length;Q<P;Q++){var V=E.animations[Q];if(m[V.name+"Selector"]!==undefined){V.selector=m[V.name+"Selector"]}b(V)}h.push(m.touchSelector);h.push(m.backSelector);h.push(m.submitSelector);C(h.join(", ")).css("-webkit-touch-callout","none");B=C("#jqt");var T=[];if(B.length===0){j('Could not find an element with the id "jqt", so the body id has been set to "jqt". If you are having any problems, wrapping your panels in a div with the id "jqt" might help.');B=C(document.body).attr("id","jqt")}if(C.support.transform3d){T.push("supports3d")}if(C.support.ios5&&m.useTouchScroll){T.push("touchscroll")}if(m.fullScreenClass&&window.navigator.standalone===true){T.push(m.fullScreenClass,m.statusBar)}B.addClass(T.join(" ")).bind("click",o).bind("orientationchange",f).bind("submit",p).bind("tap",F).bind(C.support.touch?"touchstart":"mousedown",t).trigger("orientationchange");C(window).bind("hashchange",L);var S=location.hash;if(C("#jqt > .current").length===0){D=C("#jqt > *:first-child").addClass("current")}else{D=C("#jqt > .current")}n(D.attr("id"));k(D);if(C(S).length===1){I(S)}});x={addAnimation:b,animations:N,getOrientation:M,goBack:g,insertPages:z,goTo:I,history:l,settings:m,submitForm:p};return x};$.jQTouch.prototype.extensions=[];$.jQTouch.addExtension=function(a){$.jQTouch.prototype.extensions.push(a)}})();
