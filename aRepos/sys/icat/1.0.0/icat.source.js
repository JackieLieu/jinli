/*
 * @module My ui-framework
 * @author Wang.wenlong
 * @date 2011-10-08
 */
 
(function(iCat, win){
	
	// If ICAT is already defined, the existing ICAT object will not
    // be overwritten so that defined namespaces are preserved.
    if (win[iCat] === undefined) win[iCat] = {};
    iCat = win[iCat]; // shortcut
	
	var AP = Array.prototype,
        indexOf = AP.indexOf,
		toString = Object.prototype.toString,
		
		/**
		 * Copies all the properties of s to r.
		 * @return {Object} the augmented object
		 */
		mix = function(r, s, ov, wl) {
			if (!s || !r) return r;
			if (ov === undefined) ov = true;
			var i, p, len;

			if (wl && (len = wl.length)) {
				for (i = 0; i < len; i++) {
					p = wl[i];
					if (p in s) {
						_mix(p, r, s, ov);
					}
				}
			} else {
				for (p in s) {
					_mix(p, r, s, ov);
				}
			}
			return r;
		},
		_mix = function(p, r, s, ov) {
            if (ov || !(p in r)) {
                r[p] = s[p];
            }
        },
		
		// [[Class]] -> type pairs
        class2type = {};
		
	mix(iCat, {
		
		/**
         * The version of the library.
         * @type {string}
         */
        version: '1.0.0',
		
		// iCat.app() with these members.
        __APP_MEMBERS: ['namespace'],
        __APP_INIT_METHODS: ['__init'],

        /**
         * Initializes ICAT object.
         * @private
         */
        __init: function() {
            this.Env = {//thisָ��iCat
                mods: {}
            };

            this.Config = {
                debug: true
            };
        },
		
		isNull: function(o) {
            return o === null;
        },

        isUndefined: function(o) {
            return o === undefined;
        },
		
		/**
         * Determines whether or not the provided object is a boolean.
         */
        isBoolean: function(o) {
            return typeof o === 'boolean';
        },

        /**
         * Determines whether or not the provided object is a string.
         */
        isString: function(o) {
            return typeof o === 'string';
        },

        /**
         * Determines whether or not the provided item is a legal number.
         * NOTICE: Infinity and NaN return false.
         */
        isNumber: function(o) {
            return typeof o === 'number' && isFinite(o);
        },
		
		/**
         * Search for a specified value within an array.
         */
        indexOf: indexOf ?
            function(elem, arr) {
                return indexOf.call(arr, elem);
            } :
            function(elem, arr) {
                for (var i = 0, len = arr.length; i < len; ++i) {
                    if (arr[i] === elem) {
                        return i;
                    }
                }
                return -1;
            },
		
		/**
         * Copies all the properties of s to r.
         * @return {object} the augmented object
         */
        mix: mix,
		
		/**
         * Applies prototype properties from the supplier to the receiver.
         * @param r {Function} the object to receive the augmentation
         * @param s {Object|Function} the object that supplies the properties to augment
         * @param wl {String[]} a whitelist
         * @return {Object} the augmented object
         */
        augment: function(r, s, ov, wl) {
            mix(r.prototype, s.prototype || s, ov, wl);
            return r;
        },
		
        /**
         * Registers a module.
         * @param {string} name module name
         * @param {function} fn entry point into the module that is used to bind module to ICAT
         * <pre>
         * ICAT.add('module-name', function(iCat){ });
         * </pre>
         * @return {ICAT}
         */
        add: function(name, fn) {
            var self = this;

            // override mode
            self.Env.mods[name] = {
                name: name,
                fn: fn
            };

            // call entry point immediately
            fn(self);

            // chain support
            return self;
        },
		
		/**
         * Returns the namespace specified and creates it if it doesn't exist. Be careful
         * when naming packages. Reserved words may work in some browsers and not others.
         * <pre>
         * iCat.namespace('AC.app'); // returns AC.app
         * iCat.namespace('app.Shop'); // returns AC.app.Shop
         *
         * AC.namespace('AC.app'); // returns AC.app
         * AC.namespace('app.Shop'); // returns AC.app.Shop
         * </pre>
         * @return {object}  A reference to the last namespace object created
         */
        namespace: function() {
            var a = arguments, l = a.length, o = null, i, j, p;

            for (i = 0; i < l; ++i) {
                p = ('' + a[i]).split('.');
                o = this;
                for (j = (win[p[0]] === o) ? 1 : 0; j < p.length; ++j) {
                    o = o[p[j]] = o[p[j]] || {};
                }
            }
            return o;
        },
		
		/**
         * create app based on ICAT.
         * @param name {String} the app name
         * @param sx {Object} static properties to add/override
         * <code>
         * iCat.app('AC');
         * AC.namespace('app'); // returns AC.app
         * </code>
         * @return {Object}  A reference to the app global object
         */
        app: function(name, sx) {
            var self = this,
				isStr = iCat.isString(name),
                O = isStr ? win[name] || {} : name,
                i = 0,
                len = self.__APP_INIT_METHODS.length;

            mix(O, self, true, self.__APP_MEMBERS);
            for (; i < len; i++) self[self.__APP_INIT_METHODS[i]].call(O);
			
			mix(O, iCat.isFunction(sx) ? sx() : sx);
			isStr && (win[name] = O);

            return O;
        },
		
        /**
         * Prints debug info.
         * @param {string} msg The message to log.
         * @param {string} sign The log category for the message. Default
         * categories are "info", "warn", "error", time" etc.
         * @param {string} src The source of the the message (opt)
         * @return {ICAT}
         */
        log: function(msg, sign, src) {
            var c = this.Config;

            if (c.debug) {
                src && (msg = src + ': ' + msg);
                if (win.console !== undefined && console.log) {
                    console[sign && console[sign] ? sign : 'log'](msg);
                }
            }
        },
		
		/**
         * Throws error message.
         */
        error: function(msg) {
            if(this.Config.debug) {
                throw msg;
            }
        }
	});
		
	iCat.namespace('widget');
	iCat.__init();
	
	return iCat;
	
})('ICAT', this);//thisָ��window

