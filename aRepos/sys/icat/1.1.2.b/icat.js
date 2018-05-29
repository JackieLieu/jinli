/*!
 * Copyright 2011~2012, ICAT JavaScript Library v1.1.2.b
 * MIT Licensed
 * @Author valleykiddy@gmail.com
 * @Time 2012-09-23 10:00:00
 */
(function(){var B=this;var I=B.ICAT=B._;var L={};var P=navigator.userAgent,S=/debug/i.test(B.location.href);var K=Array.prototype,g=Object.prototype,v=Function.prototype;var s=K.slice,F=K.unshift,c=g.toString,k=g.hasOwnProperty;var Y=K.forEach,u=K.map,M=K.reduce,b=K.reduceRight,a=K.filter,G=K.every,t=K.some,q=K.indexOf,o=K.lastIndexOf,x=Array.isArray,f=Object.keys,N=v.bind;var A=function(i){return new y(i)};if(typeof exports!=="undefined"){if(typeof module!=="undefined"&&module.exports){exports=module.exports=A}exports._=exports.ICAT=A}else{B._=B.ICAT=A}A.VERSION="1.1.2.b";A.mix=function(ae,ac,ag,ab){if(!ac||!ae){return ae}if(ab===undefined){ab=true}var aa,af,Z,ad=function(aj,ai,ah,i){if(i||!(aj in ai)){ai[aj]=ah[aj]}};if(ag&&(Z=ag.length)){for(aa=0;aa<Z;aa++){af=ag[aa];if(af in ac){ad(af,ae,ac,ab)}}}else{for(af in ac){ad(af,ae,ac,ab)}}return ae};A.browser={uc:/UC/i.test(P),safari:/webkit/i.test(P),opera:/opera/i.test(P),msie:/msie/i.test(P)&&!/opera/i.test(P),mozilla:/mozilla/i.test(P)&&!/(compatible|webkit)/i.test(P)};var e=document,m=e.getElementsByTagName("script"),r=e.getElementsByName("appRef")[0],n,l="";A.appRef=r?r.content:"";A.corecss=r?(r.getAttribute("corecss")||""):"";for(var W=0,X;X=m[W++];){n=!!e.querySelector?X.src:X.getAttribute("src",4);if(n&&/icat(\.source)?\.js/i.test(n)){var H=/icat\/\d+\.+/.test(n),V;if(H){V="/icat/"}else{V=r?(r.getAttribute("icatreg")||"/icat."):"/icat."}if(/\?(v|t)=\d+/.test(n)){l="?"+n.replace(/.*\?/,"")}A.refFile=X;A.sysRef=n.substr(0,n.lastIndexOf(V));A.libRef=A.sysRef+"/lib";break}}A.mix(A,{isIE:A.browser.msie,ieVersion:A.browser.msie?P.match(/MSIE(\s)?\d+/i)[0].replace(/MSIE(\s)?/i,""):-1,isDebug:S,__APP_MEMBERS:["namespace"],__APP_INIT_METHODS:["__init"],__init:function(){var p=this,i=/^\.{2}\//.test(A.refFile.getAttribute("main")||"");p.widget={};p.util={};p.sysPlugin=p.refFile.src.replace(/icat\..*/,"")+"plugin/";p.appPlugin=p.appRef+(i?"/assets":"")+"/plugin/";p.timestamp=l;p.loadStore={};p.modStore={};p.mods={}},isFunction:function(i){return c.call(i)=="[object Function]"},isString:isString=function(i){return c.call(i)=="[object String]"},isArray:x||function(i){return c.call(i)=="[object Array]"},foreach:function(ae,p,ab){var aa,ac=0,ad=ae.length,Z=ad===undefined||A.isFunction(ae);if(ab){if(Z){for(aa in ae){if(p.apply(ae[aa],ab)===false){break}}}else{for(;ac<ad;){if(p.apply(ae[ac++],ab)===false){break}}}}else{if(Z){for(aa in ae){if(p.call(ae[aa],aa,ae[aa])===false){break}}}else{for(;ac<ad;){if(p.call(ae[ac],ac,ae[ac++])===false){break}}}}},namespace:function(){var aa=arguments,Z=aa.length,ae=null,ac,ab,ad;for(ac=0;ac<Z;++ac){ad=(""+aa[ac]).split(".");ae=this;for(ab=(B[ad[0]]===ae)?1:0;ab<ad.length;++ab){ae=ae[ad[ab]]=ae[ad[ab]]||{}}}return ae},app:function(p,ab){var i=this,Z=i.isString(p),aa=Z?B[p]||{}:p;i.mix(aa,i,i.__APP_MEMBERS,true);i.mix(aa,i.isFunction(ab)?ab():ab);Z&&(B[p]=aa);return aa},log:function(i){if(!this.isDebug){return}if(this.isIE){alert(i)}else{if(B.console!==undefined&&console.log){console.log(i)}}}});var R=A.each=A.forEach=function(ad,ac,ab){if(ad==null){return}if(Y&&ad.forEach===Y){ad.forEach(ac,ab)}else{if(ad.length===+ad.length){for(var aa=0,p=ad.length;aa<p;aa++){if(aa in ad&&ac.call(ab,ad[aa],aa,ad)===L){return}}}else{for(var Z in ad){if(A.has(ad,Z)){if(ac.call(ab,ad[Z],Z,ad)===L){return}}}}}};A.map=A.collect=function(aa,Z,p){var i=[];if(aa==null){return i}if(u&&aa.map===u){return aa.map(Z,p)}R(aa,function(ad,ab,ac){i[i.length]=Z.call(p,ad,ab,ac)});if(aa.length===+aa.length){i.length=aa.length}return i};A.reduce=A.foldl=A.inject=function(ab,aa,i,Z){var p=arguments.length>2;if(ab==null){ab=[]}if(M&&ab.reduce===M){if(Z){aa=A.bind(aa,Z)}return p?ab.reduce(aa,i):ab.reduce(aa)}R(ab,function(ae,ac,ad){if(!p){i=ae;p=true}else{i=aa.call(Z,i,ae,ac,ad)}});if(!p){throw new TypeError("Reduce of empty array with no initial value")}return i};A.reduceRight=A.foldr=function(ab,aa,i,Z){var p=arguments.length>2;if(ab==null){ab=[]}if(b&&ab.reduceRight===b){if(Z){aa=A.bind(aa,Z)}return p?ab.reduceRight(aa,i):ab.reduceRight(aa)}var ac=A.toArray(ab).reverse();if(Z&&!p){aa=A.bind(aa,Z)}return p?A.reduce(ac,aa,i,Z):A.reduce(ac,aa)};A.find=A.detect=function(aa,Z,p){var i;E(aa,function(ad,ab,ac){if(Z.call(p,ad,ab,ac)){i=ad;return true}});return i};A.filter=A.select=function(aa,Z,p){var i=[];if(aa==null){return i}if(a&&aa.filter===a){return aa.filter(Z,p)}R(aa,function(ad,ab,ac){if(Z.call(p,ad,ab,ac)){i[i.length]=ad}});return i};A.reject=function(aa,Z,p){var i=[];if(aa==null){return i}R(aa,function(ad,ab,ac){if(!Z.call(p,ad,ab,ac)){i[i.length]=ad}});return i};A.every=A.all=function(aa,Z,p){var i=true;if(aa==null){return i}if(G&&aa.every===G){return aa.every(Z,p)}R(aa,function(ad,ab,ac){if(!(i=i&&Z.call(p,ad,ab,ac))){return L}});return !!i};var E=A.some=A.any=function(aa,Z,p){Z||(Z=A.identity);var i=false;if(aa==null){return i}if(t&&aa.some===t){return aa.some(Z,p)}R(aa,function(ad,ab,ac){if(i||(i=Z.call(p,ad,ab,ac))){return L}});return !!i};A.include=A.contains=function(Z,p){var i=false;if(Z==null){return i}if(q&&Z.indexOf===q){return Z.indexOf(p)!=-1}i=E(Z,function(aa){return aa===p});return i};A.invoke=function(p,Z){var i=s.call(arguments,2);return A.map(p,function(aa){return(A.isFunction(Z)?Z||aa:aa[Z]).apply(aa,i)})};A.pluck=function(p,i){return A.map(p,function(Z){return Z[i]})};A.max=function(aa,Z,p){if(!Z&&A.isArray(aa)&&aa[0]===+aa[0]){return Math.max.apply(Math,aa)}if(!Z&&A.isEmpty(aa)){return -Infinity}var i={computed:-Infinity};R(aa,function(ae,ab,ad){var ac=Z?Z.call(p,ae,ab,ad):ae;ac>=i.computed&&(i={value:ae,computed:ac})});return i.value};A.min=function(aa,Z,p){if(!Z&&A.isArray(aa)&&aa[0]===+aa[0]){return Math.min.apply(Math,aa)}if(!Z&&A.isEmpty(aa)){return Infinity}var i={computed:Infinity};R(aa,function(ae,ab,ad){var ac=Z?Z.call(p,ae,ab,ad):ae;ac<i.computed&&(i={value:ae,computed:ac})});return i.value};A.shuffle=function(Z){var i=[],p;R(Z,function(ac,aa,ab){p=Math.floor(Math.random()*(aa+1));i[aa]=i[p];i[p]=ac});return i};A.sortBy=function(Z,aa,i){var p=A.isFunction(aa)?aa:function(ab){return ab[aa]};return A.pluck(A.map(Z,function(ad,ab,ac){return{value:ad,criteria:p.call(i,ad,ab,ac)}}).sort(function(ae,ad){var ac=ae.criteria,ab=ad.criteria;if(ac===void 0){return 1}if(ab===void 0){return -1}return ac<ab?-1:ac>ab?1:0}),"value")};A.groupBy=function(Z,aa){var i={};var p=A.isFunction(aa)?aa:function(ab){return ab[aa]};R(Z,function(ad,ab){var ac=p(ad,ab);(i[ac]||(i[ac]=[])).push(ad)});return i};A.sortedIndex=function(ac,ab,Z){Z||(Z=A.identity);var i=0,aa=ac.length;while(i<aa){var p=(i+aa)>>1;Z(ac[p])<Z(ab)?i=p+1:aa=p}return i};A.toArray=function(i){if(!i){return[]}if(A.isArray(i)){return s.call(i)}if(A.isArguments(i)){return s.call(i)}if(i.toArray&&A.isFunction(i.toArray)){return i.toArray()}return A.values(i)};A.size=function(i){return A.isArray(i)?i.length:A.keys(i).length};A.first=A.head=A.take=function(Z,p,i){return(p!=null)&&!i?s.call(Z,0,p):Z[0]};A.initial=function(Z,p,i){return s.call(Z,0,Z.length-((p==null)||i?1:p))};A.last=function(Z,p,i){if((p!=null)&&!i){return s.call(Z,Math.max(Z.length-p,0))}else{return Z[Z.length-1]}};A.rest=A.tail=function(Z,i,p){return s.call(Z,(i==null)||p?1:i)};A.compact=function(i){return A.filter(i,function(p){return !!p})};A.flatten=function(p,i){return A.reduce(p,function(Z,aa){if(A.isArray(aa)){return Z.concat(i?aa:A.flatten(aa))}Z[Z.length]=aa;return Z},[])};A.without=function(i){return A.difference(i,s.call(arguments,1))};A.uniq=A.unique=function(ab,aa,Z){var i=Z?A.map(ab,Z):ab;var p=[];if(ab.length<3){aa=true}A.reduce(i,function(ac,ae,ad){if(aa?A.last(ac)!==ae||!ac.length:!A.include(ac,ae)){ac.push(ae);p.push(ab[ad])}return ac},[]);return p};A.union=function(){return A.uniq(A.flatten(arguments,true))};A.intersection=A.intersect=function(p){var i=s.call(arguments,1);return A.filter(A.uniq(p),function(Z){return A.every(i,function(aa){return A.indexOf(aa,Z)>=0})})};A.difference=function(p){var i=A.flatten(s.call(arguments,1),true);return A.filter(p,function(Z){return !A.include(i,Z)})};A.zip=function(){var p=s.call(arguments);var ab=A.max(A.pluck(p,"length"));var aa=new Array(ab);for(var Z=0;Z<ab;Z++){aa[Z]=A.pluck(p,""+Z)}return aa};A.indexOf=function(ac,aa,ab){if(ac==null){return -1}var Z,p;if(ab){Z=A.sortedIndex(ac,aa);return ac[Z]===aa?Z:-1}if(q&&ac.indexOf===q){return ac.indexOf(aa)}for(Z=0,p=ac.length;Z<p;Z++){if(Z in ac&&ac[Z]===aa){return Z}}return -1};A.lastIndexOf=function(aa,Z){if(aa==null){return -1}if(o&&aa.lastIndexOf===o){return aa.lastIndexOf(Z)}var p=aa.length;while(p--){if(p in aa&&aa[p]===Z){return p}}return -1};A.range=function(ac,aa,ab){if(arguments.length<=1){aa=ac||0;ac=0}ab=arguments[2]||1;var p=Math.max(Math.ceil((aa-ac)/ab),0);var i=0;var Z=new Array(p);while(i<p){Z[i++]=ac;ac+=ab}return Z};var O=function(){};A.bind=function d(aa,p){var Z,i;if(aa.bind===N&&N){return N.apply(aa,s.call(arguments,1))}if(!A.isFunction(aa)){throw new TypeError}i=s.call(arguments,2);return Z=function(){if(!(this instanceof Z)){return aa.apply(p,i.concat(s.call(arguments)))}O.prototype=aa.prototype;var ac=new O;var ab=aa.apply(ac,i.concat(s.call(arguments)));if(Object(ab)===ab){return ab}return ac}};A.bindAll=function(p){var i=s.call(arguments,1);if(i.length==0){i=A.functions(p)}R(i,function(Z){p[Z]=A.bind(p[Z],p)});return p};A.memoize=function(Z,p){var i={};p||(p=A.identity);return function(){var aa=p.apply(this,arguments);return A.has(i,aa)?i[aa]:(i[aa]=Z.apply(this,arguments))}};A.delay=function(p,Z){var i=s.call(arguments,2);return setTimeout(function(){return p.apply(null,i)},Z)};A.defer=function(i){return A.delay.apply(A,[i,1].concat(s.call(arguments,1)))};A.throttle=function(Z,aa){var p,ac,ad,ae,ab,af;var i=A.debounce(function(){ab=ae=false},aa);return function(){p=this;ac=arguments;var ag=function(){ad=null;if(ab){Z.apply(p,ac)}i()};if(!ad){ad=setTimeout(ag,aa)}if(ae){ab=true}else{af=Z.apply(p,ac)}i();ae=true;return af}};A.debounce=function(p,aa,i){var Z;return function(){var ad=this,ac=arguments;var ab=function(){Z=null;if(!i){p.apply(ad,ac)}};if(i&&!Z){p.apply(ad,ac)}clearTimeout(Z);Z=setTimeout(ab,aa)}};A.once=function(Z){var i=false,p;return function(){if(i){return p}i=true;return p=Z.apply(this,arguments)}};A.wrap=function(i,p){return function(){var Z=[i].concat(s.call(arguments,0));return p.apply(this,Z)}};A.compose=function(){var i=arguments;return function(){var p=arguments;for(var Z=i.length-1;Z>=0;Z--){p=[i[Z].apply(this,p)]}return p[0]}};A.after=function(p,i){if(p<=0){return i()}return function(){if(--p<1){return i.apply(this,arguments)}}};A.keys=f||function(Z){if(Z!==Object(Z)){throw new TypeError("Invalid object")}var p=[];for(var i in Z){if(A.has(Z,i)){p[p.length]=i}}return p};A.values=function(i){return A.map(i,A.identity)};A.functions=A.methods=function(Z){var p=[];for(var i in Z){if(A.isFunction(Z[i])){p.push(i)}}return p.sort()};A.extend=function(i){R(s.call(arguments,1),function(p){for(var Z in p){i[Z]=p[Z]}});return i};A.pick=function(p){var i={};R(A.flatten(s.call(arguments,1)),function(Z){if(Z in p){i[Z]=p[Z]}});return i};A.defaults=function(i){R(s.call(arguments,1),function(p){for(var Z in p){if(i[Z]==null){i[Z]=p[Z]}}});return i};A.clone=function(i){if(!A.isObject(i)){return i}return A.isArray(i)?i.slice():A.extend({},i)};A.tap=function(p,i){i(p);return p};function T(aa,Z,p){if(aa===Z){return aa!==0||1/aa==1/Z}if(aa==null||Z==null){return aa===Z}if(aa._chain){aa=aa._wrapped}if(Z._chain){Z=Z._wrapped}if(aa.isEqual&&A.isFunction(aa.isEqual)){return aa.isEqual(Z)}if(Z.isEqual&&A.isFunction(Z.isEqual)){return Z.isEqual(aa)}var ad=c.call(aa);if(ad!=c.call(Z)){return false}switch(ad){case"[object String]":return aa==String(Z);case"[object Number]":return aa!=+aa?Z!=+Z:(aa==0?1/aa==1/Z:aa==+Z);case"[object Date]":case"[object Boolean]":return +aa==+Z;case"[object RegExp]":return aa.source==Z.source&&aa.global==Z.global&&aa.multiline==Z.multiline&&aa.ignoreCase==Z.ignoreCase}if(typeof aa!="object"||typeof Z!="object"){return false}var ae=p.length;while(ae--){if(p[ae]==aa){return true}}p.push(aa);var ac=0,i=true;if(ad=="[object Array]"){ac=aa.length;i=ac==Z.length;if(i){while(ac--){if(!(i=ac in aa==ac in Z&&T(aa[ac],Z[ac],p))){break}}}}else{if("constructor" in aa!="constructor" in Z||aa.constructor!=Z.constructor){return false}for(var ab in aa){if(A.has(aa,ab)){ac++;if(!(i=A.has(Z,ab)&&T(aa[ab],Z[ab],p))){break}}}if(i){for(ab in Z){if(A.has(Z,ab)&&!(ac--)){break}}i=!ac}}p.pop();return i}A.isEqual=function(p,i){return T(p,i,[])};A.isEmpty=function(p){if(p==null){return true}if(A.isArray(p)||A.isString(p)){return p.length===0}for(var i in p){if(A.has(p,i)){return false}}return true};A.isElement=function(i){return !!(i&&i.nodeType==1)};A.isObject=function(i){return i===Object(i)};A.isArguments=function(i){return c.call(i)=="[object Arguments]"};if(!A.isArguments(arguments)){A.isArguments=function(i){return !!(i&&A.has(i,"callee"))}}A.isNumber=function(i){return c.call(i)=="[object Number]"};A.isFinite=function(i){return A.isNumber(i)&&isFinite(i)};A.isNaN=function(i){return i!==i};A.isBoolean=function(i){return i===true||i===false||c.call(i)=="[object Boolean]"};A.isDate=function(i){return c.call(i)=="[object Date]"};A.isRegExp=function(i){return c.call(i)=="[object RegExp]"};A.isNull=function(i){return i===null};A.isUndefined=function(i){return i===void 0};A.has=function(p,i){return k.call(p,i)};A.noConflict=function(){B._=I;return this};A.identity=function(i){return i};A.times=function(ab,aa,Z){for(var p=0;p<ab;p++){aa.call(Z,p)}};A.escape=function(i){return(""+i).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#x27;").replace(/\//g,"&#x2F;")};A.result=function(i,Z){if(i==null){return null}var p=i[Z];return A.isFunction(p)?p.call(i):p};A.mixin=function(i){R(A.functions(i),function(p){C(p,A[p]=i[p])})};var D=0;A.uniqueId=function(i){var p=D++;return i?i+p:p};A.templateSettings={evaluate:/<%([\s\S]+?)%>/g,interpolate:/<%=([\s\S]+?)%>/g,escape:/<%-([\s\S]+?)%>/g};var z=/.^/;var h={"\\":"\\","'":"'",r:"\r",n:"\n",t:"\t",u2028:"\u2028",u2029:"\u2029"};for(var Q in h){h[h[Q]]=Q}var j=/\\|'|\r|\n|\t|\u2028|\u2029/g;var J=/\\(\\|'|r|n|t|u2028|u2029)/g;var U=function(i){return i.replace(J,function(p,Z){return h[Z]})};A.template=function(ac,ab,Z){Z=A.defaults(Z||{},A.templateSettings);var aa="__p+='"+ac.replace(j,function(ad){return"\\"+h[ad]}).replace(Z.escape||z,function(ad,ae){return"'+\n_.escape("+U(ae)+")+\n'"}).replace(Z.interpolate||z,function(ad,ae){return"'+\n("+U(ae)+")+\n'"}).replace(Z.evaluate||z,function(ad,ae){return"';\n"+U(ae)+"\n;__p+='"})+"';\n";if(!Z.variable){aa="with(obj||{}){\n"+aa+"}\n"}aa="var __p='';var print=function(){__p+=Array.prototype.join.call(arguments, '')};\n"+aa+"return __p;\n";var p=new Function(Z.variable||"obj","iCat",aa);if(ab){return p(ab,A)}var i=function(ad){return p.call(this,ad,A)};i.source="function("+(Z.variable||"obj")+"){\n"+aa+"}";return i};A.chain=function(i){return A(i).chain()};var y=function(i){this._wrapped=i};A.prototype=y.prototype;var w=function(p,i){return i?A(p).chain():p};var C=function(i,p){y.prototype[i]=function(){var Z=s.call(arguments);F.call(Z,this._wrapped);return w(p.apply(A,Z),this._chain)}};A.mixin(A);R(["pop","push","reverse","shift","sort","splice","unshift"],function(i){var p=K[i];y.prototype[i]=function(){var Z=this._wrapped;p.apply(Z,arguments);var aa=Z.length;if((i=="shift"||i=="splice")&&aa===0){delete Z[0]}return w(Z,this._chain)}});R(["concat","join","slice"],function(i){var p=K[i];y.prototype[i]=function(){return w(p.apply(this._wrapped,arguments),this._chain)}});y.prototype.chain=function(){this._chain=true;return this};y.prototype.value=function(){return this._wrapped};A.__init()}).call(this);if(!this.JSON){this.JSON={}}(function(){function f(n){return n<10?"0"+n:n}if(typeof Date.prototype.toJSON!=="function"){Date.prototype.toJSON=function(key){return isFinite(this.valueOf())?this.getUTCFullYear()+"-"+f(this.getUTCMonth()+1)+"-"+f(this.getUTCDate())+"T"+f(this.getUTCHours())+":"+f(this.getUTCMinutes())+":"+f(this.getUTCSeconds())+"Z":null};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(key){return this.valueOf()}}var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={"\b":"\\b","\t":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},rep;function quote(string){escapable.lastIndex=0;return escapable.test(string)?'"'+string.replace(escapable,function(a){var c=meta[a];return typeof c==="string"?c:"\\u"+("0000"+a.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+string+'"'}function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==="object"&&typeof value.toJSON==="function"){value=value.toJSON(key)}if(typeof rep==="function"){value=rep.call(holder,key,value)}switch(typeof value){case"string":return quote(value);case"number":return isFinite(value)?String(value):"null";case"boolean":case"null":return String(value);case"object":if(!value){return"null"}gap+=indent;partial=[];if(Object.prototype.toString.apply(value)==="[object Array]"){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||"null"}v=partial.length===0?"[]":gap?"[\n"+gap+partial.join(",\n"+gap)+"\n"+mind+"]":"["+partial.join(",")+"]";gap=mind;return v}if(rep&&typeof rep==="object"){length=rep.length;for(i=0;i<length;i+=1){k=rep[i];if(typeof k==="string"){v=str(k,value);if(v){partial.push(quote(k)+(gap?": ":":")+v)}}}}else{for(k in value){if(Object.hasOwnProperty.call(value,k)){v=str(k,value);if(v){partial.push(quote(k)+(gap?": ":":")+v)}}}}v=partial.length===0?"{}":gap?"{\n"+gap+partial.join(",\n"+gap)+"\n"+mind+"}":"{"+partial.join(",")+"}";gap=mind;return v}}if(typeof JSON.stringify!=="function"){JSON.stringify=function(value,replacer,space){var i;gap="";indent="";if(typeof space==="number"){for(i=0;i<space;i+=1){indent+=" "}}else{if(typeof space==="string"){indent=space}}rep=replacer;if(replacer&&typeof replacer!=="function"&&(typeof replacer!=="object"||typeof replacer.length!=="number")){throw new Error("JSON.stringify")}return str("",{"":value})}}if(typeof JSON.parse!=="function"){JSON.parse=function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==="object"){for(k in value){if(Object.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v}else{delete value[k]}}}}return reviver.call(holder,key,value)}cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return"\\u"+("0000"+a.charCodeAt(0).toString(16)).slice(-4)})}if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,""))){j=eval("("+text+")");return typeof reviver==="function"?walk({"":j},""):j}throw new SyntaxError("JSON.parse")}}}());(function(e){var i=document,a=i.head||i.getElementsByTagName("head")[0],k=e.loadStore,h=e.modStore,g=function(p){if(!p){return}var n=p.replace(/\s|[\?#].*/g,""),o=n.replace(/.*\./g,""),l=o=="css";if(!n){return}if(e.isDebug){n=/\.source/i.test(n)?n:(l?n.replace(/\.css/g,".source.css"):n.replace(/\.js/g,".source.js"))}if(/^(http|ftp|https):\/\/.*/i.test(n)){return n}else{if(/^\.(\.|~)?\//g.test(n)){if(/^\.\//.test(n)){n=n.replace(/^\./,e.appRef)}if(/^\.{2}\//.test(n)){n=n.replace(/^\.{2}/,e.appRef+(l?"/assets/css":"/assets/js"))}if(/^\.~\//.test(n)){n=n.replace(/^\.~\//,e.appPlugin)}}else{if(/^\/{1,2}/.test(n)){if(/^\/{2}/.test(n)){var m=n.replace(/^\/{2}|.source|.css|.js/ig,"");m=/\d|\./.test(m)?m.replace(/\d(\/)?|\./g,""):m;n=n.replace(/^\//,e.libRef+"/"+m)}else{n=n.replace(/^\//,e.libRef+"/")}}else{if(/^~\//.test(n)){n=n.replace(/^~\//,e.sysPlugin)}else{n=e.sysRef+"/"+n}}}}return n+e.timestamp},f=function(s){var o=s,p=o.replace(/[\?#].*/,"");if(k[p]){return}var q=p.replace(/.*\./g,""),n=q=="css",m=n?"link":"script",l=n?' type="text/css" rel="stylesheet"':' type="text/javascript"',r=(n?"href":"src")+'="'+o+'"';i.write("<"+m+l+r+(n?"/>":"></"+m+">"));k[p]=true},d=function(r,o){if(r.length==0){return}var s={};e.mix(s,o);var m=r[0],q=m.replace(/[\?#].*/,""),l=function(){if(r.length>0){d(r,o)}else{if(s.callback&&e.isFunction(s.callback)){s.callback(s.context||e)}if(s.mod){h[s.mod]=true}}};if(k[q]){l();return}r.shift();var p,t=q.replace(/.*\./g,"");if(t==="css"){p=i.createElement("link");p.setAttribute("type","text/css");p.setAttribute("rel","stylesheet");p.setAttribute("href",m)}else{if(t==="js"){p=i.createElement("script");p.setAttribute("type","text/javascript");p.setAttribute("src",m);p.setAttribute("async",true)}}if(!p){return}if(e.isIE){var n=setInterval(function(){try{document.documentElement.doScroll("left")}catch(u){return}clearInterval(n);if(t==="js"&&p.readyState){p.onreadystatechange=function(){if(p.readyState=="loaded"||p.readyState=="complete"){p.onreadystatechange=null;l();k[q]=true}}}a.appendChild(p)},1)}else{if(t==="js"){p.onload=function(){l();k[q]=true}}a.appendChild(p)}if(t==="css"){l();k[q]=true;a.appendChild(p)}};e.mix(e,{inc:function(m){if(!m){return}var l=e.isString(m)?[m]:m;e.foreach(l,function(o,n){if(!n){return}f(g(n))})},incfile:function(n,m){if(!n||!n.length){return}var l=[];if(e.isString(n)){l.push(g(n))}else{if(e.isArray(n)){e.foreach(n,function(p,o){l.push(g(o))})}else{return}}d(l,{callback:m})},require:function(o,p,n){if(!o&&!p){return}if(h[o]){if(n){n(e)}}else{var l=[];if(e.isString(p)){l.push(g(p))}else{if(e.isArray(p)){e.foreach(p,function(q,m){l.push(g(m))})}}d(l,{callback:n,mod:o})}},use:function(n,l,p){var o=0,p=p||500,q=setInterval(function(){o+=5;if(o>=p){clearInterval(q);e.require(n,e.mods[n],l)}else{if(h[n]){clearInterval(q);l(e)}}},5)}});var c=e.refFile.getAttribute("corelib")||"",b=e.refFile.getAttribute("main")||"",j=[];if(/,/.test(c)){c=c.split(",");j=e.union(j,c);j=e.union(j,[e.corecss,b])}else{j=e.union(j,[e.corecss,c,b])}e.inc(j)})(ICAT);