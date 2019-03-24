//=require simpletoast.js

/**
 * @name Cookieconsent
 * @description Call cookieconsent panel
 */
window.addEventListener('load', function () {
	window.cookieconsent.initialise({
		'palette': {
			'popup': {
				'background': '#000'
			},
			'button': {
				'background': '#328fea'
			}
		},
		'theme': 'classic',
		'position': 'bottom-left',
		'content': {
			'href': webRootJs + 'pages/legals'
		}
	});
});

/**
 * MAIN MENU transition effect
 */
$(window).scroll(function () {
	var scrollTop = $(window).scrollTop();
	if (scrollTop < 50) {
		$('.heavy-nav').stop().removeClass('nav-color').addClass('nav-transparent');
	} else {
		$('.heavy-nav').stop().removeClass('nav-transparent').addClass('nav-color');
	}
});

/**
 * This function is for css initialization
 */
$(function () {
	var scrollTop = $(window).scrollTop();
	if (scrollTop < 50) {
		$('.heavy-nav').addClass('nav-transparent');
	} else {
		$('.heavy-nav').addClass('nav-color');
	}
});

/**
 * @name inIframe
 * @description Dectects if the current window is running under an iframe
 * @type {HELPER}
 */
function inIframe() {
	try {
		return window.self !== window.top;
	} catch (e) {
		return true;
	}
}

/**
 * This function will detect if a add_setup_modal anchor is used in the URL
 */
$(function () {
	if (window.location.hash && window.location.hash.substring(1) == 'add_setup_modal') {
		eventFire(document.getElementById('menu_trigger_add_modal'), 'click');
	}
});

/**
 * @name eventFire
 * @description Dectects if the current window is running under an iframe
 * @param {DOM element} [el] [Define which element will receive the action]
 * @param {event} [etype] [Type of event to use on the element]
 * @type {HELPER}
 */
function eventFire(el, etype) {
	if (el.fireEvent) {
		el.fireEvent('on' + etype);
	} else {
		var evObj = document.createEvent('Events');
		evObj.initEvent(etype, true, false);
		el.dispatchEvent(evObj);
	}
}

/**
 * @description Slick slider on post page
 */
$('.post_slider').slick({
	centerMode: false,
	autoplay: false,
	adaptiveHeight: true,
	lazyLoad: 'progressive',
	arrows: true,
	infinite: false,
	slidesToShow: 1,
	responsive: [{
		breakpoint: 768,
		settings: {
			arrows: false,
			slidesToShow: 1,
		}
	}, {
		breakpoint: 480,
		settings: {
			arrows: false,
			slidesToShow: 1,
		}
	}]
});