/**
* @module  jQuery-methods
* @author  jn_dream@163.com
* @description Common methods from jQuery
*/
(function(iCat, $){
	
	iCat.mix(iCat, $);
	
	iCat.extend({
		
		/**
         * the jQuery's Object is or isn't exist.
         */
		detect: function(o){
			return o.length>0;
		},
		
		/**
		 * put jQuery's selector into the framework
		 */
		cQuery: function(){
			return $.apply(this,arguments);
		}
	});
	
	iCat.namespace('DOM','Event');
	iCat.DOM = {
		/**
		 * ����jQueryѡ���������ԭ����dom��������
		 * @param arr {String[]} arguments list
         * @return {dom[]} the dom Object
		 */
		query: function(){
			var arrNodes = [], nodes = $.apply(this,arguments);
			
			nodes.each(function(){
				arrNodes.push(this);
			});
			return arrNodes;
		},
		
		/**
		 * ����jQueryѡ��������õ�һ��ԭ����dom����
		 * @param arr {String[]} arguments list
         * @return {dom[]} the first dom Object
		 */
		get: function(){
			return $.apply(this,arguments).get(0);
		}
	};
	
	iCat.Event = {
		/**
		 * �������¼��Ĳ���
		 * @param arr {String[]} arguments list
         * @return {Object} the augmented object
		 */
		dealArgs: function(arr){
			var args = [], evtArgs = [];
			iCat.each(arr, function(i,v){
				i===0 ? evtArgs.push(v) : args.push(v);
			});
			evtArgs.push(args);
			
			return evtArgs;
		},
		
		/**
		 * ����jQuery��bind����
		 * @param event arguments list
         * @description like jQuery's bind
		 */
		on: function(){
			var args = this.dealArgs(arguments);
			$(args[0]).bind.apply($(args[0]), args[1]);
		},
		
		/**
		 * ����jQuery��live����
		 * @param event arguments list
         * @description like jQuery's live
		 */
		live: function(){
			var args = this.dealArgs(arguments);
			$(args[0]).live.apply($(args[0]), args[1]);
		}
	}
})(ICAT, jQuery);

