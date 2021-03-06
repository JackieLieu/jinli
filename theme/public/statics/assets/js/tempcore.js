/*!
 * artTemplate - Template Engine
 * https://github.com/aui/artTemplate
 * Released under the MIT, BSD, and GPL Licenses
 */
(function(d){var c=function(h,g){return c[typeof g==="string"?"compile":"render"].apply(c,arguments);
};c.version="2.0.2";c.openTag="<%";c.closeTag="%>";c.isEscape=true;c.isCompress=false;c.parser=null;c.render=function(i,h){var g=c.get(i)||e({id:i,name:"Render Error",message:"No Template"});
return g(h);};c.compile=function(n,k){var m=arguments;var h=m[2];var j="anonymous";if(typeof k!=="string"){h=m[1];k=m[0];n=j;}try{var g=b(n,k,h);}catch(l){l.id=n||k;
l.name="Syntax Error";return e(l);}function i(o){try{return new g(o,n)+"";}catch(p){if(!h){return c.compile(n,k,true)(o);}return e(p)();}}i.prototype=g.prototype;
i.toString=function(){return g.toString();};if(n!==j){f[n]=i;}return i;};var f=c.cache={};var a=c.helpers={$include:c.render,$string:function(h,g){if(typeof h!=="string"){g=typeof h;
if(g==="number"){h+="";}else{if(g==="function"){h=a.$string(h());}else{h="";}}}return h;},$escape:function(h){var g={"<":"&#60;",">":"&#62;",'"':"&#34;","'":"&#39;","&":"&#38;"};
return a.$string(h).replace(/&(?![\w#]+;)|[<>"']/g,function(i){return g[i];});},$each:function(k,l){var h=Array.isArray||function(i){return({}).toString.call(i)==="[object Array]";
};if(h(k)){for(var j=0,g=k.length;j<g;j++){l.call(k,k[j],j,k);}}else{for(j in k){l.call(k,k[j],j);}}}};c.helper=function(g,h){a[g]=h;};c.onerror=function(i){var h="Template Error\n\n";
for(var g in i){h+="<"+g+">\n"+i[g]+"\n\n";}if(d.console){console.error(h);}};c.get=function(j){var g;if(f.hasOwnProperty(j)){g=f[j];}else{if("document" in d){var h=document.getElementById(j);
if(h){var i=h.value||h.innerHTML;g=c.compile(j,i.replace(/^\s*|\s*$/g,""));}}}return g;};var e=function(g){c.onerror(g);return function(){return"{Template Error}";
};};var b=(function(){var n=a.$each;var h="break,case,catch,continue,debugger,default,delete,do,else,false,finally,for,function,if,in,instanceof,new,null,return,switch,this,throw,true,try,typeof,var,void,while,with,abstract,boolean,byte,char,class,const,double,enum,export,extends,final,float,goto,implements,import,int,interface,long,native,package,private,protected,public,short,static,super,synchronized,throws,transient,volatile,arguments,let,yield,undefined";
var i=/\/\*[\w\W]*?\*\/|\/\/[^\n]*\n|\/\/[^\n]*$|"(?:[^"\\]|\\[\w\W])*"|'(?:[^'\\]|\\[\w\W])*'|[\s\t\n]*\.[\s\t\n]*[$\w\.]+/g;var m=/[^\w$]+/g;var k=new RegExp(["\\b"+h.replace(/,/g,"\\b|\\b")+"\\b"].join("|"),"g");
var j=/^\d[^,]*|,\d[^,]*/g;var g=/^,+|,+$/g;var l=function(o){return o.replace(i,"").replace(m,",").replace(k,"").replace(j,"").replace(g,"").split(/^$|,+/);
};return function(D,E,G){var B=c.openTag;var w=c.closeTag;var s=c.parser;var p=E;var r="";var y=1;var C={$data:1,$id:1,$helpers:1,$out:1,$line:1};var A={};
var v="var $helpers=this,"+(G?"$line=0,":"");var J="".trim;var L=J?["$out='';","$out+=",";","$out"]:["$out=[];","$out.push(",");","$out.join('')"];var u=J?"if(content!==undefined){$out+=content;return content;}":"$out.push(content);";
var t="function(content){"+u+"}";var o="function(id,data){data=data||$data;var content=$helpers.$include(id,data,$id);"+u+"}";n(p.split(B),function(P,O){P=P.split(w);
var N=P[0];var M=P[1];if(P.length===1){r+=x(N);}else{r+=H(N);if(M){r+=x(M);}}});p=r;if(G){p="try{"+p+"}catch(e){throw {id:$id,name:'Render Error',message:e.message,line:$line,source:"+F(E)+".split(/\\n/)[$line-1].replace(/^[\\s\\t]+/,'')};}";
}p=v+L[0]+p+"return new String("+L[3]+");";try{var K=new Function("$data","$id",p);K.prototype=A;return K;}catch(I){I.temp="function anonymous($data,$id) {"+p+"}";
throw I;}function x(M){y+=M.split(/\n/).length-1;if(c.isCompress){M=M.replace(/[\n\r\t\s]+/g," ").replace(/<!--.*?-->/g,"");}if(M){M=L[1]+F(M)+L[2]+"\n";
}return M;}function H(O){var P=y;if(s){O=s(O);}else{if(G){O=O.replace(/\n/g,function(){y++;return"$line="+y+";";});}}if(O.indexOf("=")===0){var N=O.indexOf("==")!==0;
O=O.replace(/^=*|[\s;]*$/g,"");if(N&&c.isEscape){var M=O.replace(/\s*\([^\)]+\)/,"");if(!a.hasOwnProperty(M)&&!/^(include|print)$/.test(M)){O="$escape("+O+")";
}}else{O="$string("+O+")";}O=L[1]+O+L[2];}if(G){O="$line="+P+";"+O;}z(O);return O+"\n";}function z(M){M=l(M);n(M,function(N){if(!C.hasOwnProperty(N)){q(N);
C[N]=true;}});}function q(M){var N;if(M==="print"){N=t;}else{if(M==="include"){A["$include"]=a["$include"];N=o;}else{N="$data."+M;if(a.hasOwnProperty(M)){A[M]=a[M];
if(M.indexOf("$")===0){N="$helpers."+M;}else{N=N+"===undefined?$helpers."+M+":"+N;}}}}v+=M+"="+N+",";}function F(M){return"'"+M.replace(/('|\\)/g,"\\$1").replace(/\r/g,"\\r").replace(/\n/g,"\\n")+"'";
}};})();if(typeof define==="function"){define(function(){return c;});}else{if(typeof exports!=="undefined"){module.exports=c;}}d.template=c;})(this);