/*
 * Copyright 2011, ICAT JavaScript Library v1.1.0
 * MIT Licensed
 * @Author jndream221@gmail.com
 * @Time 2012-05-25 23:30:00
 */
(function(a,c){if(c[a]===undefined){c[a]={}}a=c[a];var f=navigator.userAgent,e=function(g){return !g?false:g.constructor===Function},b=function(h,k){for(var j=0,g=h.length;j<g;++j){if(h[j]===k){return j}}return -1},d={browser:{safari:/webkit/i.test(f),opera:/opera/i.test(f),msie:/msie/i.test(f)&&!/opera/i.test(f),mozilla:/mozilla/i.test(f)&&!/(compatible|webkit)/i.test(f)},foreach:function(n,g,k){var j,l=0,m=n.length,h=m===undefined||e(n);if(k){if(h){for(j in n){if(g.apply(n[j],k)===false){break}}}else{for(;l<m;){if(g.apply(n[l++],k)===false){break}}}}else{if(h){for(j in n){if(g.call(n[j],j,n[j])===false){break}}}else{for(;l<m;){if(g.call(n[l],l,n[l++])===false){break}}}}},mix:function(m,k,o,j){if(!k||!m){return m}if(j===undefined){j=true}var h,n,g,l=function(u,t,q,i){if(i||!(u in t)){t[u]=q[u]}};if(o&&(g=o.length)){for(h=0;h<g;h++){n=o[h];if(n in k){l(n,m,k,j)}}}else{for(n in k){l(n,m,k,j)}}return m}};d.mix(a,d);delete d;a.mix(a,{version:"1.1.0",isIE:a.browser.msie,ieVersion:a.browser.msie?f.match(/MSIE(\s)?\d+/i)[0].replace(/MSIE(\s)?/i,""):-1,isDebug:/debug/i.test(window.location.href)?true:false,__APP_MEMBERS:["namespace"],__APP_INIT_METHODS:["__init"],__init:function(){var g=this,h=(function(){var l=[],k=/\w*(\.source)?\.(js|css)/g,i=document.getElementsByTagName("script"),o;for(var m=0,n;n=i[m++];){o=!!document.querySelector?n.src:n.getAttribute("src",4);if(o&&/icat(\.source)?\.js/i.test(o)){a.refFile=a.refFile||n;l.push(n.getAttribute("data-appRef"));l.push(o.substring(0,o.indexOf("/icat/")));break}}return l})();g.widget={};g.util={};g.sysRef=g.sysRef||h[1];g.sysPlugin=g.sysRef+"/icat/"+g.version+"/plugin/";g.appRef=g.appRef||h[0];g.appPlugin=g.appRef+"/assets/plugin/";g.loadContainer=g.loadContainer||{};g.modContainer={}},isFunction:e,isString:function(g){return !g?false:typeof g==="string"},isArray:function(g){return !g?false:g.constructor===Array},isObject:function(g){return !g?false:g.constructor===Object},namespace:function(){var h=arguments,g=h.length,q=null,m,k,n;for(m=0;m<g;++m){n=(""+h[m]).split(".");q=this;for(k=(c[n[0]]===q)?1:0;k<n.length;++k){q=q[n[k]]=q[n[k]]||{}}}return q},app:function(h,k){var g=this,i=g.isString(h),j=i?c[h]||{}:h;g.mix(j,g,g.__APP_MEMBERS,true);g.mix(j,g.isFunction(k)?k():k);i&&(c[h]=j);return j},log:function(g){if(!this.isDebug){return}if(this.isIE){alert(g)}else{if(c.console!==undefined&&console.log){console.log(g)}}}});a.__init()})("ICAT",this);(function(b){var g=document,a=g.head||g.getElementsByTagName("head")[0]||g.documentElement,d=b.refFile,c=b.loadContainer,j=b.modContainer,e=function(l){if(!l){return}var k=l.replace(/\s/g,"");if(!k){return}k=/\.js|\.css/i.test(k)?k:k+".js";if(b.isDebug){k=/\.source/i.test(k)?k:k.replace(/\.js|\.css/g,/\.css/i.test(k)?".source.css":".source.js")}if(/\w?:\/\//.test(k)){return k}else{if(/\.\/(~)?/.test(k)){if(/\.\/\w+/.test(k)){k=k.replace(/\.\//g,b.appRef+"/")}if(/\.\/~/.test(k)){k=k.replace(/\.\/~/g,b.appPlugin)}}else{if(/~\w+/.test(k)){k=k.replace(/~/g,b.sysPlugin)}else{k=b.sysRef+"/"+k}}}return k};function h(k,n,l,m){this.loadQueue=k;this.callback=n||function(){};this.context=l&&b.isObject(l)?l:b;this.charest=m||"utf-8";if(l&&b.isString(l)){this.mod=l}}h.prototype={start:function(l){var k=this,l=l||k.loadQueue[0],n=k.context,m=l.replace(/[\?#].*/,""),r=function(t,s){if(t.loadQueue.length>0){t.next()}else{if(t.callback&&b.isFunction(t.callback)){t.callback(s)}if(t.mod){j[t.mod]=true}}};k.loadQueue.shift();if(c[m]){r(k,n);return this}var p,o=o||m.replace(/.*\./g,"");if(o==="css"){p=g.createElement("link");p.setAttribute("type","text/css");p.setAttribute("rel","stylesheet");p.setAttribute("href",l)}else{if(o==="js"){p=g.createElement("script");p.setAttribute("type","text/javascript");p.setAttribute("src",l);p.setAttribute("async",true)}}if(!p){return}if(k.charset){p.charset=k.charset}if(b.isIE){var q=setInterval(function(){try{document.documentElement.doScroll("left")}catch(s){return}clearInterval(q);if(o==="js"&&p.readyState){p.onreadystatechange=function(){if(p.readyState=="loaded"||p.readyState=="complete"){p.onreadystatechange=null;r(k,n)}}}a.appendChild(p)},1)}else{if(o==="js"){p.onload=function(){r(k,n)}}a.appendChild(p)}if(o==="css"){r(k,n)}c[m]=true},next:function(){var k=this;if(k.loadQueue.length>0){k.start(k.loadQueue[0])}}};b.mix(b,{include:function(p,l,n,q){if(!p||!p.length){return}var o=[],m;if(b.isString(p)){o.push(e(p));if(l){m=true}}else{if(p.length==1){m=true}if(b.isString(l)){var k=p,s=l;b.foreach(k,function(){o.push(e(s+this))})}else{b.foreach(p,function(){o.push(e(this))})}}var r=new h(o,(m?l:undefined),n,q).start();delete r},require:function(o,k,p){if(!o){return}p=b.isFunction(p)?p:function(){};var s=[];if(b.isString(k)){s.push(e(k))}else{if(b.isArray(k)){b.foreach(k,function(){s.push(e(this))})}else{if(b.isObject(k)){var q=b.isString(k.require)?[k.require]:k.require,v=k.path||"";b.foreach(q,function(){s.push(e(v+this))})}else{k=undefined}}}if(o.indexOf(":")!==-1){var u=o.split(":"),l=u[0];o=u[1]}s.push(e(o));var t=new h(s,p,l).start();delete t},use:function(l,k,n){n=n||b;var o=setInterval(function(){if(!b.modContainer[l]){return}clearInterval(o);k(n)},5)}});var i=b.refFile,f=i.getAttribute("main");if(f){f=f.indexOf("/")===-1?"./assets/js/"+f:"./"+f;b.include(f)}})(ICAT);