!function(a){a.fn.bootstrapSwitch=function(c){var b={init:function(){return this.each(function(){var p=a(this),m,f,l,o,h="",g=p.attr("class"),i,d,j="ON",e="OFF",n=false;a.each(["switch-mini","switch-small","switch-large"],function(q,r){if(g.indexOf(r)>=0){h=r;}});p.addClass("has-switch");if(p.data("on")!==undefined){i="switch-"+p.data("on");}if(p.data("on-label")!==undefined){j=p.data("on-label");}if(p.data("off-label")!==undefined){e=p.data("off-label");}if(p.data("icon")!==undefined){n=p.data("icon");}f=a("<span>").addClass("switch-left").addClass(h).addClass(i).html(j);i="";if(p.data("off")!==undefined){i="switch-"+p.data("off");}l=a("<span>").addClass("switch-right").addClass(h).addClass(i).html(e);o=a("<label>").html("&nbsp;").addClass(h).attr("for",p.find("input").attr("id"));if(n){o.html('<i class="icon icon-'+n+'"></i>');}m=p.find(":checkbox").wrap(a("<div>")).parent().data("animated",false);if(p.data("animated")!==false){m.addClass("switch-animate").data("animated",true);}m.append(f).append(o).append(l);p.find(">div").addClass(p.find("input").is(":checked")?"switch-on":"switch-off");if(p.find("input").is(":disabled")){a(this).addClass("deactivate");}var k=function(q){q.siblings("label").trigger("mousedown").trigger("mouseup").trigger("click");};p.on("keydown",function(q){if(q.keyCode===32){q.stopImmediatePropagation();q.preventDefault();k(a(q.target).find("span:first"));}});f.on("click",function(q){k(a(this));});l.on("click",function(q){k(a(this));});p.find("input").on("change",function(u){var t=a(this),r=t.parent(),q=t.is(":checked"),s=r.is(".switch-off");u.preventDefault();r.css("left","");if(s===q){if(q){r.removeClass("switch-off").addClass("switch-on");}else{r.removeClass("switch-on").addClass("switch-off");}if(r.data("animated")!==false){r.addClass("switch-animate");}r.parent().trigger("switch-change",{el:t,value:q});}});p.find("label").on("mousedown touchstart",function(r){var q=a(this);d=false;r.preventDefault();r.stopImmediatePropagation();q.closest("div").removeClass("switch-animate");if(q.closest(".has-switch").is(".deactivate")){q.unbind("click");}else{q.on("mousemove touchmove",function(x){var s=a(this).closest(".switch"),u=(x.pageX||x.originalEvent.targetTouches[0].pageX)-s.offset().left,v=(u/s.width())*100,w=25,t=75;d=true;if(v<w){v=w;}else{if(v>t){v=t;}}s.find(">div").css("left",(v-t)+"%");});q.on("click touchend",function(u){var t=a(this),s=a(u.target),v=s.siblings("input");u.stopImmediatePropagation();u.preventDefault();t.unbind("mouseleave");if(d){v.prop("checked",!(parseInt(t.parent().css("left"))<-25));}else{v.prop("checked",!v.is(":checked"));}d=false;v.trigger("change");});q.on("mouseleave",function(t){var s=a(this),u=s.siblings("input");t.preventDefault();t.stopImmediatePropagation();s.unbind("mouseleave");s.trigger("mouseup");u.prop("checked",!(parseInt(s.parent().css("left"))<-25)).trigger("change");});q.on("mouseup",function(s){s.stopImmediatePropagation();s.preventDefault();a(this).unbind("mousemove");});}});});},toggleActivation:function(){a(this).toggleClass("deactivate");},isActive:function(){return !a(this).hasClass("deactivate");},setActive:function(d){if(d){a(this).removeClass("deactivate");}else{a(this).addClass("deactivate");}},toggleState:function(d){var e=a(this).find("input:checkbox");e.prop("checked",!e.is(":checked")).trigger("change",d);},setState:function(e,d){a(this).find("input:checkbox").prop("checked",e).trigger("change",d);},status:function(){return a(this).find("input:checkbox").is(":checked");},destroy:function(){var d=a(this).find("div"),e;d.find(":not(input:checkbox)").remove();e=d.children();e.unwrap().unwrap();e.unbind("change");return e;}};if(b[c]){return b[c].apply(this,Array.prototype.slice.call(arguments,1));}else{if(typeof c==="object"||!c){return b.init.apply(this,arguments);}else{a.error("Method "+c+" does not exist!");}}};}(jQuery);$(function(){$(".switch")["bootstrapSwitch"]();});