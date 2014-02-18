if(document.readyState !== "complete") {  
	document.addEventListener("DOMContentLoaded", function () {  				
		if(topNavAnchors = document.querySelectorAll("#primary .nav > li > a")) {
            var subMenus = document.querySelectorAll("#primary .nav > li > ul");

            document.body.onclick = function() {
                for (var s = 0; s < subMenus.length; s++) {
                    if (subMenus[s].classList.contains("visible")) {
                      subMenus[s].classList.remove("visible");
                    }
                }
            };
			
            var topNavLi = document.querySelectorAll("#primary .nav > li ");

			var j = 0;	
			
			for (var i = 0; i < topNavAnchors.length; i++) {   			
				if(topNavLi[i].getElementsByTagName("ul").length != 0) {
					j++;
					topNavAnchors[i].onclick = function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                        for (var s = 0; s < subMenus.length; s++) {
                          if (subMenus[s] !== this.nextElementSibling && subMenus[s].classList.contains("visible")) {
                            subMenus[s].classList.remove("visible");
                          }
                        }
                        if (!this.nextElementSibling.classList.contains("visible")) {
                          this.nextElementSibling.classList.add("visible");
                        }
					}; 			
				}
			}
		}
		
		if(languageForm = document.forms["language"]) {
			var languageSelect = languageForm.getElementsByTagName("select");		
			
			languageSelect[0].onchange= function() {
				languageForm.submit();
			};
		}
		
		if(currencyForm = document.forms["currency"]) {
			var currencySelect = currencyForm.getElementsByTagName("select");				
			
			currencySelect[0].onchange= function() {
				currencyForm.submit();
			};		
		}
		
		if(!supportsSVG()) {			
			document.getElementsByTagName("body")[0].className += " no-svg";			
		}
	}, false);

	$("#description h2").bind("click",function(){
		$("#description .panel-content").toggle();

		if ($("#description").hasClass("opened")) {
            $("#description").removeClass("opened");
        } else {
            $("#description").addClass("opened");
        }
	});

	$("#attribute h2").bind("click",function(){
		$("#attribute .panel-content").toggle();

		if ($("#attribute").hasClass("opened")) {
            $("#attribute").removeClass("opened");
        } else {
            $("#attribute").addClass("opened");
        }
	});

	$("#reviews h2").bind("click",function(){
		$("#reviews .panel-content").toggle();

		if ($("#reviews").hasClass("opened")) {
            $("#reviews").removeClass("opened");
        } else {
            $("#reviews").addClass("opened");
        }
	});

	$("#related h2").bind("click",function(){
		$("#related .panel-content").toggle();

		if ($("#related").hasClass("opened")) {
            $("#related").removeClass("opened");
        } else {
            $("#related").addClass("opened");
        }
	});

	$("#description .panel-content a").bind("click",function(){
		$("#description .panel-content").hide();
		$("#description").removeClass("opened");
	});

	$("#attribute .panel-content a").bind("click",function(){
		$("#attribute .panel-content").hide();
		$("#attribute").removeClass("opened");
	});

	$("#reviews .panel-content a").bind("click",function(){
		$("#reviews .panel-content").hide();
		$("#reviews").removeClass("opened");
	});

	$("#related .panel-content a").bind("click",function(){
		$("#related .panel-content").hide();
		$("#related").removeClass("opened");
	});
}

function supportsSVG() {
    return !!document.createElementNS && !!document.createElementNS('http://www.w3.org/2000/svg', "svg").createSVGRect;
}

function focusFirstError(form) {
	document.getElementById($(form.find('.s-error').get(0)).parent().find('input, select').get(0).id).focus();
}

// jQuery Mobile compatibility  plugins
//.submit()
(function($){
    $.fn.submit=function(){
		this.get(0).submit();    	
        return this;
    };
})(jq);

//.before()
(function($){
    $.fn.before = function(opts){
    	for (var i = 0; i < this.length; i++) {
    		$(opts).insertBefore(this[i]);
        }
    	
        return this;
    };
})(jq);

//.after()
(function($){
    $.fn.after = function(opts){
    	for (var i = 0; i < this.length; i++) {
    		$(opts).insertAfter(this[i]);
        }
    	
        return this;
    };
})(jq);