/**
* @module  loader
* @author  jn_dream@163.com
* @description
*/
(function (iCat) {
	var win = this,
        doc = win['document'],
        //head = doc.getElementsByTagName('head')[0] || doc.documentElement,
		mix = iCat.mix, C = iCat.cQuery,
		loader;
	
	loader = {
		
		/**
         * all kinds of path.
         */
		/*hostPath: function(){
			//var url = document.location.pathname.substr(1);
			//url = '/' + url.substr(0, url.indexOf('/'));
			return win['location'].protocol+'//'+win['location'].host+'/plugin/';
		},*/
		sysPath: C('#J_filePath').attr('sys-hostPath'),
		sysPlugin: C('#J_filePath').attr('sys-hostPath')+'/sys/icat/'+iCat.version+'/plugin/',
		appPlugin: C('#J_filePath').attr('app-pluginPath'),
		
		/**
         * Load a JavaScript/css file from the server using add script tag.
         * <code>
         *  ICAT.include([url]);//ȱʡ·���ǹ��ò��·��
         * </code>
         */
		impFileRec: {},//��̬���ؼ�¼��
        include: function (file,path) {
            var files = typeof file == 'string' ? [file] : file/*,
				_addfile = function(isCSS,url){
					if(isCSS){
						var cssfile = document.createElement('link');
						cssfile.rel = 'stylesheet';
						cssfile.type = 'text/css';
						cssfile.href = url;
						head.appendChild(cssfile);
					} else {
						var jsfile = document.createElement('script');
						jsfile.type = 'text/javascript';
						jsfile.src = url;
						head.appendChild(jsfile);
					}
				}*/;
			
			/* ȡ�ò��Ŀ¼�����ַ�ʽ��
			 -	1.��������
			 -	2.domα�����趨
			 -	3.jsȡ��host��ַ/Ӧ��js����iCat.hostPathָ��
			
			hostPath = hostPath || (C('#J_hostPath').attr('data-pluginPath') ||
									(iCat.isFunction(this.hostPath) ? this.hostPath() : this.hostPath));*/
            path = path || this.sysPlugin;
			for (var i=0,ilen=files.length; i<ilen; i++){
                var name = files[i].replace(/^\s|\s$/g, ''),
					att = name.split('.'),
					ext = att[att.length - 1].toLowerCase(),
					isCSS = ext=='css',
					url = path + name;
                var tag = isCSS ? 'link' : 'script',
					attr = isCSS ? ' type="text/css" rel="stylesheet" ' : ' type="text/javascript" ',
					link = (isCSS ? 'href' : 'src') + '="' + url + '"';
                if(!this.impFileRec[url]){
					document.write('<' + tag + attr + link + '></' + tag + '>');
					this.impFileRec[url] = true;
				}
				/*if(!this.impFileRec[url]){
					_addfile(isCSS,url);
					this.impFileRec[url] = true;
				}*/
            }
        },
		
		use: function(mod, callback){
			//iCat.getScript(iCat.plugin[mod],callback);
		}
	};
	
	mix(iCat, loader);
	
})(ICAT);

