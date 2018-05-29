/*!
 * Copyright 2011~2012, ICAT JavaScript Library v1.1.3
 * https://github.com/valleykid/icat
 *
 * Copyright (c) 2012 valleykid
 * Licensed under the MIT license.
 *
 * @Author valleykiddy@gmail.com
 * @Time 2012-11-29 19:52:00
 */
(function(){var c=this,b={};if(typeof exports!=="undefined"){if(typeof module!=="undefined"&&module.exports){exports=module.exports=b}exports.ICAT=b}else{c.ICAT=b}var g=navigator.userAgent,d=Object.prototype,e=d.toString,a=Array.prototype,f=["namespace"];String.prototype.trim=function(){return this.replace(/(^\s*)|(\s*$)/g,"")};a.hasItem=function(l){for(var k=0,j=this,h=j.length;k<h;k++){if(j[k]==l){return true}}return false};a.removeItem=function(l){for(var k=0,j=this,h=j.length;k<h;k++){if(j[k]==l){j.splice(k,1)}}return j};a.unique=function(){var j=this,m={},l=[];for(var k=0,h=j.length;k<h;k++){if(!m[j[k]]){l.push(j[k]);m[j[k]]=true}}return l};b.mix=function(m,l,o,k){if(!l||!m){return m}if(!k){k=true}var j,n,h;if(o&&(h=o.length)){for(j=0;j<h;j++){n=o[j];if(n in l){if(k||!(n in m)){m[n]=l[n]}}}}else{for(n in l){if(k||!(n in m)){m[n]=l[n]}}}return m};b.mix(b,{version:"1.1.3",isDebug:/debug/i.test(c.location.href),browser:{safari:/webkit/i.test(g),opera:/opera/i.test(g),msie:/msie/i.test(g)&&!/opera/i.test(g),mozilla:/mozilla/i.test(g)&&!/(compatible|webkit)/i.test(g)},isFunction:function(h){return e.call(h)=="[object Function]"},isString:function(h){return e.call(h)=="[object String]"},isArray:Array.isArray||function(h){return e.call(h)=="[object Array]"},isObject:function(h){return e.call(h)=="[object Object]"},isNull:function(h){return h===null},isEmptyObject:function(i){for(var h in i){return false}return true},throttle:function(k){var n=null,i;var l=k.fn,j=k.context,h=k.delay||100,m=k.mustRunDelay;return function(){var p=arguments,o=+new Date();j=j||this;clearTimeout(n);if(!i){i=o}if(m&&o-i>=m){l.apply(j,p);i=o}else{n=setTimeout(function(){l.apply(j,p)},h)}}},foreach:function(p,h,l){var k,m=0,n=p.length,j=n===undefined||b.isString(p);if(l){if(j){for(k in p){if(h.apply(p[k],l)===false){break}}}else{for(;m<n;){if(h.apply(p[m++],l)===false){break}}}}else{if(j){for(k in p){if(h.call(p[k],k,p[k])===false){break}}}else{for(;m<n;){if(h.call(p[m],m,p[m++])===false){break}}}}},Class:function(){var j=arguments,h=j.length;if(h==0){return null}else{if(h==1){var i=j[0];if(!b.isObject(i)){return null}else{function l(){i.Create.apply(this,arguments)}b.foreach(i,function(o,n){if(o!="Create"){l.prototype[o]=n}});return l}}else{if(h>=2){var m=j[0],i=j[1],k=j[2]||c;if(!b.isString(m)||!b.isObject(i)){return null}else{function l(){i.Create.apply(this,arguments)}b.foreach(i,function(o,n){if(o!="Create"){l.prototype[o]=n}});k[m]=l}}}}},widget:function(i,h){this.Class(i,h,b.widget)},util:function(h,i){b.util[h]=i},namespace:function(){var k=arguments,h=k.length,r=null,n,m,q;for(n=0;n<h;++n){q=(""+k[n]).split(".");r=this;for(m=(c[q[0]]===r)?1:0;m<q.length;++m){r=r[q[m]]=r[q[m]]||{}}}return r},app:function(i,l){var h=this,j=h.isString(i),k=j?c[i]||{}:i;h.mix(k,h,f,true);h.mix(k,h.isFunction(l)?l():l);j&&(c[i]=k);return k},log:function(h){c.console!==undefined&&console.log?console.log(h):alert(h)}})}).call(this);(function(e){var v=document,p=v.head||v.getElementsByTagName("head")[0],s=v.getElementsByName("appRef")[0],l,b,o,f,c,g,t,a,u,j,n,d,m={},r={};(function(x){l=x[x.length-1];b=l.hasAttribute?l.src:l.getAttribute("src",4);u=l.getAttribute("corelib")||"";j=l.getAttribute("asyn-corelib")||"";n=l.getAttribute("main")||"";d=l.getAttribute("asyn-main")||"";c=/^\.{2}\//.test(n||d);if(b.indexOf(",")<0){if(/\?[vt]=\d+/.test(b)){o=b.replace(/.*\?/,"?");b=b.replace(/\?.*/,"")}}else{var A=b.replace(/\/\?{2}/,"?").split("?"),y=A[1].split(","),w=y.length,z=0;do{if(/icat\//i.test(y[z])){b=A[0]+y[z]}if(/apps\//i.test(y[z])){c=/assets\//i.test(y[z])}z++}while(z<=w-1);if(A[2]){o="?"+A[2]}}})(v.getElementsByTagName("script"));f=/\/sys\/icat\//i.test(b);e.modsConfig={};e.sysRef=f?b.replace(/\/sys\/.*/,"/sys"):b.replace(/\/\w*\.js/,"");e.appRef=s?s.content:e.sysRef;e.libRef=e.sysRef+"/lib";a=s?s.getAttribute("corecss"):"";g=f?b.replace(/icat\..*/,"plugin/"):e.sysRef+"plugin/";t=e.appRef+(c?"/assets":"")+"/plugin/";e.config=function(w){e.modsConfig[w.modName]=[];e.foreach(w.paths,function(y,x){e.modsConfig[w.modName].push((w.baseUrl||"")+x)})};var i=function(A){if(!A){return}var y=A.replace(/\s|[\?#].*/g,""),z=y.replace(/.*\./g,""),w=z=="css";if(!y){return}if(e.isDebug){y=/\.source/i.test(y)?y:(w?y.replace(/\.css/g,".source.css"):y.replace(/\.js/g,".source.js"))}if(/^(http|ftp|https):\/\/.*/i.test(y)){return y}else{if(/^\.{1,2}(~)?\//.test(y)){if(/^\.\//.test(y)){y=y.replace(/^\./,e.appRef)}if(/^\.{2}\//.test(y)){y=y.replace(/^\.{2}/,e.appRef+(w?"/assets/css":"/assets/js"))}if(/^\.~\//.test(y)){y=y.replace(/^\.~\//,t)}}else{if(/^\/{1,2}/.test(y)){if(/^\/{2}/.test(y)){var x=y.replace(/^\/{2}|.source|.css|.js/ig,"");x=/\d|\./.test(x)?x.replace(/\d(\/)?|\./g,""):x;y=y.replace(/^\//,e.libRef+"/"+x)}else{y=y.replace(/^\//,e.libRef+"/")}}else{if(/^~\//.test(y)){y=y.replace(/^~\//,g)}else{y=e.sysRef+"/"+y}}}}return y+(o||"")},k=function(D){var z=D,A=z.replace(/[\?#].*/,"");if(m[A]){return}var B=A.replace(/.*\./g,""),y=B=="css",x=y?"link":"script",w=y?' type="text/css" rel="stylesheet"':' type="text/javascript"',C=(y?"href":"src")+'="'+z+'"';v.write("<"+x+w+C+(y?"/>":"></"+x+">"));m[A]=true},h=function(z,w,y,x){if(w&&e.isFunction(w)){w(x||e)}if(y){r[y]=true;e.modsConfig[y]=e.modsConfig[y]?e.modsConfig[y]:[];e.modsConfig[y].push(z)}},q=function(A,F,D,y){var w=A,E=l.parentNode||p,B=w.replace(/[\?#].*/,"");if(m[B]){h(A,F,D,y);return}var z,C=B.replace(/.*\./g,"");if(C==="css"){z=v.createElement("link");z.setAttribute("type","text/css");z.setAttribute("rel","stylesheet");z.setAttribute("href",w)}else{if(C==="js"){z=v.createElement("script");z.setAttribute("type","text/javascript");z.setAttribute("src",w);z.setAttribute("async",true)}}if(!z){return}if(e.browser.msie){var x=setInterval(function(){try{document.documentElement.doScroll("left")}catch(G){return}clearInterval(x);if(C==="js"&&z.readyState){z.onreadystatechange=function(){if(z.readyState=="loaded"||z.readyState=="complete"){z.onreadystatechange=null;h(A,F,D,y);m[B]=true}}}E.appendChild(z)},1)}else{if(C==="js"){z.onload=function(){h(A,F,D,y);m[B]=true}}E.appendChild(z)}if(C==="css"){setTimeout(function(){h(A,F,D,y)},5);m[B]=true;E.appendChild(z)}};e.mix(e,{inc:function(w){if(!w){return}w=e.isString(w)?[w]:w;e.foreach(w,function(y,x){if(!x){return}k(i(x))})},incfile:function(A,w,z,y,x){if(!A){return}A=e.isString(A)?[A]:A;(function(){if(A.length){var C=A.shift();C=i(C)}else{return}if(A.length){var B=arguments.callee;if(z){q(C,function(){B(A)},undefined,x)}else{q(C,undefined,undefined,x);B(A)}}else{q(C,w,undefined,x)}})()},require:function(x,A,w,z,y){if(!A){return}A=e.isString(A)?[A]:A;if(r[x]){if(w){w(y)}}else{(function(){if(A.length){var C=A.shift();C=i(C)}else{return}if(A.length){var B=arguments.callee;q(C,function(){B(A)},x,y)}else{q(C,w,x,y)}})()}},use:function(x,w,A,z){var y=0,A=A||100,B;if(r[x]){if(w){w(z)}}else{if(e.modsConfig[x]){B=setInterval(function(){y+=5;if(r[x]){clearInterval(B);if(w){w(z)}}else{if(y>=A){clearInterval(B);e.require(x,e.modsConfig[x],w,z)}}},5)}}}});if(a){e.inc(a)}if(u){u=u.split(",");e.inc(u)}else{if(j){j=j.split(",");e.incfile(j,undefined,true)}}if(n){e.inc(n)}else{if(d){e.incfile(d)}}})(ICAT);(function(e,j){e.namespace("Dom");var g=e.Dom,j=document,h=Array.prototype.slice,l=j.createElement("div").style,k=function(){var p=l,q=["t","WebkitT","MozT","msT","OT"],o;for(var n=0,m=q.length;n<m;n++){o=q[n]+"ransform";if(o in p){return q[n].slice(0,-1)}}return false}(),i=k?"-"+k.toLowerCase()+"-":"",a=!!k,b=k+"Transition" in l,c=k+"BorderImage" in l;function d(p,n){var m=j.documentElement,o=m.matchesSelector||m.mozMatchesSelector||m.webkitMatchesSelector||m.oMatchesSelector||m.msMatchesSelector;return o.call(p,n)}e.mix(g,{one:function(n,m){return !n?j:(m||j).querySelector(n)},all:function(n,m){return !n?[j]:h.call((m||j).querySelectorAll(n))},filter:function(m,n){if(!m.length||!n){return m}var o=[];m.forEach(function(p){if(e.isString(n)&&d(p,n)){o.push(p)}if(n.nodeType&&p==n){o.push(p)}});return o},not:function(m,n){if(!m.length||!n){return m}var o=[];m.forEach(function(p){if(e.isString(n)&&!d(p,n)){o.push(p)}if(n.nodeType&&p!=n){o.push(p)}});return o},index:function(n,m){}});e.mix(g,{parent:function(p){if(!p){return null}if(!e.isArray(p)){return p.parentNode}else{if(!p.length){return null}var m=[];for(var o=0,n=p.length;o<n;o++){var q=arguments.callee(p[o]);if(!m.hasItem(q)){m.push(q)}}return m}},parents:function(q,p){if(!q){return null}if(!e.isArray(q)){if(!p||typeof p=="number"){p=p||1;for(var o=0;o<p;o++){if(!e.isNull(q)){q=q.parentNode}}}else{(function(){q=q.parentNode;if(e.isNull(q)){return}if(d(q,p)){return}else{arguments.callee()}})()}return q}else{if(!q.length){return null}var m=[];for(var o=0,n=q.length;o<n;o++){var r=arguments.callee(q[o],p);if(!m.hasItem(r)){m.push(r)}}return m}},children:function(m,u){if(!m){return null}if(!e.isArray(m)){var v=m.childNodes,n=v.length,p=[];for(var o=0;o<n;o++){var q=v[o];if(!u){if(q.nodeType==1&&!p.hasItem(q)){p.push(q)}}else{if(q.nodeType==1&&d(q,u)&&!p.hasItem(q)){p.push(q)}}}return p}else{if(!m.length){return null}var r=[];for(var o=0,n=m.length;o<n;o++){var t=arguments.callee(m[o],u);r=r.concat(t)}return r}},siblings:function(r,q){if(!r){return null}if(!e.isArray(r)){var u=r.parentNode,v=g.children(u,q);return g.not(v,r)}else{if(!r.length){return null}var n=[];for(var o=0,m=r.length;o<m;o++){var t=arguments.callee(r[o],q);n=n.concat(t)}return n}},prev:function(q,p){if(!q){return null}if(!e.isArray(q)){if(!p){do{q=q.previousSibling}while(q&&q.nodeType!=1)}else{(function(){q=q.previousSibling;if(e.isNull(q)){return}if(q.nodeType==1&&d(q,p)){return}else{arguments.callee()}})()}return q}else{if(!q.length){return null}var m=[];for(var o=0,n=q.length;o<n;o++){var r=arguments.callee(q[o],p);if(!m.hasItem(r)){m.push(r)}}return m}},next:function(q,p){if(!q){return null}if(!e.isArray(q)){if(!p){do{q=q.nextSibling}while(q&&q.nodeType!=1)}else{(function(){q=q.nextSibling;if(e.isNull(q)){return}if(q.nodeType==1&&d(q,p)){return}else{arguments.callee()}})()}return q}else{if(!q.length){return null}var m=[];for(var o=0,n=q.length;o<n;o++){var r=arguments.callee(q[o],p);if(!m.hasItem(r)){m.push(r)}}return m}},first:function(p){if(!p){return null}if(!e.isArray(p)){p=p.firstChild;return p&&p.nodeType!=1?g.next(p):p}else{if(!p.length){return null}var m=[];for(var o=0,n=p.length;o<n;o++){var q=arguments.callee(p[o]);if(!m.hasItem(q)){m.push(q)}}return m}},last:function(p){if(!p){return null}if(!e.isArray(p)){p=p.lastChild;return p&&p.nodeType!=1?g.prev(p):p}else{if(!p.length){return null}var m=[];for(var o=0,n=p.length;o<n;o++){var q=arguments.callee(p[o]);if(!m.hasItem(q)){m.push(q)}}return m}},closest:function(n,m){}});e.mix(g,{hasClass:function(p,q){if(!p){return null}if(!e.isArray(p)){p=e.isArray(p)?p[0]:p;return new RegExp("(?:^|\\s+)"+q+"(?:\\s+|$)").test(p.className)}else{if(!p.length){return null}var m=[];for(var o=0,n=p.length;o<n;o++){m.push(arguments.callee(p[o]))}return m}},addClass:function(o,p){if(!o){return}if(!e.isArray(o)){if(!g.hasClass(o,p)){var q=o.className;q=!q?p:[q,p].join(" ");o.className=q.trim()}}else{if(!o.length){return}for(var n=0,m=o.length;n<m;n++){arguments.callee(o[n],p)}}},removeClass:function(o,p){if(!o){return}if(!e.isArray(o)){if(g.hasClass(o,p)){var q=o.className;q=q.replace(new RegExp("(?:^|\\s+)"+p+"(?:\\s+|$)","g")," ");o.className=q.trim()}}else{if(!o.length){return}for(var n=0,m=o.length;n<m;n++){arguments.callee(o[n],p)}}},replaceClass:function(p,o,q){if(!p){return}if(!e.isArray(p)){if(g.hasClass(p,o)){var r=p.className;r=r.replace(new RegExp("(?:^|\\s+)"+o+"(?:\\s+|$)","g")," "+q+" ");p.className=r.trim()}}else{if(!p.length){return}for(var n=0,m=p.length;n<m;n++){arguments.callee(p[n],o,q)}}},toggleClass:function(o,p){if(!o){return}if(!e.isArray(o)){g[g.hasClass(o,p)?"removeClass":"addClass"](o,p)}else{if(!o.length){return}for(var n=0,m=o.length;n<m;n++){arguments.callee(o[n],p)}}},attr:function(){},removeAttr:function(){},css:function(){function n(t){switch(t){case"float":return("cssFloat" in j.body.style)?"cssFloat":"styleFloat";break;case"opacity":return("opacity" in j.body.style)?"opacity":{get:function(u,p){var v=p.filter;return v&&v.indexOf("opacity")>=0&&parseFloat(v.match(/opacity=([^)]*)/i)[1])/100+""||"1"},set:function(p,u){p.style.filter="alpha(opacity="+u*100+")";p.style.zoom=1}};break;default:if(t.indexOf("-")>-1){var q=t.split("-");for(var s=0,r=q.length;s<r;s++){if(q[s]=="webkit"||q[s]=="ms"||q[s]=="moz"||q[s]=="o"){continue}q[s]=q[s].substring(0,1).toUpperCase()+q[s].substring(1)}t=q.join("")}return t;break}}function m(u,w){if(!u){return null}if(!e.isArray(u)){w=n(w);var x=u.style[w];if(!x){var t=j.defaultView&&j.defaultView.getComputedStyle&&getComputedStyle(u,null)||u.currentStyle||u.style;x=e.isString(w)?t[w]:w.get(u,t)}return x=="auto"?"":x}else{if(!u.length){return null}var q=[];for(var s=0,r=u.length;s<r;s++){var v=arguments.callee(u[s],w);q.push(v)}return q}}function o(s,t){if(!s||!e.isObject(t)){return}if(!e.isArray(s)){var p;e.foreach(t,function(w,u){p=n(w);e.isString(p)?s.style[p]=u:p.set(s,u)})}else{if(!s.length){return}for(var r=0,q=s.length;r<q;r++){arguments.callee(s[r],t)}}}return function(q,p){if(e.isString(p)){return m(q,p)}else{o(q,p)}}}(),position:function(){function n(s){if(!s){return null}if(!e.isArray(s)){var p=0,u=0;do{p+=s.offsetLeft||0;u+=s.offsetTop||0;s=s.offsetParent}while(s);return{left:p,top:u}}else{if(!s.length){return null}var o=[];for(var r=0,q=s.length;r<q;r++){var t=arguments.callee(s[r]);o.push(t)}return o}}function m(r,v){if(!r){return}if(!e.isArray(r)){v=typeof v=="number"?v+"px":v;if(e.isString(v)){return m(r,{left:v})}var p={},t=typeof v.left!="undefined",s=typeof v.top!="undefined",u=/absolute|relative/i.test(g.css(r,"position"));if(b&&!u){if(t&&s){p[i+"transform"]="translate("+v.left+", "+v.top+")"}else{if(t){p[i+"transform"]="translateX("+v.left+")"}if(s){p[i+"transform"]="translateY("+v.top+")"}}}else{if(t){p.left=v.left}if(s){p.top=v.top}}g.css(r,p)}else{if(!r.length){return}for(var q=0,o=r.length;q<o;q++){arguments.callee(r[q],v)}}}return function(o,p){if(typeof p=="undefined"){return n(o)}else{m(o,p)}}}(),offset:function(){function m(r){if(!r){return null}if(!e.isArray(r)){var o=r.offsetLeft||0,t=r.offsetTop||0;return{left:o,top:t}}else{if(!r.length){return null}var n=[];for(var q=0,p=r.length;q<p;q++){var s=arguments.callee(r[q]);n.push(s)}return n}}return function(n,o){return typeof o=="undefined"?m(n):g.position(n,o)}}(),width:function(n,m){return g.css(n,m?{width:m}:"width")},height:function(n,m){return g.css(n,m?{height:m}:"height")}});e.mix(g,{html:function(n,m){},before:function(){},after:function(){}});var f=e.$=function(n,m){return new f.fn.init(n,m)};f.fn=f.prototype={constructor:f,init:function(n,m){if(!n){return this}if(n.nodeType){this.selector=[n];return this}if(e.isString(n)){this.selector=g.all(n,m);return this}else{if(e.isArray(n)){this.selector=n.length?n:[j];return this}}},size:function(){return this.selector.length}};f.fn.init.prototype=f.fn;e.foreach(g,function(n,m){if(n=="one"||n=="all"){return}f.fn[n]=function(){var o=h.call(arguments),s=this.selector,q,t;if(!s.length){return this}o.unshift(s);var u=m.apply(s||this,o);if(u){q=[];for(var r=0,p=u.length;r<p;r++){if(!e.isNull(u[r])&&u[r].nodeType){q.push(u[r]);t=true}}q=t?f(q):u}else{q=this}return q}});f.extend=f.fn.extend=function(n){if(!e.isObject(n)){return this}var m=this;e.foreach(n,function(p,o){if(e.isFunction(o)){m[p]=function(){return o.apply(this.selector||m,arguments)}}else{m[p]=o}});return m};f.fn.extend({get:function(m){return m==null?h.call(this):(m<0?this[this.length+m]:this[m])}})})(ICAT,document);(function(a){a.namespace("Event");function d(k,i){var h=document.documentElement,j=h.matchesSelector||h.mozMatchesSelector||h.webkitMatchesSelector||h.oMatchesSelector||h.msMatchesSelector;return j.call(k,i)}function c(h){return"tagName" in h?h:h.parentNode}a.Class("Observer",{Create:function(h){this.selectors=[];this.events={};this.pageid=h},subscribe:function(i){var h=this;if(!i){return h}i=a.isArray(i)?i:[i];a.foreach(i,function(m,j){if(!h.selectors.hasItem(j.el)){h.selectors.push(j.el)}var l=j.el.trim()+"|"+(j.stopDefault?1:0)+"|"+(j.stopBubble?1:0),k=j.eType.trim();switch(k){case"click":k="tap";break;case"longClick":k="longTap";break;case"doubleClick":k="doubleTap";break;case"singleClick":k="singleTap";break;case"moving":k="swiping";break}if(!h.events[k]){h.events[k]={}}if(!h.events[k][l]){h.events[k][l]=j.callback}});return h},unsubscribe:function(i){var h=this;if(!i){h.events={}}else{i=a.isArray(i)?i:[i];i.forEach(function(j){if(j.indexOf("|")>0){j=j.split("|");delete h.events[j[1].trim()][j[0].trim()]}else{delete h.events[j.trim()]}})}return h},execute:function(n,p,i){var l=this,o,m=l.events[n];if(!m){return}for(o in m){var j=o.split("|"),h=false;(function(q,k){if(d(q,j[0])){k.apply(q,i);if(j[1]==0){a.Event.triggerEvent(q,"click",false,true)}h=true}else{if(q.parentNode!==g.body){arguments.callee(q.parentNode,k)}}})(p,m[o]);if(h&&j[2]==1){return}}},setCurrent:function(){a.__OBSERVER_PAGEID=this.pageid;return this},on:function(h,j,l,k,i){return this.subscribe({el:h,eType:j,callback:l,stopDefault:k,stopBubble:i})},off:function(h,i){return this.unsubscribe(h+"|"+i)}});a.obsCreate=function(h){if(!a.obsCreate[h]){a.obsCreate[h]=new Observer(h)}return a.obsCreate[h]};a.obsDestroy=function(h){a.obsCreate[h]=null;a.__OBSERVER_PAGEID="__PAGE_EVENT"};var b=a.Event=a.obsCreate("__PAGE_EVENT"),g=document,f=g.addEventListener?function(k,j,i){if(d(k,"*[data-pagerole=body]")){k.addEventListener(j,i,false)}else{var h="icat_event"+Math.floor(Math.random()*1000000+1);k.setAttribute(j+"-eventId",h);b[h]=i;k.addEventListener(j,b[h],false)}}:g.attachEvent?function(k,j,i){if(d(k,"*[data-pagerole=body]")){k.attachEvent("on"+j,i)}else{var h="icatevent"+Math.floor(Math.random()*1000000+1);k.setAttribute("eventId",h);b[h]=i;k.attachEvent("on"+j,b[h])}}:function(j,i,h){j["on"+i]=h},e=g.removeEventListener?function(k,j,i){var h=k.getAttribute(j+"-eventId");if(!h){return}k.removeEventListener(j,b[h],false);delete b[h]}:g.detachEvent?function(k,j,i){var h=k.getAttribute(j+"-eventId");if(!h){return}k.detachEvent("on"+j,b[h]);delete b[h]}:function(j,i,h){j["on"+i]=null};a.mix(b,{preventDefault:function(h){if(h&&h.preventDefault){h.preventDefault()}else{window.event.returnValue=false}},stopPropagation:function(h){if(window.event){window.event.cancelBubble=true}else{h.stopPropagation()}},bindEvent:function(j,i,h){if(!j){return}!j.length?f(j,i,h):a.foreach(j,function(l,k){f(k,i,h)})},removeEvent:function(j,i,h){if(!j){return}!j.length?e(j,i,h):a.foreach(j,function(l,k){e(k,i,h)})},triggerEvent:function(k,l,j,i){if(g.createEventObject){var h=g.createEventObject();k.fireEvent("on"+l,h)}else{var m=g.createEvent("Event");m.initEvent(l,j,i);k.dispatchEvent(m)}},ready:function(){var i=[];var h=function(){if(!arguments.callee.done){arguments.callee.done=true;for(var j=0;j<i.length;j++){i[j]()}}};if(g.addEventListener){g.addEventListener("DOMContentLoaded",h,false)}if(a.browser.msie){(function(){try{g.documentElement.doScroll("left")}catch(j){setTimeout(arguments.callee,50);return}g.onreadystatechange=function(){if(g.readyState==="complete"){g.onreadystatechange=null;h()}}})()}if(a.browser.webkit&&g.readyState){(function(){if(g.readyState!=="loading"){h()}else{setTimeout(arguments.callee,10)}})()}window.onload=h;return function(j){if(a.isFunction(j)){i[i.length]=j}return j}}()});(function(){var n={},j,o="ontouchstart" in window;var p=o?"touchstart":"mousedown",h=o?"touchmove":"mousemove",r=o?"touchend":"mouseup",k="touchcancel";function q(t,s,v,u){var x=Math.abs(t-s),w=Math.abs(v-u);return x>=w?(t-s>0?"Left":"Right"):(v-u>0?"Up":"Down")}var m=750,i;function l(){if(i){clearTimeout(i)}i=null}b.ready(function(){if(!a.__OBSERVER_PAGEID||a.obsCreate[a.__OBSERVER_PAGEID]==null){a.__OBSERVER_PAGEID="__PAGE_EVENT"}var t=g.querySelector("*[data-pagerole=body]"),s,u;if(!t){return}b.bindEvent(t,p,function(v){var w=a.obsCreate[a.__OBSERVER_PAGEID],x=o?v.touches[0]:v;s=Date.now();u=s-(n.last||s);n.el=c(v.target);j&&clearTimeout(j);n.x1=x.pageX;n.y1=x.pageY;n.isScrolling=undefined;if(u>0&&u<=250){n.isDoubleTap=true}n.last=s;i=setTimeout(function(){i=null;if(n.last){w.execute("longTap",n.el);n={}}},m);b.stopPropagation(v)});b.bindEvent(t,h,function(v){l();var w=a.obsCreate[a.__OBSERVER_PAGEID],z=o?v.touches[0]:v;n.x2=z.pageX;n.y2=z.pageY;var y=n.x2-n.x1,x=n.y2-n.y1;if(typeof n.isScrolling=="undefined"){n.isScrolling=!!(n.isScrolling||Math.abs(y)<Math.abs(x))}if(!n.isScrolling){b.preventDefault(v);w.execute("swiping",n.el,[n.x1,n.x2,n.y1,n.y2]);b.stopPropagation(v)}});b.bindEvent(t,r,function(v){l();var x=a.obsCreate[a.__OBSERVER_PAGEID];if(!n.isScrolling){if(n.isDoubleTap){x.execute("doubleTap",n.el);n={}}else{if("last" in n){x.execute("tap",n.el);j=setTimeout(function(){j=null;x.execute("singleTap",n.el);n={}},250)}else{if((n.x2&&Math.abs(n.x1-n.x2)>30)||(n.y2&&Math.abs(n.y1-n.y2)>30)){var w="swipe"+q(n.x1,n.x2,n.y1,n.y2);x.execute(w,n.el);x.execute(w,n.el);n={}}}}}else{n={}}b.stopPropagation(v)});b.bindEvent(t,k,function(v){b.preventDefault(v);b.stopPropagation(v);if(j){clearTimeout(j)}if(i){clearTimeout(i)}i=j=null;n={}});b.bindEvent(t,"click",function(v){var w=a.obsCreate[a.__OBSERVER_PAGEID],z=c(v.target),y=w.selectors;if(!z||z==g.body){return}for(var x=0;x<y.length;x++){(function(){if(d(z,y[x])){b.preventDefault(v);b.stopPropagation(v)}else{if(z.parentNode!==g.body){z=z.parentNode;arguments.callee()}}})()}})})})()})(ICAT);
