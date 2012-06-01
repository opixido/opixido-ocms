/**
 * ScrollSTOP event
 */
(function(){

    var special = jQuery.event.special,
    uid1 = 'D' + (+new Date()),
    uid2 = 'D' + (+new Date() + 1);

    special.scrollstart = {
        setup: function() {

            var timer,
            handler =  function(evt) {

                var _self = this,
                _args = arguments;

                if (timer) {
                    clearTimeout(timer);
                } else {
                    evt.type = 'scrollstart';
                    jQuery.event.handle.apply(_self, _args);
                }

                timer = setTimeout( function(){
                    timer = null;
                }, special.scrollstop.latency);

            };

            jQuery(this).bind('scroll', handler).data(uid1, handler);

        },
        teardown: function(){
            jQuery(this).unbind( 'scroll', jQuery(this).data(uid1) );
        }
    };

    special.scrollstop = {
        latency: 300,
        setup: function() {

            var timer,
            handler = function(evt) {

                var _self = this,
                _args = arguments;

                if (timer) {
                    clearTimeout(timer);
                }

                timer = setTimeout( function(){

                    timer = null;
                    evt.type = 'scrollstop';
                    jQuery.event.handle.apply(_self, _args);

                }, special.scrollstop.latency);

            };

            jQuery(this).bind('scroll', handler).data(uid2, handler);

        },
        teardown: function() {
            jQuery(this).unbind( 'scroll', jQuery(this).data(uid2) );
        }
    };

})();


