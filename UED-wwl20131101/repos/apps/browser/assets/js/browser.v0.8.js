(function(a,b){a.app("MH",function(){return{version:"0.8"}});b.fn.tabs=function(d){var c=b(this),d=b(d);c.delegate("li","click",function(){var e=b(this).attr("data-tab");console.log(e);c.trigger("change:tabs",e)});c.on("change:tabs",function(g,f){c.find("li").removeClass("actived");c.find("[data-tab='"+f+"']").addClass("actived")});c.on("change:tabs",function(g,f){d.find("[data-tab]").addClass("ishide");d.find("[data-tab='"+f+"']").removeClass("ishide")});return this};b.fn.dropdown=function(){};a.mix(MH,{init:function(){this.tabClick();this.dropdownClick()},tabClick:function(){if(!b("#tabs01","#tabs02")){return}b("#tabs01").tabs("#tabs-cont01");b("#tabs02").tabs("#tabs-cont02")},dropdownClick:function(){if(!b(".dropdown")[0]){return}b(".dropdown .block .title").on("click",function(d){var f=b(this);var e=b(".dropdown .block .title");var c=f.next(".cont");if(c.hasClass("ishide")){c.removeClass("ishide");e.removeClass("arrow-up");f.addClass("arrow-up");b(".cont").not(f.next(".cont")).addClass("ishide")}else{c.addClass("ishide");f.removeClass("arrow-up")}})}});b(function(){MH.init();var c=b("body").attr("dt-cr");if(c){b("a").click(function(d){d.preventDefault();var e=this.getAttribute("dt-labelCla")?"&tid="+this.getAttribute("dt-labelCla"):"";location.href=c+"?url="+encodeURIComponent(this.href)+e})}})})(ICAT,Zepto);
