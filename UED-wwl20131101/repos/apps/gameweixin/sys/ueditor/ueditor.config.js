(function(){var a=window.UEDITOR_HOME_URL||e();window.UEDITOR_CONFIG={UEDITOR_HOME_URL:a,toolbars:[["fontsize","|","blockquote","horizontal","|","removeformat"],["bold","italic","underline","forecolor","backcolor","|","justifyleft","justifycenter","justifyright","|","rowspacingtop","rowspacingbottom","lineheight","|","insertorderedlist","insertunorderedlist"]],zIndex:0,customDomain:false,enableAutoSave:false,imagePopup:false,enableContextMenu:false,elementPathEnabled:false,wordCount:false,maximumWords:1000000,autoHeightEnabled:false};
function e(f,g){return c(f||self.document.URL||self.location.href,g||b());}function b(){var f=document.getElementsByTagName("script");return f[f.length-1].src;
}function c(f,g){var h=g;if(/^(\/|\\\\)/.test(g)){h=/^.+?\w(\/|\\\\)/.exec(f)[0]+g.replace(/^(\/|\\\\)/,"");}else{if(!/^[a-z]+:/i.test(g)){f=f.split("#")[0].split("?")[0].replace(/[^\\\/]+$/,"");
h=f+""+g;}}return d(h);}function d(h){var i=/^[a-z]+:\/\//.exec(h)[0],g=null,f=[];h=h.replace(i,"").split("?")[0].split("#")[0];h=h.replace(/\\/g,"/").split(/\//);
h[h.length-1]="";while(h.length){if((g=h.shift())===".."){f.pop();}else{if(g!=="."){f.push(g);}}}return i+f.join("/");}window.UE={getUEBasePath:e};})();