/*! jQuery UI - v1.8.20 - 2012-04-30
* https://github.com/jquery/jquery-ui
* Includes: jquery.effects.core.js
* Copyright (c) 2012 AUTHORS.txt; Licensed MIT, GPL */
jQuery.effects||function(a,b){function c(b){var c;return b&&b.constructor==Array&&b.length==3?b:(c=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(b))?[parseInt(c[1],10),parseInt(c[2],10),parseInt(c[3],10)]:(c=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(b))?[parseFloat(c[1])*2.55,parseFloat(c[2])*2.55,parseFloat(c[3])*2.55]:(c=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(b))?[parseInt(c[1],16),parseInt(c[2],16),parseInt(c[3],16)]:(c=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(b))?[parseInt(c[1]+c[1],16),parseInt(c[2]+c[2],16),parseInt(c[3]+c[3],16)]:(c=/rgba\(0, 0, 0, 0\)/.exec(b))?e.transparent:e[a.trim(b).toLowerCase()]}function d(b,d){var e;do{e=a.curCSS(b,d);if(e!=""&&e!="transparent"||a.nodeName(b,"body"))break;d="backgroundColor"}while(b=b.parentNode);return c(e)}function h(){var a=document.defaultView?document.defaultView.getComputedStyle(this,null):this.currentStyle,b={},c,d;if(a&&a.length&&a[0]&&a[a[0]]){var e=a.length;while(e--)c=a[e],typeof a[c]=="string"&&(d=c.replace(/\-(\w)/g,function(a,b){return b.toUpperCase()}),b[d]=a[c])}else for(c in a)typeof a[c]=="string"&&(b[c]=a[c]);return b}function i(b){var c,d;for(c in b)d=b[c],(d==null||a.isFunction(d)||c in g||/scrollbar/.test(c)||!/color/i.test(c)&&isNaN(parseFloat(d)))&&delete b[c];return b}function j(a,b){var c={_:0},d;for(d in b)a[d]!=b[d]&&(c[d]=b[d]);return c}function k(b,c,d,e){typeof b=="object"&&(e=c,d=null,c=b,b=c.effect),a.isFunction(c)&&(e=c,d=null,c={});if(typeof c=="number"||a.fx.speeds[c])e=d,d=c,c={};return a.isFunction(d)&&(e=d,d=null),c=c||{},d=d||c.duration,d=a.fx.off?0:typeof d=="number"?d:d in a.fx.speeds?a.fx.speeds[d]:a.fx.speeds._default,e=e||c.complete,[b,c,d,e]}function l(b){return!b||typeof b=="number"||a.fx.speeds[b]?!0:typeof b=="string"&&!a.effects[b]?!0:!1}a.effects={},a.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","borderColor","color","outlineColor"],function(b,e){a.fx.step[e]=function(a){a.colorInit||(a.start=d(a.elem,e),a.end=c(a.end),a.colorInit=!0),a.elem.style[e]="rgb("+Math.max(Math.min(parseInt(a.pos*(a.end[0]-a.start[0])+a.start[0],10),255),0)+","+Math.max(Math.min(parseInt(a.pos*(a.end[1]-a.start[1])+a.start[1],10),255),0)+","+Math.max(Math.min(parseInt(a.pos*(a.end[2]-a.start[2])+a.start[2],10),255),0)+")"}});var e={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]},f=["add","remove","toggle"],g={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};a.effects.animateClass=function(b,c,d,e){return a.isFunction(d)&&(e=d,d=null),this.queue(function(){var g=a(this),k=g.attr("style")||" ",l=i(h.call(this)),m,n=g.attr("class")||"";a.each(f,function(a,c){b[c]&&g[c+"Class"](b[c])}),m=i(h.call(this)),g.attr("class",n),g.animate(j(l,m),{queue:!1,duration:c,easing:d,complete:function(){a.each(f,function(a,c){b[c]&&g[c+"Class"](b[c])}),typeof g.attr("style")=="object"?(g.attr("style").cssText="",g.attr("style").cssText=k):g.attr("style",k),e&&e.apply(this,arguments),a.dequeue(this)}})})},a.fn.extend({_addClass:a.fn.addClass,addClass:function(b,c,d,e){return c?a.effects.animateClass.apply(this,[{add:b},c,d,e]):this._addClass(b)},_removeClass:a.fn.removeClass,removeClass:function(b,c,d,e){return c?a.effects.animateClass.apply(this,[{remove:b},c,d,e]):this._removeClass(b)},_toggleClass:a.fn.toggleClass,toggleClass:function(c,d,e,f,g){return typeof d=="boolean"||d===b?e?a.effects.animateClass.apply(this,[d?{add:c}:{remove:c},e,f,g]):this._toggleClass(c,d):a.effects.animateClass.apply(this,[{toggle:c},d,e,f])},switchClass:function(b,c,d,e,f){return a.effects.animateClass.apply(this,[{add:c,remove:b},d,e,f])}}),a.extend(a.effects,{version:"1.8.20",save:function(a,b){for(var c=0;c<b.length;c++)b[c]!==null&&a.data("ec.storage."+b[c],a[0].style[b[c]])},restore:function(a,b){for(var c=0;c<b.length;c++)b[c]!==null&&a.css(b[c],a.data("ec.storage."+b[c]))},setMode:function(a,b){return b=="toggle"&&(b=a.is(":hidden")?"show":"hide"),b},getBaseline:function(a,b){var c,d;switch(a[0]){case"top":c=0;break;case"middle":c=.5;break;case"bottom":c=1;break;default:c=a[0]/b.height}switch(a[1]){case"left":d=0;break;case"center":d=.5;break;case"right":d=1;break;default:d=a[1]/b.width}return{x:d,y:c}},createWrapper:function(b){if(b.parent().is(".ui-effects-wrapper"))return b.parent();var c={width:b.outerWidth(!0),height:b.outerHeight(!0),"float":b.css("float")},d=a("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),e=document.activeElement;return b.wrap(d),(b[0]===e||a.contains(b[0],e))&&a(e).focus(),d=b.parent(),b.css("position")=="static"?(d.css({position:"relative"}),b.css({position:"relative"})):(a.extend(c,{position:b.css("position"),zIndex:b.css("z-index")}),a.each(["top","left","bottom","right"],function(a,d){c[d]=b.css(d),isNaN(parseInt(c[d],10))&&(c[d]="auto")}),b.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"})),d.css(c).show()},removeWrapper:function(b){var c,d=document.activeElement;return b.parent().is(".ui-effects-wrapper")?(c=b.parent().replaceWith(b),(b[0]===d||a.contains(b[0],d))&&a(d).focus(),c):b},setTransition:function(b,c,d,e){return e=e||{},a.each(c,function(a,c){var f=b.cssUnit(c);f[0]>0&&(e[c]=f[0]*d+f[1])}),e}}),a.fn.extend({effect:function(b,c,d,e){var f=k.apply(this,arguments),g={options:f[1],duration:f[2],callback:f[3]},h=g.options.mode,i=a.effects[b];return a.fx.off||!i?h?this[h](g.duration,g.callback):this.each(function(){g.callback&&g.callback.call(this)}):i.call(this,g)},_show:a.fn.show,show:function(a){if(l(a))return this._show.apply(this,arguments);var b=k.apply(this,arguments);return b[1].mode="show",this.effect.apply(this,b)},_hide:a.fn.hide,hide:function(a){if(l(a))return this._hide.apply(this,arguments);var b=k.apply(this,arguments);return b[1].mode="hide",this.effect.apply(this,b)},__toggle:a.fn.toggle,toggle:function(b){if(l(b)||typeof b=="boolean"||a.isFunction(b))return this.__toggle.apply(this,arguments);var c=k.apply(this,arguments);return c[1].mode="toggle",this.effect.apply(this,c)},cssUnit:function(b){var c=this.css(b),d=[];return a.each(["em","px","%","pt"],function(a,b){c.indexOf(b)>0&&(d=[parseFloat(c),b])}),d}}),a.easing.jswing=a.easing.swing,a.extend(a.easing,{def:"easeOutQuad",swing:function(b,c,d,e,f){return a.easing[a.easing.def](b,c,d,e,f)},easeInQuad:function(a,b,c,d,e){return d*(b/=e)*b+c},easeOutQuad:function(a,b,c,d,e){return-d*(b/=e)*(b-2)+c},easeInOutQuad:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b+c:-d/2*(--b*(b-2)-1)+c},easeInCubic:function(a,b,c,d,e){return d*(b/=e)*b*b+c},easeOutCubic:function(a,b,c,d,e){return d*((b=b/e-1)*b*b+1)+c},easeInOutCubic:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b*b+c:d/2*((b-=2)*b*b+2)+c},easeInQuart:function(a,b,c,d,e){return d*(b/=e)*b*b*b+c},easeOutQuart:function(a,b,c,d,e){return-d*((b=b/e-1)*b*b*b-1)+c},easeInOutQuart:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b*b*b+c:-d/2*((b-=2)*b*b*b-2)+c},easeInQuint:function(a,b,c,d,e){return d*(b/=e)*b*b*b*b+c},easeOutQuint:function(a,b,c,d,e){return d*((b=b/e-1)*b*b*b*b+1)+c},easeInOutQuint:function(a,b,c,d,e){return(b/=e/2)<1?d/2*b*b*b*b*b+c:d/2*((b-=2)*b*b*b*b+2)+c},easeInSine:function(a,b,c,d,e){return-d*Math.cos(b/e*(Math.PI/2))+d+c},easeOutSine:function(a,b,c,d,e){return d*Math.sin(b/e*(Math.PI/2))+c},easeInOutSine:function(a,b,c,d,e){return-d/2*(Math.cos(Math.PI*b/e)-1)+c},easeInExpo:function(a,b,c,d,e){return b==0?c:d*Math.pow(2,10*(b/e-1))+c},easeOutExpo:function(a,b,c,d,e){return b==e?c+d:d*(-Math.pow(2,-10*b/e)+1)+c},easeInOutExpo:function(a,b,c,d,e){return b==0?c:b==e?c+d:(b/=e/2)<1?d/2*Math.pow(2,10*(b-1))+c:d/2*(-Math.pow(2,-10*--b)+2)+c},easeInCirc:function(a,b,c,d,e){return-d*(Math.sqrt(1-(b/=e)*b)-1)+c},easeOutCirc:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},easeInOutCirc:function(a,b,c,d,e){return(b/=e/2)<1?-d/2*(Math.sqrt(1-b*b)-1)+c:d/2*(Math.sqrt(1-(b-=2)*b)+1)+c},easeInElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e)==1)return c+d;g||(g=e*.3);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return-(h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g))+c},easeOutElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e)==1)return c+d;g||(g=e*.3);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return h*Math.pow(2,-10*b)*Math.sin((b*e-f)*2*Math.PI/g)+d+c},easeInOutElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e/2)==2)return c+d;g||(g=e*.3*1.5);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return b<1?-0.5*h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)+c:h*Math.pow(2,-10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)*.5+d+c},easeInBack:function(a,c,d,e,f,g){return g==b&&(g=1.70158),e*(c/=f)*c*((g+1)*c-g)+d},easeOutBack:function(a,c,d,e,f,g){return g==b&&(g=1.70158),e*((c=c/f-1)*c*((g+1)*c+g)+1)+d},easeInOutBack:function(a,c,d,e,f,g){return g==b&&(g=1.70158),(c/=f/2)<1?e/2*c*c*(((g*=1.525)+1)*c-g)+d:e/2*((c-=2)*c*(((g*=1.525)+1)*c+g)+2)+d},easeInBounce:function(b,c,d,e,f){return e-a.easing.easeOutBounce(b,f-c,0,e,f)+d},easeOutBounce:function(a,b,c,d,e){return(b/=e)<1/2.75?d*7.5625*b*b+c:b<2/2.75?d*(7.5625*(b-=1.5/2.75)*b+.75)+c:b<2.5/2.75?d*(7.5625*(b-=2.25/2.75)*b+.9375)+c:d*(7.5625*(b-=2.625/2.75)*b+.984375)+c},easeInOutBounce:function(b,c,d,e,f){return c<f/2?a.easing.easeInBounce(b,c*2,0,e,f)*.5+d:a.easing.easeOutBounce(b,c*2-f,0,e,f)*.5+e*.5+d}})}(jQuery);;


/**
 * Overscroll v1.6.2
 *  http://azoffdesign.com/overscroll
 *
 * Copyright 2012, Jonathan Azoff
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *  http://jquery.org/license
 */(function(a,b,c,d,e,f,g,h){"use strict";var i="overscroll",j=function(){var c=g.browser,e,f=b.createElement(i).style,k=c.webkit?"webkit":c.mozilla?"moz":c.msie?"ms":c.opera?"o":"",l=k?["-","-"].join(k):"";return j={prefix:k,overflowScrolling:!1},g.each(k?[k,""]:[k],function(b,c){var d=c?c+"RequestAnimationFrame":"requestAnimationFrame",e=c?c+"OverflowScrolling":"overflowScrolling";a[d]!==h&&(j.animate=function(b){a[d].call(a,b)}),f[e]!==h?j.overflowScrolling=l+"overflow-scrolling":!j.touchEvents}),j.touchEvents="ontouchstart"in a,j.animate||(j.animate=function(a){d(a,1e3/60)}),k==="moz"||k==="webkit"?(j.cursorGrab=l+"grab",j.cursorGrabbing=l+"grabbing"):(e="https://mail.google.com/mail/images/2/",j.cursorGrab="url("+e+"openhand.cur), default",j.cursorGrabbing="url("+e+"closedhand.cur), default"),j}(),k={drag:"mousemove touchmove",end:"mouseup mouseleave click touchend touchcancel",hover:"mouseenter mouseleave",ignored:"select dragstart drag",scroll:"scroll",start:"mousedown touchstart",wheel:"mousewheel DOMMouseScroll"},l={captureThreshold:3,driftDecay:1.1,driftSequences:22,driftTimeout:100,scrollDelta:15,thumbOpacity:.7,thumbThickness:6,thumbTimeout:400,wheelDelta:20},m={cancelOn:"",direction:"multi",dragHold:!1,hoverThumbs:!1,scrollDelta:l.scrollDelta,showThumbs:!0,persistThumbs:!1,wheelDelta:l.wheelDelta,wheelDirection:"vertical",zIndex:999},n=function(a,b){b.trigger("overscroll:"+a)},o=function(){return(new Date).getTime()},p=function(a,b,c){return b.x=a.pageX,b.y=a.pageY,b.time=o(),b.index=c,b},q=function(a,b,c,d){var e,f;a&&a.added&&(a.horizontal&&(e=c*(1+b.container.width/b.container.scrollWidth),f=d+b.thumbs.horizontal.top,a.horizontal.css("margin",f+"px 0 0 "+e+"px")),a.vertical&&(e=c+b.thumbs.vertical.left,f=d*(1+b.container.height/b.container.scrollHeight),a.vertical.css("margin",f+"px 0 0 "+e+"px")))},r=function(a,b,c){a&&a.added&&!b.persistThumbs&&(c?(a.vertical&&a.vertical.stop(!0,!0).fadeTo("fast",l.thumbOpacity),a.horizontal&&a.horizontal.stop(!0,!0).fadeTo("fast",l.thumbOpacity)):(a.vertical&&a.vertical.fadeTo("fast",0),a.horizontal&&a.horizontal.fadeTo("fast",0)))},s=function(a){var b=a.data("events"),c=b&&b.click?b.click.slice():[];b&&delete b.click,a.one("mouseup touchend touchcancel",function(){return g.each(c,function(b,c){a.click(c)}),!1})},t=function(a){var b=a.data,c=b.thumbs,d=b.options,e=a.type==="mouseenter";r(c,d,e)},u=function(a){var b=a.data;b.flags.dragged||q(b.thumbs,b.sizing,this.scrollLeft,this.scrollTop)},v=function(a){a.preventDefault();var b=a.data,c=b.options,f=b.sizing,g=b.thumbs,h=b.wheel,k=b.flags,m,n=a.originalEvent;k.drifting=!1,n.wheelDelta&&(m=n.wheelDelta/(j.prefix==="o"?-120:120)),n.detail&&(m=-n.detail/3),m*=c.wheelDelta,h||(b.target.data(i).dragging=k.dragging=!0,b.wheel=h={timeout:null},r(g,c,!0)),c.wheelDirection==="horizontal"?this.scrollLeft-=m:this.scrollTop-=m,h.timeout&&e(h.timeout),q(g,f,this.scrollLeft,this.scrollTop),h.timeout=d(function(){b.target.data(i).dragging=k.dragging=!1,r(g,c,b.wheel=null)},l.thumbTimeout)},w=function(a){a.preventDefault();var b=a.data,c=a.originalEvent.touches,d=b.options,e=b.sizing,f=b.thumbs,g=b.position,h=b.flags,k=b.target.get(0);j.touchEvents&&c&&c.length&&(a=c[0]),h.dragged||r(f,d,!0),h.dragged=!0,d.direction!=="vertical"&&(k.scrollLeft-=a.pageX-g.x),b.options.direction!=="horizontal"&&(k.scrollTop-=a.pageY-g.y),p(a,b.position),--b.capture.index<=0&&(b.target.data(i).dragging=h.dragging=!0,p(a,b.capture,l.captureThreshold)),q(f,e,k.scrollLeft,k.scrollTop)},x=function(a,b,c){var d=b.data,e,f,g,h,i=d.capture,k=d.options,m=d.sizing,p=d.thumbs,r=o()-i.time,s=a.scrollLeft,t=a.scrollTop,u=l.driftDecay;if(r>l.driftTimeout)return c(d);e=k.scrollDelta*(b.pageX-i.x),f=k.scrollDelta*(b.pageY-i.y),k.direction!=="vertical"&&(s-=e),k.direction!=="horizontal"&&(t-=f),g=e/l.driftSequences,h=f/l.driftSequences,n("driftstart",d.target),d.drifting=!0,j.animate(function v(){if(d.drifting){var b=1,e=-1;d.drifting=!1;if(h>b&&a.scrollTop>t||h<e&&a.scrollTop<t)d.drifting=!0,a.scrollTop-=h,h/=u;if(g>b&&a.scrollLeft>s||g<e&&a.scrollLeft<s)d.drifting=!0,a.scrollLeft-=g,g/=u;q(p,m,a.scrollLeft,a.scrollTop),j.animate(v)}else n("driftend",d.target),c(d)})},y=function(a){var b=a.data,c=b.target,d=b.start=g(a.target),e=b.flags;e.drifting=!1,d.size()&&!d.is(b.options.cancelOn)&&(j.touchEvents||a.preventDefault(),c.css("cursor",j.cursorGrabbing),c.data(i).dragging=e.dragging=e.dragged=!1,b.options.dragHold?g(document).on(k.drag,b,w):c.on(k.drag,b,w),b.position=p(a,{}),b.capture=p(a,{},l.captureThreshold),n("dragstart",c))},z=function(a){var b=a.data,c=b.target,d=b.options,e=b.flags,f=b.thumbs,h=function(){f&&!d.hoverThumbs&&r(f,d,!1)};d.dragHold?g(document).unbind(k.drag,w):c.unbind(k.drag,w),b.position&&(n("dragend",c),e.dragging?x(c.get(0),a,h):h()),e.dragging&&b.start.is(a.target)&&s(b.start),c.data(i).dragging=b.start=b.capture=b.position=e.dragged=e.dragging=!1,c.css("cursor",j.cursorGrab)},A=function(a){return a=g.extend({},m,a),a.direction!=="multi"&&a.direction!==a.wheelDirection&&(a.wheelDirection=a.direction),a.scrollDelta=c.abs(a.scrollDelta),a.wheelDelta=c.abs(a.wheelDelta),a.scrollLeft=a.scrollLeft===h?null:c.abs(a.scrollLeft),a.scrollTop=a.scrollTop===h?null:c.abs(a.scrollTop),a},B=function(a){var b=g(a),c=b.width(),d=b.height(),e=c>=a.scrollWidth?c:a.scrollWidth,f=d>=a.scrollHeight?d:a.scrollHeight;return{container:{width:c,height:d,scrollWidth:e,scrollHeight:f},thumbs:{horizontal:{width:c*c/e,height:l.thumbThickness,corner:l.thumbThickness/2,left:0,top:d-l.thumbThickness},vertical:{width:l.thumbThickness,height:d*d/f,corner:l.thumbThickness/2,left:c-l.thumbThickness,top:0}}}},C=function(a,b){var c=g(a),d,e=c.data(i)||{},f=c.attr("style"),h=b?function(){e=c.data(i),d=e.thumbs,f?c.attr("style",f):c.removeAttr("style"),d&&(d.horizontal&&d.horizontal.remove(),d.vertical&&d.vertical.remove()),c.removeData(i).off(k.wheel,v).off(k.start,y).off(k.end,z).off(k.ignored,!1)}:g.noop;return g.isFunction(e.remover)?e.remover:h},D=function(a,b){return{position:"absolute",opacity:b.persistThumbs?l.thumbOpacity:0,"background-color":"black",width:a.width+"px",height:a.height+"px","border-radius":a.corner+"px",margin:a.top+"px 0 0 "+a.left+"px","z-index":b.zIndex}},E=function(a,b,c){var d="<div/>",e={},f=!1;return b.container.scrollWidth>0&&c.direction!=="vertical"&&(f=D(b.thumbs.horizontal,c),e.horizontal=g(d).css(f).prependTo(a)),b.container.scrollHeight>0&&c.direction!=="horizontal"&&(f=D(b.thumbs.vertical,c),e.vertical=g(d).css(f).prependTo(a)),e.added=!!f,e},F=function(a,b){var c,d,e={flags:{dragging:!1},options:b=A(b),remover:C(a,!0),sizing:d=B(a)};e.target=a=g(a).css({position:"relative",overflow:"hidden",cursor:j.cursorGrab}).on(k.wheel,e,v).on(k.start,e,y).on(k.end,e,z).on(k.scroll,e,u).on(k.ignored,!1),b.dragHold?g(document).on(k.end,e,z):e.target.on(k.end,e,z),b.scrollLeft!==null&&a.scrollLeft(b.scrollLeft),b.scrollTop!==null&&a.scrollTop(b.scrollTop),b.showThumbs&&(e.thumbs=c=E(a,d,b),c.added&&(q(c,d,a.scrollLeft(),a.scrollTop()),b.hoverThumbs&&a.on(k.hover,e,t))),a.data(i,e)},G=function(a){C(a)()},H=function(a){return this.removeOverscroll().each(function(){F(this,a)})},I=function(){return this.removeOverscroll().each(function(){g(this).data(i,{remover:C(this)}).css(j.overflowScrolling,"touch").css("overflow","auto")})},J=function(){return this.each(function(){G(this)})};H.settings=l,g.extend(f,{overscroll:j.overflowScrolling?I:H,removeOverscroll:J})})(window,document,Math,setTimeout,clearTimeout,jQuery.fn,jQuery);




/**
 *  Version 2.1
 *      -Contributors: "mindinquiring" : filter to exclude any stylesheet other than print.
 *  Tested ONLY in IE 8 and FF 3.6. No official support for other browsers, but will
 *      TRY to accomodate challenges in other browsers.
 *  Example:
 *      Print Button: <div id="print_button">Print</div>
 *      Print Area  : <div class="PrintArea"> ... html ... </div>
 *      Javascript  : <script>
 *                       $("div#print_button").click(function(){
 *                           $("div.PrintArea").printArea( [OPTIONS] );
 *                       });
 *                     </script>
 *  options are passed as json (json example: {mode: "popup", popClose: false})
 *
 *  {OPTIONS} | [type]    | (default), values      | Explanation
 *  --------- | --------- | ---------------------- | -----------
 *  @mode     | [string]  | ("iframe"),"popup"     | printable window is either iframe or browser popup
 *  @popHt    | [number]  | (500)                  | popup window height
 *  @popWd    | [number]  | (400)                  | popup window width
 *  @popX     | [number]  | (500)                  | popup window screen X position
 *  @popY     | [number]  | (500)                  | popup window screen Y position
 *  @popTitle | [string]  | ('')                   | popup window title element
 *  @popClose | [boolean] | (false),true           | popup window close after printing
 *  @strict   | [boolean] | (undefined),true,false | strict or loose(Transitional) html 4.01 document standard or undefined to not include at all (only for popup option)
 */
(function($) {
    var counter = 0;
    var modes = { iframe : "iframe", popup : "popup" };
    var defaults = { mode     : modes.iframe,
                     popHt    : 500,
                     popWd    : 400,
                     popX     : 200,
                     popY     : 200,
                     popTitle : '',
                     popClose : false };

    var settings = {};//global settings

    $.fn.printArea = function( options )
        {
            $.extend( settings, defaults, options );

            counter++;
            var idPrefix = "printArea_";
            $( "[id^=" + idPrefix + "]" ).remove();
            var ele = getFormData( $(this) );

            settings.id = idPrefix + counter;

            var writeDoc;
            var printWindow;

            switch ( settings.mode )
            {
                case modes.iframe :
                    var f = new Iframe();
                    writeDoc = f.doc;
                    printWindow = f.contentWindow || f;
                    break;
                case modes.popup :
                    printWindow = new Popup();
                    writeDoc = printWindow.doc;
            }

            writeDoc.open();
            writeDoc.write( docType() + "<html>" + getHead() + getBody(ele) + "</html>" );
            writeDoc.close();
            if(settings.preCall) {
                settings.preCall(writeDoc);
            }
            printWindow.focus();

            printWindow.print();

            if ( settings.mode == modes.popup && settings.popClose )
                printWindow.close();

            if(settings.callBack) {
                settings.callBack();
            }
        }

    function docType()
    {
        if ( settings.mode == modes.iframe || !settings.strict ) return "";

        var standard = settings.strict == false ? " Trasitional" : "";
        var dtd = settings.strict == false ? "loose" : "strict";

        return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01' + standard + '//EN" "http://www.w3.org/TR/html4/' + dtd +  '.dtd">';
    }

    function getHead()
    {
        var head = "<head><title>" + settings.popTitle + "</title>";
        $(document).find("link")
            .filter(function(){
                    if($(this).attr("rel")) {
                        return $(this).attr("rel").toLowerCase() == "stylesheet";
                    } else {
                        return false;
                    }
                })
            .filter(function(){ // this filter contributed by "mindinquiring"
                    var media = $(this).attr("media");
                    if(media) {
                        return (media.toLowerCase() == "all" || media.toLowerCase() == "print")
                    } else {
                        return true;
                    }
                })
            .each(function(){
                    head += '<link type="text/css" rel="stylesheet" href="' + $(this).attr("href") + '" >';
                });
        head += "</head>";
        return head;
    }

    function getBody( printElement )
    {
        return '<body><div class="' + $(printElement).attr("class") + '">' + getRealHtml($(printElement).html()) + '</div></body>';
    }

    function getRealHtml(html) {
        /*
        html = html.replace(/data-original=/gi,"src=");
        html = html.replace(/lazy" src/,'lazy" data-oldsrc');
        html = html.replace(/style=/,'data-style=');

        alert(html);
        */
        return html;
    }

    function getFormData( ele )
    {
        $("input,select,textarea", ele).each(function(){
            // In cases where radio, checkboxes and select elements are selected and deselected, and the print
            // button is pressed between select/deselect, the print screen shows incorrectly selected elements.
            // To ensure that the correct inputs are selected, when eventually printed, we must inspect each dom element
            var type = $(this).attr("type");
            if ( type == "radio" || type == "checkbox" )
            {
                if ( $(this).is(":not(:checked)") ) this.removeAttribute("checked");
                else this.setAttribute( "checked", true );
            }
            else if ( type == "text" )
                this.setAttribute( "value", $(this).val() );
            else if ( type == "select-multiple" || type == "select-one" )
                $(this).find( "option" ).each( function() {
                    if ( $(this).is(":not(:selected)") ) this.removeAttribute("selected");
                    else this.setAttribute( "selected", true );
                });
            else if ( type == "textarea" )
            {
                var v = $(this).attr( "value" );
                if ($.browser.mozilla)
                {
                    if (this.firstChild) this.firstChild.textContent = v;
                    else this.textContent = v;
                }
                else this.innerHTML = v;
            }
        });
        return ele;
    }

    function Iframe()
    {
        var frameId = settings.id;
        var iframeStyle = 'border:0;position:absolute;width:0px;height:0px;left:0px;top:0px;';
        var iframe;

        try
        {
            iframe = document.createElement('iframe');
            document.body.appendChild(iframe);
            $(iframe).attr({ style: iframeStyle, id: frameId, src: "" });
            iframe.doc = null;
            iframe.doc = iframe.contentDocument ? iframe.contentDocument : ( iframe.contentWindow ? iframe.contentWindow.document : iframe.document);
        }
        catch( e ) { throw e + ". iframes may not be supported in this browser."; }

        if ( iframe.doc == null ) throw "Cannot find document.";

        return iframe;
    }

    function Popup()
    {
        var windowAttr = "location=yes,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no";
        windowAttr += ",width=" + settings.popWd + ",height=" + settings.popHt;
        windowAttr += ",resizable=yes,screenX=" + settings.popX + ",screenY=" + settings.popY + ",personalbar=no,scrollbars=yes";

        var newWin = window.open( "", "_blank",  windowAttr );

        newWin.doc = newWin.document;

        return newWin;
    }
})(jQuery);