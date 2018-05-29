(function($){
	$.extend($.fn, {
		slidePic: function(options){
			var defOptions = {
				slideWrap: '.slideWrap',
				slidePanel: '.pic',
				slideItem: '.pic a',
				handlePanel: '.handle',
				handleItem: '.handle>span',
				circle: false,
				auto: false,
				speed: 500,
				circleTime: 3000,
				prev: '.prev',
				next: '.next',
				curHandleCla: 'on',
				isBounce: false
			},
			opt = $.extend(defOptions, options);
			
			var me = $(this), timer,
				slideWrap = me.find(opt.slideWrap),
				slidePanel = me.find(opt.slidePanel),
				slideItem = me.find(opt.slideItem), steps = slideItem.length, iWidth = slideItem.width(),
				siWidth = opt.itemWidth || iWidth, threshold = Math.round(siWidth/4), noRule = siWidth!=iWidth,
				handlePanel = $(opt.handlePanel),
				handleItem = $(opt.handleItem),
				prev = $(opt.prev),
				next = $(opt.next);
			if(!me[0]) return;
			
			if(steps<=1){
				handlePanel.hide();
				return;
			} else {
				if(opt.fixCurrent){
					me.data('curIndex', opt.fixCurrent());
				} else {me.data('curIndex', 0);}
				
				slidePanel.width(iWidth*steps);
				if(noRule) steps = Math.ceil(iWidth*steps/siWidth);
				
				var style = slidePanel[0].style,
					idx = parseInt(me.data('curIndex'));
				style.MozTransform = style.webkitTransform = 'translate3d(' + (-idx*siWidth) + 'px,0,0)';
				style.msTransform = style.OTransform = 'translateX(' + (-idx*siWidth) + 'px)';
				handleItem.removeClass(opt.curHandleCla).eq(idx).addClass(opt.curHandleCla);
			}
			
			prev.on('click', function(){
				var curIndex = me.data('curIndex'),
					index = parseInt(curIndex)-1;
				slide(index, 'right');
			});
			next.on('click', function(){
				var curIndex = me.data('curIndex'),
					index = parseInt(curIndex)+1;
				slide(index, 'left');
			});
			
			if(opt.auto||opt.circle){autoplay();}
			
			slidePanel.swipe({
				swipeStatus: function(evt, phase, direction, distance, duration, fingerCount){
					if(phase=='start' || phase=='move'){
						clearTimeout(timer);
						if(direction=='up'||direction=='down') return;
						tmove(direction, distance);
					}else {
						if(direction=='up'||direction=='down') return;
						slide(parseInt(me.data('curIndex')), direction, distance);
						if(opt.auto) autoplay();
					}
				},
				threshold: threshold,
				maxTimeThreshold: siWidth,
				triggerOnTouchEnd: true,
				allowPageScroll: 'vertical'
			});
			
			function autoplay(){
				timer = setTimeout(function(){
					curIndex = parseInt(me.data('curIndex')) + 1;
					slide(curIndex, 'left');
					autoplay();
				}, opt.circleTime);
			}
			
			function slide(index, dir, distance, duration){
				
				var style = slidePanel[0].style;
				if(!duration){
					duration = opt.speed;
				}
				if(distance){
					index = distance<threshold? index : (dir=='left'? index+1 : index-1);
				}
				style.MozTransitionDuration = style.msTransitionDuration = style.OTransitionDuration = style.webkitTransitionDuration = style.transitionDuration = duration + 'ms';
				
				if(index>=steps){
					var idx = steps-1;
					if(opt.circle && dir=='left'){
						style.MozTransitionDuration = style.msTransitionDuration = style.OTransitionDuration = style.webkitTransitionDuration = style.transitionDuration = '1ms';
						style.MozTransform = style.webkitTransform = 'translate3d(0px,0,0)';
						style.msTransform = style.OTransform = 'translateX(0px)';
						
						me.data('curIndex', 0);
						handleItem.removeClass(opt.curHandleCla).eq(0).addClass(opt.curHandleCla);
					}
					else if(opt.isBounce && dir=='left'){
						style.MozTransform = style.webkitTransform = 'translate3d(' + (-(idx*siWidth+siWidth/2)) + 'px,0,0)';
						style.msTransform = style.OTransform = 'translateX(' + (-(idx*siWidth+siWidth/2)) + 'px)';
						setTimeout(function(){
							style.MozTransform = style.webkitTransform = 'translate3d(' + (-idx*siWidth) + 'px,0,0)';
							style.msTransform = style.OTransform = 'translateX(' + (-idx*siWidth) + 'px)';
							
							me.data('curIndex', idx);
							handleItem.removeClass(opt.curHandleCla).eq(idx).addClass(opt.curHandleCla);
						}, opt.speed);
					}
					else{
						if(noRule){
							var w = slidePanel.width()-siWidth;
							style.MozTransform = style.webkitTransform = 'translate3d(' + (-w) + 'px,0,0)';
							style.msTransform = style.OTransform = 'translateX(' + (-w) + 'px)';
						} else {
							style.MozTransform = style.webkitTransform = 'translate3d(' + (-idx*siWidth) + 'px,0,0)';
							style.msTransform = style.OTransform = 'translateX(' + (-idx*siWidth) + 'px)';
						}
						
						me.data('curIndex', idx);
						handleItem.removeClass(opt.curHandleCla).eq(idx).addClass(opt.curHandleCla);
					}
				} else if(index<0){
					if(opt.isBounce && dir=='right'){
						style.MozTransform = style.webkitTransform = 'translate3d(' + siWidth/2 + 'px,0,0)';
						style.msTransform = style.OTransform = 'translateX(' + siWidth/2 + 'px)';
						setTimeout(function(){
							style.MozTransform = style.webkitTransform = 'translate3d(0px,0,0)';
							style.msTransform = style.OTransform = 'translateX(0px)';
							
							me.data('curIndex', 0);
							handleItem.removeClass(opt.curHandleCla).eq(0).addClass(opt.curHandleCla);
						}, opt.speed);
					} else {
						style.MozTransform = style.webkitTransform = 'translate3d(0px,0,0)';
						style.msTransform = style.OTransform = 'translateX(0px)';
						
						me.data('curIndex', 0);
						handleItem.removeClass(opt.curHandleCla).eq(0).addClass(opt.curHandleCla);
					}
				} else {
					if(noRule && index==steps-1){
						var w = slidePanel.width()-siWidth;
						style.MozTransform = style.webkitTransform = 'translate3d(' + (-w) + 'px,0,0)';
						style.msTransform = style.OTransform = 'translateX(' + (-w) + 'px)';
						
						me.data('curIndex', index);
						handleItem.removeClass(opt.curHandleCla).eq(index).addClass(opt.curHandleCla);
						
						return false;
					}
					style.MozTransform = style.webkitTransform = 'translate3d(' + (-index*siWidth) + 'px,0,0)';
					style.msTransform = style.OTransform = 'translateX(' + (-index*siWidth) + 'px)';
					
					me.data('curIndex', index);
					handleItem.removeClass(opt.curHandleCla).eq(index).addClass(opt.curHandleCla);
				}
			}
			
			function tmove(dir, distance, duration){
				var style = slidePanel[0].style,
					index = me.data('curIndex') || 0;
				if(!duration){
					duration = opt.speed;
				}
				if(!opt.isBounce&&index==0&&dir=='right' || !opt.isBounce&&index==steps-1&&dir=='left') return;
				style.MozTransitionDuration = style.msTransitionDuration = style.OTransitionDuration = style.webkitTransitionDuration = style.transitionDuration = '';
				var dis = dir=='left'? distance : -distance;
				style.MozTransform = style.webkitTransform = 'translate3d(' + (-(index*siWidth+dis)) + 'px,0,0)';
				style.msTransform = style.OTransform = 'translateX(' + (-(index*siWidth+dis)) + 'px)';
			}
			
			/*(function(){
				var style = slidePanel[0].style,
					index = me.data('curIndex');
				setInterval(function(){
					style.MozTransform = style.webkitTransform = 'translate3d(' + (-index*siWidth) + 'px,0,0)';
					style.msTransform = style.OTransform = 'translateX(' + (-index*siWidth) + 'px)';
				}, 2*opt.speed);
			})()*/
		}
	});
})(Zepto);