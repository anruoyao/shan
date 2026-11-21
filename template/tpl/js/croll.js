
(function(){
var m = Math,
	mround = function (r) { return r >> 0; },
	vendor = (/webkit/i).test(navigator.appVersion) ? 'webkit' :
		(/firefox/i).test(navigator.userAgent) ? 'Moz' :
		'opera' in window ? 'O' : '',

    
    isAndroid = (/android/gi).test(navigator.appVersion),
    isIDevice = (/iphone|ipad/gi).test(navigator.appVersion),
    isPlaybook = (/playbook/gi).test(navigator.appVersion),
    isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),

    has3d = 'WebKitCSSMatrix' in window && 'm11' in new WebKitCSSMatrix(),
    hasTouch = 'ontouchstart' in window && !isTouchPad,
    hasTransform = vendor + 'Transform' in document.documentElement.style,
    hasTransitionEnd = isIDevice || isPlaybook,

	nextFrame = (function() {
	    return window.requestAnimationFrame
			|| window.webkitRequestAnimationFrame
			|| window.mozRequestAnimationFrame
			|| window.oRequestAnimationFrame
			|| window.msRequestAnimationFrame
			|| function(callback) { return setTimeout(callback, 17); }
	})(),
	cancelFrame = (function () {
	    return window.cancelRequestAnimationFrame
			|| window.webkitCancelAnimationFrame
			|| window.webkitCancelRequestAnimationFrame
			|| window.mozCancelRequestAnimationFrame
			|| window.oCancelRequestAnimationFrame
			|| window.msCancelRequestAnimationFrame
			|| clearTimeout
	})(),

	
	RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize',
	START_EV = hasTouch ? 'touchstart' : 'mousedown',
	MOVE_EV = hasTouch ? 'touchmove' : 'mousemove',
	END_EV = hasTouch ? 'touchend' : 'mouseup',
	CANCEL_EV = hasTouch ? 'touchcancel' : 'mouseup',

	
	trnOpen = 'translate' + (has3d ? '3d(' : '('),
	trnClose = has3d ? ',0)' : ')',

	
	iScroll = function (el, options) {
		var that = this,
			doc = document,
			i;

		that.wrapper = typeof el == 'object' ? el : doc.getElementById(el);
		that.wrapper.style.overflow = 'hidden';
		that.scroller = that.wrapper.children[0];

		
		that.options = {
			hScroll: true,
			vScroll: true,
			x: 0,
			y: 0,
			bounce: true,
			bounceLock: false,
			momentum: true,
			lockDirection: true,
			useTransform: true,
			useTransition: false,

			
			onRefresh: null,
			onBeforeScrollStart: function (e) { e.preventDefault(); },
			onScrollStart: null,
			onBeforeScrollMove: null,
			onScrollMove: null,
			onBeforeScrollEnd: null,
			onScrollEnd: null,
			onTouchEnd: null,
			onDestroy: null
		};

		
		for (i in options) that.options[i] = options[i];

		
		that.x = that.options.x;
		that.y = that.options.y;

		
		that.options.useTransform = hasTransform ? that.options.useTransform : false;
		that.options.hScrollbar = that.options.hScroll && that.options.hScrollbar;
		that.options.vScrollbar = that.options.vScroll && that.options.vScrollbar;
		that.options.useTransition = hasTransitionEnd && that.options.useTransition;

		
		that.scroller.style[vendor + 'TransitionProperty'] = that.options.useTransform ? '-' + vendor.toLowerCase() + '-transform' : 'top left';
		that.scroller.style[vendor + 'TransitionDuration'] = '0';
		that.scroller.style[vendor + 'TransformOrigin'] = '0 0';
		if (that.options.useTransition) that.scroller.style[vendor + 'TransitionTimingFunction'] = 'cubic-bezier(0.33,0.66,0.66,1)';
		
		if (that.options.useTransform) that.scroller.style[vendor + 'Transform'] = trnOpen + that.x + 'px,' + that.y + 'px' + trnClose;
		else that.scroller.style.cssText += ';position:absolute;top:' + that.y + 'px;left:' + that.x + 'px';

		that.refresh();

		that._bind(RESIZE_EV, window);
		that._bind(START_EV);
		if (!hasTouch) that._bind('mouseout', that.wrapper);
	};


iScroll.prototype = {
	enabled: true,
	x: 0,
	y: 0,
	steps: [],
	scale: 1,
	
	handleEvent: function (e) {
		var that = this;
		switch(e.type) {
			case START_EV:
				if (!hasTouch && e.button !== 0) return;
				that._start(e);
				break;
			case MOVE_EV: that._move(e); break;
			case END_EV:
			case CANCEL_EV: that._end(e); break;
			case RESIZE_EV: that._resize(); break;
			case 'mouseout': that._mouseout(e); break;
			case 'webkitTransitionEnd': that._transitionEnd(e); break;
		}
	},

	_resize: function () {
		this.refresh();
	},
	
	_pos: function (x, y) {
		x = this.hScroll ? x : 0;
		y = this.vScroll ? y : 0;

		if (this.options.useTransform) {
			this.scroller.style[vendor + 'Transform'] = trnOpen + x + 'px,' + y + 'px' + trnClose + ' scale(' + this.scale + ')';
		} else {
			x = mround(x);
			y = mround(y);
			this.scroller.style.left = x + 'px';
			this.scroller.style.top = y + 'px';
		}

		this.x = x;
		this.y = y;
	},

	_start: function (e) {
		var that = this,
			point = hasTouch ? e.touches[0] : e,
			matrix, x, y;

		if (!that.enabled) return;

		if (that.options.onBeforeScrollStart) that.options.onBeforeScrollStart.call(that, e);
		
		if (that.options.useTransition) that._transitionTime(0);

		that.moved = false;
		that.animating = false;
		that.zoomed = false;
		that.distX = 0;
		that.distY = 0;
		that.absDistX = 0;
		that.absDistY = 0;
		that.dirX = 0;
		that.dirY = 0;

		if (that.options.momentum) {
			if (that.options.useTransform) {
				
				matrix = getComputedStyle(that.scroller, null)[vendor + 'Transform'].replace(/[^0-9-.,]/g, '').split(',');
				x = matrix[4] * 1;
				y = matrix[5] * 1;
			} else {
				x = getComputedStyle(that.scroller, null).left.replace(/[^0-9-]/g, '') * 1;
				y = getComputedStyle(that.scroller, null).top.replace(/[^0-9-]/g, '') * 1;
			}
			
			if (x != that.x || y != that.y) {
				if (that.options.useTransition) that._unbind('webkitTransitionEnd');
				else cancelFrame(that.aniTime);
				that.steps = [];
				that._pos(x, y);
			}
		}

		that.startX = that.x;
		that.startY = that.y;
		that.pointX = point.pageX;
		that.pointY = point.pageY;

		that.startTime = e.timeStamp || Date.now();

		if (that.options.onScrollStart) that.options.onScrollStart.call(that, e);

		that._bind(MOVE_EV);
		that._bind(END_EV);
		that._bind(CANCEL_EV);
	},
	
	_move: function (e) {
		var that = this,
			point = hasTouch ? e.touches[0] : e,
			deltaX = point.pageX - that.pointX,
			deltaY = point.pageY - that.pointY,
			newX = that.x + deltaX,
			newY = that.y + deltaY,
			timestamp = e.timeStamp || Date.now();

		if (that.options.onBeforeScrollMove) that.options.onBeforeScrollMove.call(that, e);

		that.pointX = point.pageX;
		that.pointY = point.pageY;

		
		if (newX > 0 || newX < that.maxScrollX) {
			newX = that.options.bounce ? that.x + (deltaX / 2) : newX >= 0 || that.maxScrollX >= 0 ? 0 : that.maxScrollX;
		}
		if (newY > 0 || newY < that.maxScrollY) { 
			newY = that.options.bounce ? that.y + (deltaY / 2) : newY >= 0 || that.maxScrollY >= 0 ? 0 : that.maxScrollY;
		}

		that.distX += deltaX;
		that.distY += deltaY;
		that.absDistX = m.abs(that.distX);
		that.absDistY = m.abs(that.distY);

		if (that.absDistX < 6 && that.absDistY < 6) {
			return;
		}

		
		if (that.options.lockDirection) {
			if (that.absDistX > that.absDistY + 5) {
				newY = that.y;
				deltaY = 0;
			} else if (that.absDistY > that.absDistX + 5) {
				newX = that.x;
				deltaX = 0;
			}
		}

		that.moved = true;
		that._pos(newX, newY);
		that.dirX = deltaX > 0 ? -1 : deltaX < 0 ? 1 : 0;
		that.dirY = deltaY > 0 ? -1 : deltaY < 0 ? 1 : 0;

		if (timestamp - that.startTime > 300) {
			that.startTime = timestamp;
			that.startX = that.x;
			that.startY = that.y;
		}
		
		if (that.options.onScrollMove) that.options.onScrollMove.call(that, e);
	},
	
	_end: function (e) {
		if (hasTouch && e.touches.length != 0) return;

		var that = this,
			point = hasTouch ? e.changedTouches[0] : e,
			target, ev,
			momentumX = { dist:0, time:0 },
			momentumY = { dist:0, time:0 },
			duration = (e.timeStamp || Date.now()) - that.startTime,
			newPosX = that.x,
			newPosY = that.y,
			newDuration;

		that._unbind(MOVE_EV);
		that._unbind(END_EV);
		that._unbind(CANCEL_EV);

		if (that.options.onBeforeScrollEnd) that.options.onBeforeScrollEnd.call(that, e);

		if (!that.moved) {
			if (hasTouch) {
				
				target = point.target;
				while (target.nodeType != 1) target = target.parentNode;

				if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA') {
					ev = document.createEvent('MouseEvents');
					ev.initMouseEvent('click', true, true, e.view, 1,
						point.screenX, point.screenY, point.clientX, point.clientY,
						e.ctrlKey, e.altKey, e.shiftKey, e.metaKey,
						0, null);
					ev._fake = true;
					target.dispatchEvent(ev);
				}
			}

			that._resetPos(200);

			if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
			return;
		}

		if (duration < 300 && that.options.momentum) {
			momentumX = newPosX ? that._momentum(newPosX - that.startX, duration, -that.x, that.scrollerW - that.wrapperW + that.x, that.options.bounce ? that.wrapperW : 0) : momentumX;
			momentumY = newPosY ? that._momentum(newPosY - that.startY, duration, -that.y, (that.maxScrollY < 0 ? that.scrollerH - that.wrapperH + that.y : 0), that.options.bounce ? that.wrapperH : 0) : momentumY;

			newPosX = that.x + momentumX.dist;
			newPosY = that.y + momentumY.dist;

 			if ((that.x > 0 && newPosX > 0) || (that.x < that.maxScrollX && newPosX < that.maxScrollX)) momentumX = { dist:0, time:0 };
 			if ((that.y > 0 && newPosY > 0) || (that.y < that.maxScrollY && newPosY < that.maxScrollY)) momentumY = { dist:0, time:0 };
		}

		if (momentumX.dist || momentumY.dist) {
			newDuration = m.max(m.max(momentumX.time, momentumY.time), 10);

			that.scrollTo(mround(newPosX), mround(newPosY), newDuration);

			if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
			return;
		}

		that._resetPos(200);
		if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
	},
	
	_resetPos: function (time) {
		var that = this,
			resetX = that.x >= 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x,
			resetY = that.y >= 0 || that.maxScrollY > 0 ? 0 : that.y < that.maxScrollY ? that.maxScrollY : that.y;

		if (resetX == that.x && resetY == that.y) {
			if (that.moved) {
				if (that.options.onScrollEnd) that.options.onScrollEnd.call(that);		
				that.moved = false;
			}

			return;
		}

		that.scrollTo(resetX, resetY, time || 0);
	},
	
	_mouseout: function (e) {
		var t = e.relatedTarget;

		if (!t) {
			this._end(e);
			return;
		}

		while (t = t.parentNode) if (t == this.wrapper) return;
		
		this._end(e);
	},

	_transitionEnd: function (e) {
		var that = this;

		if (e.target != that.scroller) return;

		that._unbind('webkitTransitionEnd');
		
		that._startAni();
	},

	/**
	 *
	 * Utilities
	 *
	 */
	_startAni: function () {
		var that = this,
			startX = that.x, startY = that.y,
			startTime = Date.now(),
			step, easeOut,
			animate;

		if (that.animating) return;

		if (!that.steps.length) {
			that._resetPos(400);
			return;
		}

		step = that.steps.shift();

		if (step.x == startX && step.y == startY) step.time = 0;

		that.animating = true;
		that.moved = true;

		if (that.options.useTransition) {
			that._transitionTime(step.time);
			that._pos(step.x, step.y);
			that.animating = false;
			if (step.time) that._bind('webkitTransitionEnd');
			else that._resetPos(0);
			return;
		}
		
		animate = function () {
			var now = Date.now(),
				newX, newY;

			if (now >= startTime + step.time) {
				that._pos(step.x, step.y);
				that.animating = false;
				if (that.options.onAnimationEnd) that.options.onAnimationEnd.call(that);			
				that._startAni();
				return;
			}

			now = (now - startTime) / step.time - 1;
			easeOut = m.sqrt(1 - now * now);
			newX = (step.x - startX) * easeOut + startX;
			newY = (step.y - startY) * easeOut + startY;
			that._pos(newX, newY);
			if (that.animating) that.aniTime = nextFrame(animate);
		};
		
		animate();
	},

	_transitionTime: function (time) {
		this.scroller.style[vendor + 'TransitionDuration'] = time + 'ms';
	},
	
	_momentum: function (dist, time, maxDistUpper, maxDistLower, size) {
		var deceleration = 0.0006,
			speed = m.abs(dist) / time,
			newDist = (speed * speed) / (2 * deceleration),
			newTime = 0, outsideDist = 0;

		
		if (dist > 0 && newDist > maxDistUpper) {
			outsideDist = size / (6 / (newDist / speed * deceleration));
			maxDistUpper = maxDistUpper + outsideDist;
			speed = speed * maxDistUpper / newDist;
			newDist = maxDistUpper;
		} else if (dist < 0 && newDist > maxDistLower) {
			outsideDist = size / (6 / (newDist / speed * deceleration));
			maxDistLower = maxDistLower + outsideDist;
			speed = speed * maxDistLower / newDist;
			newDist = maxDistLower;
		}

		newDist = newDist * (dist < 0 ? -1 : 1);
		newTime = speed / deceleration;

		return { dist: newDist, time: mround(newTime) };
	},

	_offset: function (el) {
		var left = -el.offsetLeft,
			top = -el.offsetTop;
			
		while (el = el.offsetParent) {
			left -= el.offsetLeft;
			top -= el.offsetTop;
		} 

		return { left: left, top: top };
	},

	_bind: function (type, el, bubble) {
		(el || this.scroller).addEventListener(type, this, !!bubble);
	},

	_unbind: function (type, el, bubble) {
		(el || this.scroller).removeEventListener(type, this, !!bubble);
	},


	/**
	 *
	 * Public methods
	 *
	 */
	destroy: function () {
		var that = this;

		that.scroller.style[vendor + 'Transform'] = '';

		
		that._unbind(RESIZE_EV, window);
		that._unbind(START_EV);
		that._unbind(MOVE_EV);
		that._unbind(END_EV);
		that._unbind(CANCEL_EV);
		that._unbind('mouseout', that.wrapper);
		if (that.options.useTransition) that._unbind('webkitTransitionEnd');
		
		if (that.options.onDestroy) that.options.onDestroy.call(that);
	},

	refresh: function () {
		var that = this,
			offset;

		that.wrapperW = that.wrapper.clientWidth;
		that.wrapperH = that.wrapper.clientHeight;

		that.scrollerW = that.scroller.offsetWidth;
		that.scrollerH = that.scroller.offsetHeight;
		that.maxScrollX = that.wrapperW - that.scrollerW;
		that.maxScrollY = that.wrapperH - that.scrollerH;
		that.dirX = 0;
		that.dirY = 0;

		that.hScroll = that.options.hScroll && that.maxScrollX < 0;
		that.vScroll = that.options.vScroll && (!that.options.bounceLock && !that.hScroll || that.scrollerH > that.wrapperH);

		offset = that._offset(that.wrapper);
		that.wrapperOffsetLeft = -offset.left;
		that.wrapperOffsetTop = -offset.top;


		that.scroller.style[vendor + 'TransitionDuration'] = '0';

		that._resetPos(200);
	},

	scrollTo: function (x, y, time, relative) {
		var that = this,
			step = x,
			i, l;

		that.stop();

		if (!step.length) step = [{ x: x, y: y, time: time, relative: relative }];
		
		for (i=0, l=step.length; i<l; i++) {
			if (step[i].relative) { step[i].x = that.x - step[i].x; step[i].y = that.y - step[i].y; }
			that.steps.push({ x: step[i].x, y: step[i].y, time: step[i].time || 0 });
		}

		that._startAni();
	},

	scrollToElement: function (el, time) {
		var that = this, pos;
		el = el.nodeType ? el : that.scroller.querySelector(el);
		if (!el) return;

		pos = that._offset(el);
		pos.left += that.wrapperOffsetLeft;
		pos.top += that.wrapperOffsetTop;

		pos.left = pos.left > 0 ? 0 : pos.left < that.maxScrollX ? that.maxScrollX : pos.left;
		pos.top = pos.top > 0 ? 0 : pos.top < that.maxScrollY ? that.maxScrollY : pos.top;
		time = time === undefined ? m.max(m.abs(pos.left)*2, m.abs(pos.top)*2) : time;

		that.scrollTo(pos.left, pos.top, time);
	},

	disable: function () {
		this.stop();
		this._resetPos(0);
		this.enabled = false;

		
		this._unbind(MOVE_EV);
		this._unbind(END_EV);
		this._unbind(CANCEL_EV);
	},
	
	enable: function () {
		this.enabled = true;
	},
	
	stop: function () {
		cancelFrame(this.aniTime);
		this.steps = [];
		this.moved = false;
		this.animating = false;
	}
};