/**
* @module  Util
* @author  jn_dream@163.com
* @description
*/
ICAT.add('util', function(iCat){
	var Dom = iCat.DOM, Event = iCat.Event, C = $ = iCat.cQuery;
	iCat.namespace('util');
	
	iCat.mix(iCat.util, {
		
		/**
         * �۽�ʱ��ʾ��Ϊ�գ�ʧ��ʱ��ʾ�����.
         * @param iptCla {String} Selector's style
         * @param strMsg {String} tips
         */
		focusBox: function(iptCla, strMsg){
			var $tInput = $(iptCla), msg = strMsg || $tInput.val();
			if(!$tInput.length) return;
			
			$tInput.focus(function(){
				if(this.value==msg) this.value = '';
			}).blur(function(){
				if(this.value=='') this.value = msg;
			});
		},
		
		/**
         * ��ʾ��������֣���ʣ������.
         * @param iptCla {String} Selector's style
         * @param tipCla {String} Selector's style
         */
		tipNum: function(iptCla,tipCla){
			var $tInput = $(iptCla),
				maxLen = $tInput.attr('maxlength'),
				$tip = $tInput.siblings(tipCla),
				eChange = iCat.browser.msie ? 'propertychange':'input focus blur',
				_tipNum = function(o){
					var tValue = o.val(),
						tLen = tValue.replace(/[^\x00-\xFF]/g,'**').length;
					$tip.html('���Ѿ�������:'+tLen+'���ַ�����ʣ'+(maxLen-tLen)+'��');
					
					if(maxLen-tLen<=0){
						o.val(tValue.substring(0,maxLen));
					}
				};
			if(!$tInput.length && !$tip.length) return;
			
			//��ʼ��
			_tipNum($tInput);
			$tInput.bind(eChange, function(){_tipNum($tInput);});
		},
		
		/**
         * ȫѡЧ��.
         * @param iptCla {String} Selector's style
         * @param tipCla {String} Selector's style
         */
		checkAll: function(objAll,oneCla,context){
			if(context){
				var arrCheck = oneCla;
			} else {
				var arrCheck = Dom.query('.'+oneCla)
			}
			var len1 = arrCheck.length, i;
			var arrControl = Dom.query('.'+objAll.className),
				len2 = arrControl.length, j;
			
			if(objAll.checked){
				for(i=0; i<len1; i++){
					arrCheck[i].checked = true;
				}
				for(j=0; j<len2; j++){
					arrControl[j].checked = true;
				}
			} else {
				for(i=0; i<len1; i++){
					arrCheck[i].checked = false;
				}
				for(j=0; j<len2; j++){
					arrControl[j].checked = false;
				}
			}
		},
		
		/**
         * δȫѡЧ��.
         * @param iptCla {String} Selector's style
         * @param tipCla {String} Selector's style
         */
		checkOne: function(objOne,allCla){
			var arrControl = Dom.query('.'+allCla),
				len = arrControl.length, i, isAll;
			
			if(!objOne.checked){
				for(i=0; i<len; i++){
					arrControl[i].checked = false;
				}
				return;
			}
			for(var j=0, arrOne=Dom.query('.'+objOne.className), l=arrOne.length; j<l; j++){
				if(!arrOne[j].checked){
					isAll = false;
					break;
				} else {
					isAll = true;
				}
			}
			if(isAll){
				for(i=0; i<len; i++){
					arrControl[i].checked = true;
				}
			}
		},
		
		/**
         * �ı���ʾЧ��.
         * @param aimCla {String} Selector's style
         * @param tipCla {String} Selector's style
         */
		tipShow: function(config){
			var dConfig = {aimCla:'.J_aimObject', tipId:'#J_tipBox', offsetX:0, offsetY:0},
				cfg = iCat.extend(dConfig,config),
				oAim = C(cfg.aimCla),
				aimPos = oAim.offset(),
				tId = cfg.tipId.replace(/(#)/g,'');
			if(!oAim.length) return;
			if(!C(cfg.tipId).length){
				C('body').append('<div id="'+tId+'" class="tip-box"></div>');
				var oTip = C('body').find(cfg.tipId);
			} else {
				var oTip = C('body').find(cfg.tipId);
			}
			
			oAim.hover(function(){
				oTip.css({
					'left':(aimPos.left+cfg.offsetX)+'px',
					'top':(aimPos.top+cfg.offsetY)+'px'
				}).html(oAim.attr('data-tipText')).show();
			}, function(){
				oTip.hide();
			});
		},
		
		/**
         * ����ǿ��.
         */
        pwdStrength: function () {
            var _CharMode = function (iN) {
                if (iN >= 48 && iN <= 57) //���� 
                    return 1;
                if (iN >= 65 && iN <= 90) //��д��ĸ 
                    return 2;
                if (iN >= 97 && iN <= 122) //Сд 
                    return 4;
                else
                    return 8; //�����ַ� 
            },

			_bitTotal = function (num) {
			    modes = 0;
			    for (i = 0; i < 4; i++) {
			        if (num & 1) modes++;
			        num >>>= 1;
			    }
			    return modes;
			},

			_checkStrong = function (sPW) {
			    if (sPW.length <= 4)
			        return 0; //����̫�� 
			    Modes = 0;
			    for (i = 0; i < sPW.length; i++) {
			        //����ÿһ���ַ������ͳ��һ���ж�����ģʽ. 
			        Modes |= _CharMode(sPW.charCodeAt(i));
			    }
			    return _bitTotal(Modes);
			},

			_pwStrength = function (o, pwd) {
			    var strCla = ['pwd0', 'pwd1', 'pwd2'],
					$pwdSafe = o.next('.J_pwdSafe');
			    if (pwd == null || pwd == '') {
			        var oldCla = $pwdSafe.attr('data-class');
			        $pwdSafe.removeClass(oldCla);
			    } else {
			        var S_level = _checkStrong(pwd);
			        switch (S_level) {
			            case 0:
			                var oldCla = $pwdSafe.attr('data-class');
			                $pwdSafe.removeClass(oldCla).addClass(strCla[0]);
			                $pwdSafe.attr('data-class', strCla[0]);
			                break;
			            case 1:
			                var oldCla = $pwdSafe.attr('data-class');
			                $pwdSafe.removeClass(oldCla).addClass(strCla[1]);
			                $pwdSafe.attr('data-class', strCla[1]);
			                break;
			            case 2:
			                var oldCla = $pwdSafe.attr('data-class');
			                $pwdSafe.removeClass(oldCla).addClass(strCla[2]);
			                $pwdSafe.attr('data-class', strCla[2]);
			                break;
			        }
			    }
			    return;
			};

            $('.J_pwdStrength').bind('blur keyup', function () {
                _pwStrength($(this), this.value);
            });
        }
	});
});

/**
 *
 * NOTES:
 *
 *2011.11.8
 * - �о�loader�����hostPath�е���ң�������һ�£�js�õ�·���޷�������·����sys����Ĺ��ò����app����Ĳ��
 * - ���sys���������hostPath����ȷ�����λ�ã����app�����������α����ȥָ����
 *
 * 2011.10.16
 * - ��Ȼ��jQuery���ܳ������ӣ��ͷ��ĵ�ȥ�ã���������ͨ�����ˡ��˿��ֻ��Ϊ����֯���룬�γɱ�׼��ܡ�����dom��event�ȣ���jQueryȥ���ɡ�
 * - ����jQueryת��DOMģ�顢Eventģ��
 *
 * 2011.10.11
 * - ����iCat��jQuery�ںϵĶ���ģ�飨���������У�����jQuery�ϵķ�����ֱ�Ӽ޽ӵ���iCat��
 * - ����DOMģ�飬�Ժ�ḻ
 * - ����Eventģ�飬�Ժ�ḻ
 * - ɾ����jQuery�ظ��ķ�����isFunciton��merge��extend��
 *
 * 2011.9.10
 * - add ���������ڲ�����Ļ�����֯��ʽ���� module �� submodule ����֯���룩��
 * - mix, merge, augment, extend ������������������Ļ���ʵ�ַ�ʽ��������� mixin ���Ժ� prototype ��ʽ��ʵ�ִ��롣
 * - namespace, app �����������ӿ��ʵ�ֺʹ����������֯��
 * - log, error �������򵥵ĵ��Թ��ߺͱ�����ơ�
 *
 * 2011.8.10
 * - ����js�⣬��ּ�ǰ�jquery���������ӣ��ܹ���������֯���ձ��⡣
 * - �˿���������ļ������棺
	1��ICATȫ�ֱ����Ķ��壻
	2���汾�ż���������mix��
	3��namespace��app������������
	4����̬����ģ�飻
	5������js�ķ�����
 */