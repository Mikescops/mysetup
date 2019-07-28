//=require simpletoast.js

/**
 * @name Cookieconsent
 * @description Call cookieconsent panel
 */
window.addEventListener('load', () => {
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
$(window).scroll(() => {
	let scrollTop = $(window).scrollTop();
	if (scrollTop < 50) {
		$('.heavy-nav').stop().removeClass('nav-color').addClass('nav-transparent');
	} else {
		$('.heavy-nav').stop().removeClass('nav-transparent').addClass('nav-color');
	}
});

/**
 * This function is for css initialization
 */

let scrollTop = $(window).scrollTop();
if (scrollTop < 50) {
	$('.heavy-nav').addClass('nav-transparent');
} else {
	$('.heavy-nav').addClass('nav-color');
}

const revealElement = (element) => {
	element.addClass('reveal');
};

const unRevealElement = (element) => {
	element.removeClass('reveal');
};

/**
 * @name inIframe
 * @description Dectects if the current window is running under an iframe
 * @type {HELPER}
 */
const inIframe = () => {
	try {
		return window.self !== window.top;
	} catch (e) {
		return true;
	}
};

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

/**
 * @description Login/Register tab system
 */
$(function () {
	// constants
	var SHOW_CLASS = 'show',
		HIDE_CLASS = 'hide',
		ACTIVE_CLASS = 'active';

	$('.tabs').on('click', 'li a', function (e) {
		e.preventDefault();
		var $tab = $(this),
			href = $tab.attr('href');

		$('.active').removeClass(ACTIVE_CLASS);
		$tab.addClass(ACTIVE_CLASS);

		$('.show')
			.removeClass(SHOW_CLASS)
			.addClass(HIDE_CLASS)
			.hide();

		$(href)
			.removeClass(HIDE_CLASS)
			.addClass(SHOW_CLASS)
			.hide()
			.fadeIn(550);
	});
	$('.form-action').on('click', '.next', function (e) {
		e.preventDefault();
		var $next = $(this),
			href = $next.attr('href'),
			$tab = $(href + '-tab');

		$('.active').removeClass(ACTIVE_CLASS);
		$tab.addClass(ACTIVE_CLASS);

		$('.show')
			.removeClass(SHOW_CLASS)
			.addClass(HIDE_CLASS)
			.hide();

		$(href)
			.removeClass(HIDE_CLASS)
			.addClass(SHOW_CLASS)
			.hide()
			.fadeIn(550);
	});
});
