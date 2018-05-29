Scratcher=(function(){function b(d){var f,e={};var g=d.originalEvent;if(g.changedTouches!=undefined){f=g.changedTouches[0];e.pageX=f.pageX;e.pageY=f.pageY;
}else{e.pageX=d.pageX;e.pageY=d.pageY;}return e;}function c(d,e){var f=$(d).offset();return{x:e.pageX-f.left,y:e.pageY-f.top};}function a(f,d,e){this.canvas={main:$("#"+f).get(0),temp:null,draw:null};
this.mouseDown=false;this.canvasId=f;this._setupCanvases();this.setImages(d,e);this._eventListeners={};}a.prototype.setImages=function(d,e){this.image={back:{url:d,img:null},front:{url:e,img:null}};
if(d||e){this._loadImages();}};a.prototype.fullAmount=function(d){var g,f;var o=this.canvas.draw;var n=o.getContext("2d");var h,m;var e,j;if(!d||d<1){d=1;
}d*=4;e=n.getImageData(0,0,o.width,o.height);j=e.data;f=j.length;m=(f/d)|0;for(g=h=0;g<f;g+=d){if(j[g]!=0){h++;}}return h/m;};a.prototype.recompositeCanvases=function(){var g=this.canvas.temp.getContext("2d");
var f=this.canvas.main.getContext("2d");var d=this.canvas.main.width;var e=this.canvas.main.height;this.canvas.temp.width=this.canvas.temp.width;g.drawImage(this.canvas.draw,0,0,d,e);
g.globalCompositeOperation="source-atop";g.drawImage(this.image.back.img,0,0,d,e);f.drawImage(this.image.front.img,0,0,d,e);f.drawImage(this.canvas.temp,0,0,d,e);
};a.prototype.scratchLine=function(d,h,f){var g=this.canvas.draw;var e=g.getContext("2d");e.lineWidth=30;e.lineCap=e.lineJoin="round";e.strokeStyle="#fff";
if(f){e.beginPath();e.moveTo(d+0.01,h);}e.lineTo(d,h);e.stroke();this.dispatchEvent(this.createEvent("scratch"));};a.prototype._setupCanvases=function(){var g=this.canvas.main;
this.canvas.temp=document.createElement("canvas");this.canvas.draw=document.createElement("canvas");this.canvas.temp.width=this.canvas.draw.width=g.width;
this.canvas.temp.height=this.canvas.draw.height=g.height;function e(i){var h=c(g,b(i));this.mouseDown=true;this.scratchLine(h.x,h.y,true);this.recompositeCanvases();
this.dispatchEvent(this.createEvent("scratchesbegan"));return false;}function d(i){if(!this.mouseDown){return true;}var h=c(g,b(i));this.scratchLine(h.x,h.y,false);
this.recompositeCanvases();return false;}function f(h){if(this.mouseDown){this.mouseDown=false;this.dispatchEvent(this.createEvent("scratchesended"));return false;
}return true;}$(g).on("mousedown",e.bind(this)).on("touchstart",e.bind(this));$(document).on("mousemove",d.bind(this));$(document).on("touchmove",d.bind(this));
$(document).on("mouseup",f.bind(this));$(document).on("touchend",f.bind(this));};a.prototype.reset=function(){this.canvas.draw.width=this.canvas.draw.width;
this.recompositeCanvases();this.dispatchEvent(this.createEvent("reset"));};a.prototype.mainCanvas=function(){return this.canvas.main;};a.prototype._loadImages=function(){var e=0;
function d(f){e++;if(e>=2){this.dispatchEvent(this.createEvent("imagesloaded"));this.reset();}}for(k in this.image){if(this.image.hasOwnProperty(k)){this.image[k].img=document.createElement("img");
$(this.image[k].img).on("load",d.bind(this));this.image[k].img.src=this.image[k].url;}}};a.prototype.createEvent=function(d){var e={type:d,target:this,currentTarget:this};
return e;};a.prototype.addEventListener=function(f,e){var d=this._eventListeners;f=f.toLowerCase();if(!d.hasOwnProperty(f)){d[f]=[];}if(d[f].indexOf(e)==-1){d[f].push(e);
}};a.prototype.removeEventListener=function(g,f){var e=this._eventListeners;var d;g=g.toLowerCase();if(!e.hasOwnProperty(g)){return;}if(f){if((d=e[g].indexOf(f))!=-1){e[g].splice(d,1);
}}else{e[g]=[];}};a.prototype.dispatchEvent=function(h){var g=this._eventListeners;var e,d;var f=h.type.toLowerCase();if(!g.hasOwnProperty(f)){return;}d=g[f].length;
for(e=0;e<d;e++){g[f][e].call(this,h);}};if(!Function.prototype.bind){Function.prototype.bind=function(d){if(typeof this!=="function"){throw new TypeError("Function.prototype.bind - what is trying to be bound is not callable");
}var h=Array.prototype.slice.call(arguments,1),g=this,e=function(){},f=function(){return g.apply(this instanceof e?this:d||window,h.concat(Array.prototype.slice.call(arguments)));
};e.prototype=this.prototype;f.prototype=new e();return f;};}return a;})();