if (typeof exports !== 'undefined') exports.iScroll = iScroll;
else window.iScroll = iScroll;

})();

function ScrollPic(scrollContId,arrLeftId,arrRightId,dotListId,listType){
  
  this.scrollContId = scrollContId; 
  this.arrLeftId = arrLeftId; 
  this.arrRightId = arrRightId; 
  this.dotListId = dotListId; 
  this.listType = listType; 

  this.dotClassName   = "dotItem";
  this.dotOnClassName   = "dotItemOn";
  this.dotObjArr = [];
  this.listEvent = "onclick";
  this.circularly = true; 
  
  this.pageWidth = 0; 
  this.frameWidth = 0; 
  this.speed = 10; 
  this.space = 10; 
  this.scrollWidth = 5; 
  
  this.upright = false; 
  
  this.pageIndex = 0;
  
  this.autoPlay = true;
  this.autoPlayTime = 2; 
  
  this._autoTimeObj;
  this._scrollTimeObj;
  this._state = "ready"; 
  
  
  this.stripDiv = document.createElement("DIV");
  
  
  this.lDiv01 = document.createElement("DIV");
  this.lDiv02 = document.createElement("DIV");
};
ScrollPic.prototype = {
  version : "1.41",
  author : "mengjia",
  pageLength : 0,
  touch : true,
  initialize : function(){ 
    var thisTemp = this;
    if(!this.scrollContId){
      throw new Error("必须指定scrollContId.");
      return;
    };
    this.scDiv = this.$(this.scrollContId);
    if(!this.scDiv){
      throw new Error("scrollContId不是正确的对象.(scrollContId = \""+ this.scrollContId +"\")");
      return;
    };
    
    this.scDiv.style[this.upright?'height':'width'] = this.frameWidth + "px";
    this.scDiv.style.overflow = "hidden";
    
    
    this.lDiv01.innerHTML = this.scDiv.innerHTML;
    this.scDiv.innerHTML = "";
    this.scDiv.appendChild(this.stripDiv);
    this.stripDiv.appendChild(this.lDiv01);
    if(this.circularly){
      this.stripDiv.appendChild(this.lDiv02);
      this.lDiv02.innerHTML = this.lDiv01.innerHTML
    };
    
    
    this.stripDiv.style.overflow = "hidden";
    this.stripDiv.style.zoom = "1";
    this.stripDiv.style[this.upright?'height':'width'] = "32766px";
    
    if(!this.upright){  
      this.lDiv01.style.cssFloat = "left";
      this.lDiv01.style.styleFloat = "left";
      this.lDiv01.style.overflow = "hidden";
    };
    this.lDiv01.style.zoom = "1";
    if(this.circularly && !this.upright){ 
      this.lDiv02.style.cssFloat = "left";
      this.lDiv02.style.styleFloat = "left";
      this.lDiv02.style.overflow = "hidden";
    };
    this.lDiv02.style.zoom = "1";
    
    this.addEvent(this.scDiv,"mouseover",function(){thisTemp.stop()});
    this.addEvent(this.scDiv,"mouseout",function(){thisTemp.play()});
    
    
    
    if(this.arrLeftId){
      this.alObj = this.$(this.arrLeftId);
      if(this.alObj){
        this.addEvent(this.alObj,"mousedown",function(){thisTemp.rightMouseDown()});
        this.addEvent(this.alObj,"mouseup",function(){thisTemp.rightEnd()});
        this.addEvent(this.alObj,"mouseout",function(){thisTemp.rightEnd()});
      };
    };
    
    if(this.arrRightId){
      this.arObj = this.$(this.arrRightId);
      if(this.arObj){
        this.addEvent(this.arObj,"mousedown",function(){thisTemp.leftMouseDown()});
        this.addEvent(this.arObj,"mouseup",function(){thisTemp.leftEnd()});
        this.addEvent(this.arObj,"mouseout",function(){thisTemp.leftEnd()});
      };
    };
    
    var pages = Math.ceil(this.lDiv01[this.upright?'offsetHeight':'offsetWidth'] / this.frameWidth),i,tempObj;
    this.pageLength = pages;
    
    if(this.dotListId){
      this.dotListObj = this.$(this.dotListId);
      this.dotListObj.innerHTML = "";
      if(this.dotListObj){
        
        for(i=0;i<pages;i++){
          tempObj = document.createElement("span");
          this.dotListObj.appendChild(tempObj);
          this.dotObjArr.push(tempObj);
          
          if(i==this.pageIndex){
            tempObj.className = this.dotOnClassName;
          }else{
            tempObj.className = this.dotClassName;
          };
          if(this.listType == 'number'){
            tempObj.innerHTML = i+1;
          }else if(typeof (this.listType) =='string' ){
            tempObj.innerHTML = this.listType;
          }else {
            tempObj.innerHTML='';
          };
          tempObj.title = "第" + (i+1) + "页";
          tempObj.num = i;
          tempObj[this.listEvent] = function(){thisTemp.pageTo(this.num)};
        };
      };
    };
    this.scDiv[this.upright?'scrollTop':'scrollLeft'] = 0;
    
    if(this.autoPlay){this.play()};
    
    this._scroll = this.upright?'scrollTop':'scrollLeft';
    this._sWidth = this.upright?'scrollHeight':'scrollWidth';
    
    if(typeof(this.onpagechange) === 'function'){
      this.onpagechange();
    };
    
    this.iPad();
  },
  leftMouseDown : function(){
    if(this._state != "ready"){return};
    var thisTemp = this;
    this._state = "floating";
    clearInterval(this._scrollTimeObj);
    this.moveLeft();
    this._scrollTimeObj = setInterval(function(){thisTemp.moveLeft()},this.speed);
  },
  rightMouseDown : function(){
    if(this._state != "ready"){return};
    var thisTemp = this;
    this._state = "floating";
    clearInterval(this._scrollTimeObj);
    this.moveRight();
    this._scrollTimeObj = setInterval(function(){thisTemp.moveRight()},this.speed);
  },
  moveLeft : function(){
    if(this.circularly){ 
      if(this.scDiv[this._scroll] + this.space >= this.lDiv01[this._sWidth]){
        this.scDiv[this._scroll] = this.scDiv[this._scroll] + this.space - this.lDiv01[this._sWidth];
      }else{
        this.scDiv[this._scroll] += this.space;
      };
    }else{
      if(this.scDiv[this._scroll] + this.space >= this.lDiv01[this._sWidth] - this.frameWidth){
        this.scDiv[this._scroll] = this.lDiv01[this._sWidth] - this.frameWidth;
        
        this.leftEnd();
      }else{
        this.scDiv[this._scroll] += this.space;
      };
    };
    this.accountPageIndex();
  },
  moveRight : function(){
    if(this.circularly){ 
      if(this.scDiv[this._scroll] - this.space <= 0){
        
        this.scDiv[this._scroll] = this.lDiv01[this._sWidth] + this.scDiv[this._scroll] - this.space;
      }else{
        this.scDiv[this._scroll] -= this.space;
      };
    }else{
      if(this.scDiv[this._scroll] - this.space <= 0){
        this.scDiv[this._scroll] = 0;
        
        this.rightEnd();
      }else{
        this.scDiv[this._scroll] -= this.space;
      };
    };
    this.accountPageIndex();
  },
  leftEnd : function(){
    if(this._state != "floating" && this._state != 'touch'){return};
    this._state = "stoping";
    clearInterval(this._scrollTimeObj);
    
    var fill = this.pageWidth - this.scDiv[this._scroll] % this.pageWidth;
    this.move(fill);
  },
  rightEnd : function(){
    if(this._state != "floating" && this._state != 'touch'){return};
    this._state = "stoping";
    clearInterval(this._scrollTimeObj);
    
    var fill = - this.scDiv[this._scroll] % this.pageWidth;
    
    this.move(fill);
  },
  move : function(num,quick){
    var thisTemp = this;
    var thisMove = num/5;
    var theEnd = false;
    if(!quick){
      if(thisMove > this.space){thisMove = this.space};
      if(thisMove < -this.space){thisMove = -this.space};
    };
    
    if(Math.abs(thisMove)<1 && thisMove!=0){
      thisMove = thisMove>=0?1:-1;
    }else{
      thisMove = Math.round(thisMove);
    };
    
    var temp = this.scDiv[this._scroll] + thisMove;
    
    if(thisMove>0){
      if(this.circularly){ 
        if(this.scDiv[this._scroll] + thisMove >= this.lDiv01[this._sWidth]){
          this.scDiv[this._scroll] = this.scDiv[this._scroll] + thisMove - this.lDiv01[this._sWidth];
        }else{
          this.scDiv[this._scroll] += thisMove;
        };
      }else{
        if(this.scDiv[this._scroll] + thisMove >= this.lDiv01[this._sWidth] - this.frameWidth){
          this.scDiv[this._scroll] = this.lDiv01[this._sWidth] - this.frameWidth;
          this._state = "ready";
          theEnd = true;
          
        }else{
          this.scDiv[this._scroll] += thisMove;
        };
      };
    }else{
      if(this.circularly){ 
        if(this.scDiv[this._scroll] + thisMove < 0){
          this.scDiv[this._scroll] = this.lDiv01[this._sWidth] + this.scDiv[this._scroll] + thisMove;
        }else{
          this.scDiv[this._scroll] += thisMove;
        };
      }else{
        if(this.scDiv[this._scroll] - thisMove < 0){
          this.scDiv[this._scroll] = 0;
          this._state = "ready";
          theEnd = true;
          
        }else{
          this.scDiv[this._scroll] += thisMove;
        };
      };
    };
    
    if(typeof(this.onpagechange) === 'function'){
      this.onpagechange();
    };
    
    if(theEnd){
      return;
    };
    
    num -= thisMove;
    if(Math.abs(num) == 0){
      this._state = "ready";
      if(this.autoPlay){this.play()};
      this.accountPageIndex();
      return;
    }else{
      this.accountPageIndex();
      this._scrollTimeObj = setTimeout(function(){thisTemp.move(num,quick)},this.speed)
    };
    
  },
  pre : function(){
    if(this._state != "ready"){return};
    this._state = "stoping";
    this.pageTo(this.pageIndex - 1);
  },
  next : function(reStar){
    if(this._state != "ready"){return};
    this._state = "stoping";
    if(this.circularly){
      this.pageTo(this.pageIndex + 1);
    }else{
      if(this.scDiv[this._scroll] >= this.lDiv01[this._sWidth] - this.frameWidth){
        this._state = "ready";
        if(reStar){this.pageTo(0)};
      }else{
        this.pageTo(this.pageIndex + 1);
      };
    };
  },
  play : function(){
    var thisTemp = this;
    if(!this.autoPlay){return};
    clearInterval(this._autoTimeObj);
    this._autoTimeObj = setInterval(function(){thisTemp.next(true)},this.autoPlayTime * 1000);
  },
  stop : function(){
    clearInterval(this._autoTimeObj);
  },
  pageTo : function(num){
    if(this.pageIndex == num){return};
    if(num < 0){num = this.pageLength - 1};
    clearTimeout(this._scrollTimeObj);
    this._state = "stoping";
    var fill = num * this.frameWidth - this.scDiv[this._scroll];
    this.move(fill,true);
  },
  accountPageIndex : function(){
    var pageIndex = Math.floor(this.scDiv[this._scroll] / this.frameWidth);
    if(pageIndex == this.pageIndex){return};
    this.pageIndex = pageIndex;
    
    if(this.pageIndex > Math.floor(this.lDiv01[this.upright?'offsetHeight':'offsetWidth'] / this.frameWidth )){this.pageIndex = 0};
	if(typeof(this.onnowpage) === 'function'){
		this.onnowpage(this.pageIndex);
	}
    var i;
	
    for(i=0;i<this.dotObjArr.length;i++){
      if(i==this.pageIndex){
		  
        this.dotObjArr[i].className = this.dotOnClassName;
      }else{
        this.dotObjArr[i].className = this.dotClassName;
      };
    };

    if(typeof(this.onpagechange) === 'function'){
      this.onpagechange();
    };
  },
  
  iPadX : 0,
  iPadLastX : 0,
  iPadStatus : 'ok',
  iPad : function(){
    if(typeof(window.ontouchstart) === 'undefined'){ 
      return;  
    };
    if(!this.touch){return};
    
    var tempThis = this;
    this.addEvent(this.scDiv,'touchstart',function(e){tempThis._touchstart(e)});
    this.addEvent(this.scDiv,'touchmove',function(e){tempThis._touchmove(e)});
    this.addEvent(this.scDiv,'touchend',function(e){tempThis._touchend(e)});
  },
  _touchstart : function(e){
    
    
    this.stop();
    this.iPadX = e.touches[0].pageX;
    this.iPadScrollX = window.pageXOffset;
    this.iPadScrollY = window.pageYOffset; 
    this.scDivScrollLeft = this.scDiv[this._scroll];
  },
  _touchmove : function(e){
    if(e.touches.length > 1){ 
      this.iPadStatus = 'ok';
      return;
    };
    this.iPadLastX = e.touches[0].pageX;
    var cX = this.iPadX - this.iPadLastX;
    if(this.iPadStatus == 'ok'){
      if(this.iPadScrollY == window.pageYOffset && this.iPadScrollX == window.pageXOffset && Math.abs(cX)>this.scrollWidth){ 
        this.iPadStatus = 'touch';
      }else{
        return;
      };
    };
    this._state = 'touch';
    var scrollNum = this.scDivScrollLeft + cX;
    
    if(scrollNum >= this.lDiv01[this._sWidth]){
      scrollNum = scrollNum - this.lDiv01[this._sWidth];
    };
    if(scrollNum < 0){
      scrollNum = scrollNum + this.lDiv01[this._sWidth];
    };
    this.scDiv[this._scroll] = scrollNum;
    e.preventDefault();
  },
  _touchend : function(e){
    if(this.iPadStatus != 'touch'){return};
    this.iPadStatus = 'ok';
    
    var cX = this.iPadX - this.iPadLastX;
    if(cX<0){
      this.rightEnd();
    }else{
      this.leftEnd();
    };
    this.play();
  },
  $ : function(objName){if(document.getElementById){return eval('document.getElementById("'+objName+'")')}else{return eval('document.all.'+objName)}},
  isIE : navigator.appVersion.indexOf("MSIE")!=-1?true:false,
  
  
  addEvent : function(obj,eventType,func){if(obj.attachEvent){obj.attachEvent("on" + eventType,func);}else{obj.addEventListener(eventType,func,false)}},
  delEvent : function(obj,eventType,func){
    if(obj.detachEvent){obj.detachEvent("on" + eventType,func)}else{obj.removeEventListener(eventType,func,false)}
  },
  
  readCookie : function(l){var i="",I=l+"=";if(document.cookie.length>0){var offset=document.cookie.indexOf(I);if(offset!=-1){offset+=I.length;var end=document.cookie.indexOf(";",offset);if(end==-1)end=document.cookie.length;i=unescape(document.cookie.substring(offset,end))}};return i},
  
  writeCookie : function(O,o,l,I){var i="",c="";if(l!=null){i=new Date((new Date).getTime()+l*3600000);i="; expires="+i.toGMTString()};if(I!=null){c=";domain="+I};document.cookie=O+"="+escape(o)+i+c},
  
  readStyle:function(i,I){if(i.style[I]){return i.style[I]}else if(i.currentStyle){return i.currentStyle[I]}else if(document.defaultView&&document.defaultView.getComputedStyle){var l=document.defaultView.getComputedStyle(i,null);return l.getPropertyValue(I)}else{return null}}
};