//attr override
(function($){
    $.fn.original_attr = $.fn.attr;

    $.fn.attr = function(attr, value){        
        for (var i = 0; i < this.length; i++) {
            if(value === false) {
                    return $(this).removeAttr(attr);   
            } else {
            	if (value === undefined) {
                    return $(this).original_attr(attr);
                } else {
                    return $(this).original_attr(attr,value);
                }
            }
        }
        //return this;
    };
})(jq);

//.click()
(function($){
    $.fn.click = function(handler){
		$(this).bind("click", handler);
		return this;
    };
})(jq);

/* ----------------------------------
 * SLIDER v1.0.0
 * Licensed under The MIT License
 * Adapted from Brad Birdsall's swipe
 * http://opensource.org/licenses/MIT
 * ---------------------------------- */

!function () {

  var pageX;
  var pageY;
  var slider;
  var deltaX;
  var deltaY;
  var offsetX;
  var lastSlide;
  var startTime;
  var resistance;
  var sliderWidth;
  var slideNumber;
  var isScrolling;
  var scrollableArea;

  var getSlider = function (target) {
    var i, sliders = document.querySelectorAll('.slider ul');
    for (; target && target !== document; target = target.parentNode) {
      for (i = sliders.length; i--;) { if (sliders[i] === target) return target; }
    }
  }

  var getScroll = function () {
    var translate3d = slider.style.webkitTransform.match(/translate3d\(([^,]*)/);
    return parseInt(translate3d ? translate3d[1] : 0)
  };

  var setSlideNumber = function (offset) {
    var round = offset ? (deltaX < 0 ? 'ceil' : 'floor') : 'round';
    slideNumber = Math[round](getScroll() / ( scrollableArea / slider.children.length) );
    slideNumber += offset;
    slideNumber = Math.min(slideNumber, 0);
    slideNumber = Math.max(-(slider.children.length - 1), slideNumber);
  }

  var onTouchStart = function (e) {
    slider = getSlider(e.target);

    if (!slider) return;

    var firstItem  = slider.querySelector('li');

    scrollableArea = firstItem.offsetWidth * slider.children.length;
    isScrolling    = undefined;
    sliderWidth    = slider.offsetWidth;
    resistance     = 1;
    lastSlide      = -(slider.children.length - 1);
    startTime      = +new Date;
    pageX          = e.touches[0].pageX;
    pageY          = e.touches[0].pageY;

    setSlideNumber(0);

    slider.style['-webkit-transition-duration'] = 0;
  };

  var onTouchMove = function (e) {
    if (e.touches.length > 1 || !slider) return; // Exit if a pinch || no slider

    deltaX = e.touches[0].pageX - pageX;
    deltaY = e.touches[0].pageY - pageY;
    pageX  = e.touches[0].pageX;
    pageY  = e.touches[0].pageY;

    if (typeof isScrolling == 'undefined') {
      isScrolling = Math.abs(deltaY) > Math.abs(deltaX);
    }

    if (isScrolling) return;

    offsetX = (deltaX / resistance) + getScroll();

    e.preventDefault();

    resistance = slideNumber == 0         && deltaX > 0 ? (pageX / sliderWidth) + 1.25 :
                 slideNumber == lastSlide && deltaX < 0 ? (Math.abs(pageX) / sliderWidth) + 1.25 : 1;

    slider.style.webkitTransform = 'translate3d(' + offsetX + 'px,0,0)';
  };

  var onTouchEnd = function (e) {
    if (!slider || isScrolling) return;

    setSlideNumber(
      (+new Date) - startTime < 1000 && Math.abs(deltaX) > 15 ? (deltaX < 0 ? -1 : 1) : 0
    );

    offsetX = slideNumber * sliderWidth;

    slider.style['-webkit-transition-duration'] = '.2s';
    slider.style.webkitTransform = 'translate3d(' + offsetX + 'px,0,0)';

    e = new CustomEvent('slide', {
      detail: { slideNumber: Math.abs(slideNumber) },
      bubbles: true,
      cancelable: true
    });

    slider.parentNode.dispatchEvent(e);
  };

  window.addEventListener('touchstart', onTouchStart);
  window.addEventListener('touchmove', onTouchMove);
  window.addEventListener('touchend', onTouchEnd);

}();
