(function(a){a.extend(a.fn,{countDown:function(n){var m={days:".days",hours:".hours",minutes:".minutes",seconds:".seconds",currTimeAttr:"data-currentTime",endTimeAttr:"data-endTime",startTimeAttr:"data-starTime",dur:[],timerId:[],remainDur:[],remainTimeId:[],startHandle:function(){},endHandle:function(){}},c=a.extend(m,n);var f=a(this);var j=f.find(c.days),h=f.find(c.hours),d=f.find(c.minutes),i=f.find(c.seconds);var l={days:"00",hours:"00",minutes:"00",seconds:"00"},g={days:"00",hours:"00",minutes:"00",seconds:"00"};k();function k(){a.each(f,function(o){if(f.eq(o).attr(c.currTimeAttr)&&f.eq(o).attr(c.endTimeAttr)){c.dur.push(0);c.remainDur.push(0);b(o)}})}function b(q){var s=+f.eq(q).attr(c.startTimeAttr),p=+f.eq(q).attr(c.endTimeAttr),o=+f.eq(q).attr(c.currTimeAttr);var t=p*1000-o*1000+c.dur[q];var r=s*1000-o*1000+c.dur[q];if(t<0){return}if(t==0){c.timerId[q]="done";e(t,q);clearTimeout(c.timerId[q]);c.endHandle(q);return}if(r>0){e(r,q)}else{if(r==0){c.startHandle(q);return}else{if(t>0){e(t,q)}}}}function e(p,o){l.days=Math.floor(p/(1000*60*60*24));l.hours=Math.floor(p/(1000*60*60))-l.days*24;l.minutes=Math.floor(p/(1000*60))-l.days*60*24-l.hours*60;l.seconds=Math.floor(p/1000)-l.minutes*60-l.hours*60*60-l.days*60*60*24;j.eq(o).html(l.days>=10?l.days:"0"+l.days);h.eq(o).html(l.hours>=10?l.hours:"0"+l.hours);d.eq(o).html(l.minutes>=10?l.minutes:"0"+l.minutes);i.eq(o).html(l.seconds>=10?l.seconds:"0"+l.seconds);c.dur[o]-=1000;c.timerId[o]=setTimeout(function(){b(o)},1000)}}})})(Zepto);