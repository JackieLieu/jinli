(function(){var p=this;var n=p.Backbone;var g=Array.prototype;var o=g.slice;var y=g.splice;var d;if(typeof exports!=="undefined"){d=exports}else{d=p.Backbone={}}d.VERSION="0.9.2";var A=p._;if(!A&&(typeof require!=="undefined")){A=require("underscore")}d.$=p.jQuery||p.Zepto||p.ender;d.noConflict=function(){p.Backbone=n;return this};d.emulateHTTP=false;d.emulateJSON=false;var a=/\s+/;var m=d.Events={on:function(E,H,D){var C,F,G;if(!H){return this}E=E.split(a);C=this._callbacks||(this._callbacks={});while(F=E.shift()){G=C[F]||(C[F]=[]);G.push(H,D)}return this},off:function(F,I,E){var G,D,H,C;if(!(D=this._callbacks)){return this}if(!(F||I||E)){delete this._callbacks;return this}F=F?F.split(a):A.keys(D);while(G=F.shift()){if(!(H=D[G])||!(I||E)){delete D[G];continue}for(C=H.length-2;C>=0;C-=2){if(!(I&&H[C]!==I||E&&H[C+1]!==E)){H.splice(C,2)}}}return this},trigger:function(J){var C,K,G,F,E,H,I,D;if(!(K=this._callbacks)){return this}D=[];J=J.split(a);for(F=1,E=arguments.length;F<E;F++){D[F-1]=arguments[F]}while(C=J.shift()){if(I=K.all){I=I.slice()}if(G=K[C]){G=G.slice()}if(G){for(F=0,E=G.length;F<E;F+=2){G[F].apply(G[F+1]||this,D)}}if(I){H=[C].concat(D);for(F=0,E=I.length;F<E;F+=2){I[F].apply(I[F+1]||this,H)}}}return this}};m.bind=m.on;m.unbind=m.off;var k=d.Model=function(C,D){var E;C||(C={});if(D&&D.collection){this.collection=D.collection}if(D&&D.parse){C=this.parse(C)}if(E=A.result(this,"defaults")){C=A.extend({},E,C)}this.attributes={};this._escapedAttributes={};this.cid=A.uniqueId("c");this.changed={};this._silent={};this._pending={};this.set(C,{silent:true});this.changed={};this._silent={};this._pending={};this._previousAttributes=A.clone(this.attributes);this.initialize.apply(this,arguments)};A.extend(k.prototype,m,{changed:null,_silent:null,_pending:null,idAttribute:"id",initialize:function(){},toJSON:function(C){return A.clone(this.attributes)},sync:function(){return d.sync.apply(this,arguments)},get:function(C){return this.attributes[C]},escape:function(C){var D;if(D=this._escapedAttributes[C]){return D}var E=this.get(C);return this._escapedAttributes[C]=A.escape(E==null?"":""+E)},has:function(C){return this.get(C)!=null},set:function(J,I,L){var K,G,E;if(A.isObject(J)||J==null){K=J;L=I}else{K={};K[J]=I}L||(L={});if(!K){return this}if(K instanceof k){K=K.attributes}if(L.unset){for(G in K){K[G]=void 0}}if(!this._validate(K,L)){return false}if(this.idAttribute in K){this.id=K[this.idAttribute]}var H=L.changes={};var D=this.attributes;var C=this._escapedAttributes;var F=this._previousAttributes||{};for(G in K){E=K[G];if(!A.isEqual(D[G],E)||(L.unset&&A.has(D,G))){delete C[G];(L.silent?this._silent:H)[G]=true}L.unset?delete D[G]:D[G]=E;if(!A.isEqual(F[G],E)||(A.has(D,G)!==A.has(F,G))){this.changed[G]=E;if(!L.silent){this._pending[G]=true}}else{delete this.changed[G];delete this._pending[G]}}if(!L.silent){this.change(L)}return this},unset:function(C,D){D=A.extend({},D,{unset:true});return this.set(C,null,D)},clear:function(C){C=A.extend({},C,{unset:true});return this.set(A.clone(this.attributes),C)},fetch:function(D){D=D?A.clone(D):{};var C=this;var E=D.success;D.success=function(H,F,G){if(!C.set(C.parse(H,G),D)){return false}if(E){E(C,H,D)}};return this.sync("read",this,D)},save:function(H,G,L){var I,F,C;if(A.isObject(H)||H==null){I=H;L=G}else{I={};I[H]=G}L=L?A.clone(L):{};if(L.wait){if(!this._validate(I,L)){return false}F=A.clone(this.attributes)}var D=A.extend({},L,{silent:true});if(I&&!this.set(I,L.wait?D:L)){return false}if(!I&&!this.isValid()){return false}var E=this;var J=L.success;L.success=function(P,M,O){C=true;var N=E.parse(P,O);if(L.wait){N=A.extend(I||{},N)}if(!E.set(N,L)){return false}if(J){J(E,P,L)}};var K=this.sync(this.isNew()?"create":"update",this,L);if(!C&&L.wait){this.clear(D);this.set(F,D)}return K},destroy:function(D){D=D?A.clone(D):{};var C=this;var G=D.success;var E=function(){C.trigger("destroy",C,C.collection,D)};D.success=function(H){if(D.wait||C.isNew()){E()}if(G){G(C,H,D)}};if(this.isNew()){D.success();return false}var F=this.sync("delete",this,D);if(!D.wait){E()}return F},url:function(){var C=A.result(this,"urlRoot")||A.result(this.collection,"url")||s();if(this.isNew()){return C}return C+(C.charAt(C.length-1)==="/"?"":"/")+encodeURIComponent(this.id)},parse:function(D,C){return D},clone:function(){return new this.constructor(this.attributes)},isNew:function(){return this.id==null},change:function(D){D||(D={});var F=this._changing;this._changing=true;for(var C in this._silent){this._pending[C]=true}var E=A.extend({},D.changes,this._silent);this._silent={};for(var C in E){this.trigger("change:"+C,this,this.get(C),D)}if(F){return this}while(!A.isEmpty(this._pending)){this._pending={};this.trigger("change",this,D);for(var C in this.changed){if(this._pending[C]||this._silent[C]){continue}delete this.changed[C]}this._previousAttributes=A.clone(this.attributes)}this._changing=false;return this},hasChanged:function(C){if(C==null){return !A.isEmpty(this.changed)}return A.has(this.changed,C)},changedAttributes:function(E){if(!E){return this.hasChanged()?A.clone(this.changed):false}var G,F=false,D=this._previousAttributes;for(var C in E){if(A.isEqual(D[C],(G=E[C]))){continue}(F||(F={}))[C]=G}return F},previous:function(C){if(C==null||!this._previousAttributes){return null}return this._previousAttributes[C]},previousAttributes:function(){return A.clone(this._previousAttributes)},isValid:function(C){return !this.validate||!this.validate(this.attributes,C)},_validate:function(E,D){if(D.silent||!this.validate){return true}E=A.extend({},this.attributes,E);var C=this.validate(E,D);if(!C){return true}if(D&&D.error){D.error(this,C,D)}this.trigger("error",this,C,D);return false}});var B=d.Collection=function(D,C){C||(C={});if(C.model){this.model=C.model}if(C.comparator!==void 0){this.comparator=C.comparator}this._reset();this.initialize.apply(this,arguments);if(D){if(C.parse){D=this.parse(D)}this.reset(D,{silent:true,parse:C.parse})}};A.extend(B.prototype,m,{model:k,initialize:function(){},toJSON:function(C){return this.map(function(D){return D.toJSON(C)})},sync:function(){return d.sync.apply(this,arguments)},add:function(D,M){var I,K,F,J,L,E,G={},C={},H=[];M||(M={});D=A.isArray(D)?D.slice():[D];for(I=0,F=D.length;I<F;I++){if(!(J=D[I]=this._prepareModel(D[I],M))){throw new Error("Can't add an invalid model to a collection")}L=J.cid;E=J.id;if(G[L]||this._byCid[L]||((E!=null)&&(C[E]||this._byId[E]))){H.push(I);continue}G[L]=C[E]=J}I=H.length;while(I--){H[I]=D.splice(H[I],1)[0]}for(I=0,F=D.length;I<F;I++){(J=D[I]).on("all",this._onModelEvent,this);this._byCid[J.cid]=J;if(J.id!=null){this._byId[J.id]=J}}this.length+=F;K=M.at!=null?M.at:this.models.length;y.apply(this.models,[K,0].concat(D));if(M.merge){for(I=0,F=H.length;I<F;I++){if(J=this._byId[H[I].id]){J.set(H[I],M)}}}if(this.comparator&&M.at==null){this.sort({silent:true})}if(M.silent){return this}for(I=0,F=this.models.length;I<F;I++){if(!G[(J=this.models[I]).cid]){continue}M.index=I;J.trigger("add",J,this,M)}return this},remove:function(H,F){var G,C,E,D;F||(F={});H=A.isArray(H)?H.slice():[H];for(G=0,C=H.length;G<C;G++){D=this.getByCid(H[G])||this.get(H[G]);if(!D){continue}delete this._byId[D.id];delete this._byCid[D.cid];E=this.indexOf(D);this.models.splice(E,1);this.length--;if(!F.silent){F.index=E;D.trigger("remove",D,this,F)}this._removeReference(D)}return this},push:function(D,C){D=this._prepareModel(D,C);this.add(D,C);return D},pop:function(D){var C=this.at(this.length-1);this.remove(C,D);return C},unshift:function(D,C){D=this._prepareModel(D,C);this.add(D,A.extend({at:0},C));return D},shift:function(D){var C=this.at(0);this.remove(C,D);return C},slice:function(D,C){return this.models.slice(D,C)},get:function(C){if(C==null){return void 0}return this._byId[C.id!=null?C.id:C]},getByCid:function(C){return C&&this._byCid[C.cid||C]},at:function(C){return this.models[C]},where:function(C){if(A.isEmpty(C)){return[]}return this.filter(function(D){for(var E in C){if(C[E]!==D.get(E)){return false}}return true})},sort:function(C){if(!this.comparator){throw new Error("Cannot sort a set without a comparator")}if(A.isString(this.comparator)||this.comparator.length===1){this.models=this.sortBy(this.comparator,this)}else{this.models.sort(A.bind(this.comparator,this))}if(!C||!C.silent){this.trigger("reset",this,C)}return this},pluck:function(C){return A.invoke(this.models,"get",C)},reset:function(F,D){for(var E=0,C=this.models.length;E<C;E++){this._removeReference(this.models[E])}this._reset();if(F){this.add(F,A.extend({silent:true},D))}if(!D||!D.silent){this.trigger("reset",this,D)}return this},fetch:function(C){C=C?A.clone(C):{};if(C.parse===void 0){C.parse=true}var E=this;var D=C.success;C.success=function(H,F,G){E[C.add?"add":"reset"](E.parse(H,G),C);if(D){D(E,H,C)}};return this.sync("read",this,C)},create:function(D,C){var F=this;C=C?A.clone(C):{};D=this._prepareModel(D,C);if(!D){return false}if(!C.wait){F.add(D,C)}var E=C.success;C.success=function(H,I,G){if(G.wait){F.add(H,G)}if(E){E(H,I,G)}};D.save(null,C);return D},parse:function(D,C){return D},clone:function(){return new this.constructor(this.models)},chain:function(){return A(this.models).chain()},_reset:function(C){this.length=0;this.models=[];this._byId={};this._byCid={}},_prepareModel:function(E,D){if(E instanceof k){if(!E.collection){E.collection=this}return E}D||(D={});D.collection=this;var C=new this.model(E,D);if(!C._validate(C.attributes,D)){return false}return C},_removeReference:function(C){if(this===C.collection){delete C.collection}C.off("all",this._onModelEvent,this)},_onModelEvent:function(E,D,F,C){if((E==="add"||E==="remove")&&F!==this){return}if(E==="destroy"){this.remove(D,C)}if(D&&E==="change:"+D.idAttribute){delete this._byId[D.previous(D.idAttribute)];if(D.id!=null){this._byId[D.id]=D}}this.trigger.apply(this,arguments)}});var w=["forEach","each","map","collect","reduce","foldl","inject","reduceRight","foldr","find","detect","filter","select","reject","every","all","some","any","include","contains","invoke","max","min","sortedIndex","toArray","size","first","head","take","initial","rest","tail","last","without","indexOf","shuffle","lastIndexOf","isEmpty"];A.each(w,function(C){B.prototype[C]=function(){var D=o.call(arguments);D.unshift(this.models);return A[C].apply(A,D)}});var h=["groupBy","countBy","sortBy"];A.each(h,function(C){B.prototype[C]=function(F,D){var E=A.isFunction(F)?F:function(G){return G.get(F)};return A[C](this.models,E,D)}});var z=d.Router=function(C){C||(C={});if(C.routes){this.routes=C.routes}this._bindRoutes();this.initialize.apply(this,arguments)};var i=/:\w+/g;var x=/\*\w+/g;var e=/[-[\]{}()+?.,\\^$|#\s]/g;A.extend(z.prototype,m,{initialize:function(){},route:function(C,D,E){if(!A.isRegExp(C)){C=this._routeToRegExp(C)}if(!E){E=this[D]}d.history.route(C,A.bind(function(G){var F=this._extractParameters(C,G);E&&E.apply(this,F);this.trigger.apply(this,["route:"+D].concat(F));d.history.trigger("route",this,D,F)},this));return this},navigate:function(D,C){d.history.navigate(D,C)},_bindRoutes:function(){if(!this.routes){return}var D=[];for(var E in this.routes){D.unshift([E,this.routes[E]])}for(var F=0,C=D.length;F<C;F++){this.route(D[F][0],D[F][1],this[D[F][1]])}},_routeToRegExp:function(C){C=C.replace(e,"\\$&").replace(i,"([^/]+)").replace(x,"(.*?)");return new RegExp("^"+C+"$")},_extractParameters:function(C,D){return C.exec(D).slice(1)}});var b=d.History=function(){this.handlers=[];A.bindAll(this,"checkUrl");if(typeof window!=="undefined"){this.location=window.location;this.history=window.history}};var l=/^[#\/]/;var v=/^\/+|\/+$/g;var j=/msie [\w.]+/;var c=/\/$/;b.started=false;A.extend(b.prototype,m,{interval:50,getHash:function(D){var C=(D||this).location.href.match(/#(.*)$/);return C?C[1]:""},getFragment:function(E,D){if(E==null){if(this._hasPushState||!this._wantsHashChange||D){E=this.location.pathname;var C=this.root.replace(c,"");if(!E.indexOf(C)){E=E.substr(C.length)}}else{E=this.getHash()}}return decodeURIComponent(E.replace(l,""))},start:function(E){if(b.started){throw new Error("Backbone.history has already been started")}b.started=true;this.options=A.extend({},{root:"/"},this.options,E);this.root=this.options.root;this._wantsHashChange=this.options.hashChange!==false;this._wantsPushState=!!this.options.pushState;this._hasPushState=!!(this.options.pushState&&this.history&&this.history.pushState);var D=this.getFragment();var C=document.documentMode;var G=(j.exec(navigator.userAgent.toLowerCase())&&(!C||C<=7));this.root=("/"+this.root+"/").replace(v,"/");if(G&&this._wantsHashChange){this.iframe=d.$('<iframe src="javascript:0" tabindex="-1" />').hide().appendTo("body")[0].contentWindow;this.navigate(D)}if(this._hasPushState){d.$(window).bind("popstate",this.checkUrl)}else{if(this._wantsHashChange&&("onhashchange" in window)&&!G){d.$(window).bind("hashchange",this.checkUrl)}else{if(this._wantsHashChange){this._checkUrlInterval=setInterval(this.checkUrl,this.interval)}}}this.fragment=D;var H=this.location;var F=(H.pathname.replace(/[^/]$/,"$&/")===this.root)&&!H.search;if(this._wantsHashChange&&this._wantsPushState&&!this._hasPushState&&!F){this.fragment=this.getFragment(null,true);this.location.replace(this.root+this.location.search+"#"+this.fragment);return true}else{if(this._wantsPushState&&this._hasPushState&&F&&H.hash){this.fragment=this.getHash().replace(l,"");this.history.replaceState({},document.title,this.root+this.fragment)}}if(!this.options.silent){return this.loadUrl()}},stop:function(){d.$(window).unbind("popstate",this.checkUrl).unbind("hashchange",this.checkUrl);clearInterval(this._checkUrlInterval);b.started=false},route:function(C,D){this.handlers.unshift({route:C,callback:D})},checkUrl:function(D){var C=this.getFragment();if(C===this.fragment&&this.iframe){C=this.getFragment(this.getHash(this.iframe))}if(C===this.fragment){return false}if(this.iframe){this.navigate(C)}this.loadUrl()||this.loadUrl(this.getHash())},loadUrl:function(E){var D=this.fragment=this.getFragment(E);var C=A.any(this.handlers,function(F){if(F.route.test(D)){F.callback(D);return true}});return C},navigate:function(E,D){if(!b.started){return false}if(!D||D===true){D={trigger:D}}E=this.getFragment(E||"");if(this.fragment===E){return}this.fragment=E;var C=(E.indexOf(this.root)!==0?this.root:"")+E;if(this._hasPushState){this.history[D.replace?"replaceState":"pushState"]({},document.title,C)}else{if(this._wantsHashChange){this._updateHash(this.location,E,D.replace);if(this.iframe&&(E!==this.getFragment(this.getHash(this.iframe)))){if(!D.replace){this.iframe.document.open().close()}this._updateHash(this.iframe.location,E,D.replace)}}else{return this.location.assign(C)}}if(D.trigger){this.loadUrl(E)}},_updateHash:function(C,E,F){if(F){var D=C.href.replace(/(javascript:|#).*$/,"");C.replace(D+"#"+E)}else{C.hash="#"+E}}});d.history=new b;var r=d.View=function(C){this.cid=A.uniqueId("view");this._configure(C||{});this._ensureElement();this.initialize.apply(this,arguments);this.delegateEvents()};var f=/^(\S+)\s*(.*)$/;var t=["model","collection","el","id","attributes","className","tagName"];A.extend(r.prototype,m,{tagName:"div",$:function(C){return this.$el.find(C)},initialize:function(){},render:function(){return this},dispose:function(){this.undelegateEvents();if(this.model){this.model.off(null,null,this)}if(this.collection){this.collection.off(null,null,this)}return this},remove:function(){this.dispose();this.$el.remove();return this},make:function(D,C,F){var E=document.createElement(D);if(C){d.$(E).attr(C)}if(F!=null){d.$(E).html(F)}return E},setElement:function(C,D){if(this.$el){this.undelegateEvents()}this.$el=C instanceof d.$?C:d.$(C);this.el=this.$el[0];if(D!==false){this.delegateEvents()}return this},delegateEvents:function(G){if(!(G||(G=A.result(this,"events")))){return}this.undelegateEvents();for(var F in G){var H=G[F];if(!A.isFunction(H)){H=this[G[F]]}if(!H){throw new Error('Method "'+G[F]+'" does not exist')}var E=F.match(f);var D=E[1],C=E[2];H=A.bind(H,this);D+=".delegateEvents"+this.cid;if(C===""){this.$el.bind(D,H)}else{this.$el.delegate(C,D,H)}}},undelegateEvents:function(){this.$el.unbind(".delegateEvents"+this.cid)},_configure:function(E){if(this.options){E=A.extend({},this.options,E)}for(var F=0,D=t.length;F<D;F++){var C=t[F];if(E[C]){this[C]=E[C]}}this.options=E},_ensureElement:function(){if(!this.el){var C=A.extend({},A.result(this,"attributes"));if(this.id){C.id=A.result(this,"id")}if(this.className){C["class"]=A.result(this,"className")}this.setElement(this.make(A.result(this,"tagName"),C),false)}else{this.setElement(this.el,false)}}});var q={create:"POST",update:"PUT","delete":"DELETE",read:"GET"};d.sync=function(I,E,D){var F=q[I];D||(D={});var H={type:F,dataType:"json"};if(!D.url){H.url=A.result(E,"url")||s()}if(!D.data&&E&&(I==="create"||I==="update")){H.contentType="application/json";H.data=JSON.stringify(E)}if(d.emulateJSON){H.contentType="application/x-www-form-urlencoded";H.data=H.data?{model:H.data}:{}}if(d.emulateHTTP){if(F==="PUT"||F==="DELETE"){if(d.emulateJSON){H.data._method=F}H.type="POST";H.beforeSend=function(J){J.setRequestHeader("X-HTTP-Method-Override",F)}}}if(H.type!=="GET"&&!d.emulateJSON){H.processData=false}var G=D.success;D.success=function(L,J,K){if(G){G(L,J,K)}E.trigger("sync",E,L,D)};var C=D.error;D.error=function(L,J,K){if(C){C(E,L,D)}E.trigger("error",E,L,D)};return d.ajax(A.extend(H,D))};d.ajax=function(){return d.$.ajax.apply(d.$,arguments)};var u=function(C,E){var D=this;var G;if(C&&A.has(C,"constructor")){G=C.constructor}else{G=function(){D.apply(this,arguments)}}function F(){this.constructor=G}F.prototype=D.prototype;G.prototype=new F;if(C){A.extend(G.prototype,C)}A.extend(G,D,E);G.__super__=D.prototype;return G};k.extend=B.extend=z.extend=r.extend=u;var s=function(){throw new Error('A "url" property or function must be specified')}}).call(this);(function(){var c=this._;var d=this.Backbone;function b(){return(((1+Math.random())*65536)|0).toString(16).substring(1)}function a(){return(b()+b()+"-"+b()+"-"+b()+"-"+b()+"-"+b()+b()+b())}d.LocalStorage=window.Store=function(f){this.name=f;var e=this.localStorage().getItem(this.name);this.records=(e&&e.split(","))||[]};c.extend(d.LocalStorage.prototype,{save:function(){this.localStorage().setItem(this.name,this.records.join(","))},create:function(e){if(!e.id){e.id=a();e.set(e.idAttribute,e.id)}this.localStorage().setItem(this.name+"-"+e.id,JSON.stringify(e));this.records.push(e.id.toString());this.save();return e.toJSON()},update:function(e){this.localStorage().setItem(this.name+"-"+e.id,JSON.stringify(e));if(!c.include(this.records,e.id.toString())){this.records.push(e.id.toString())}this.save();return e.toJSON()},find:function(e){return JSON.parse(this.localStorage().getItem(this.name+"-"+e.id))},findAll:function(){return c(this.records).chain().map(function(e){return JSON.parse(this.localStorage().getItem(this.name+"-"+e))},this).compact().value()},destroy:function(e){this.localStorage().removeItem(this.name+"-"+e.id);this.records=c.reject(this.records,function(f){return f==e.id.toString()});this.save();return e},localStorage:function(){return localStorage}});d.LocalStorage.sync=window.Store.sync=d.localSync=function(j,h,g,f){var e=h.localStorage||h.collection.localStorage;if(typeof g=="function"){g={success:g,error:f}}var i;switch(j){case"read":i=h.id!=undefined?e.find(h):e.findAll();break;case"create":i=e.create(h);break;case"update":i=e.update(h);break;case"delete":i=e.destroy(h);break}if(i){g.success(i)}else{g.error("Record not found")}};d.ajaxSync=d.sync;d.getSyncMethod=function(e){if(e.localStorage||(e.collection&&e.collection.localStorage)){return d.localSync}return d.ajaxSync};d.sync=function(h,g,f,e){return d.getSyncMethod(g).apply(this,[h,g,f,e])}})();
