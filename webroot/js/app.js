/*!
 * mySetup v3.1.0
 * https://mysetup.co
 *
 * Copyright (c) 2018 Corentin Mors / Samuel Forestier
 * All rights reserved
 */

/*jshint esversion: 6 */

// @koala-prepend "jquery-3.2.0.min.js"

/*** Ex lib.min.js ***/
// @koala-prepend "amazon-autocomplete.js"
// @koala-prepend "jssocials.min.js"
// @koala-prepend "lity.min.js"
// @koala-prepend "slick.min.js"

// @koala-prepend "tippy.min.js"
// @koala-prepend "emojione.min.js"
// @koala-prepend "cookieconsent.min.js"


/**
 * @name Cookieconsent
 * @description Search Products over API
 */
window.addEventListener("load", function() {
	window.cookieconsent.initialise({
		"palette": {
			"popup": {
				"background": "#000"
			},
			"button": {
				"background": "#328fea"
			}
		},
		"theme": "classic",
		"position": "bottom-left",
		"content": {
			"href": webRootJs + "pages/legals"
		}
	});
});

/**
 * MAIN MENU transition effect
 */
$(window).scroll(function() {
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
$(function() {
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
 * ADD SETUP tabs
 */
$(function() {
	// constants
	var SHOW_CLASS = 'show',
		HIDE_CLASS = 'hide',
		ACTIVE_CLASS = 'active';

	$('.tabs').on('click', 'li a', function(e) {
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
	$('.form-action').on('click', '.next', function(e) {
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

/**
 * EDIT SETUP tabs
 */
$(function() {
	// constants
	var SHOW_CLASS = 'show-edit',
		HIDE_CLASS = 'hide-edit',
		ACTIVE_CLASS = 'active-edit';

	$('.tabs-edit').on('click', 'li a', function(e) {
		e.preventDefault();
		var $tab = $(this),
			href = $tab.attr('href');

		$('.active-edit').removeClass(ACTIVE_CLASS);
		$tab.addClass(ACTIVE_CLASS);

		$('.show-edit')
			.removeClass(SHOW_CLASS)
			.addClass(HIDE_CLASS)
			.hide();

		$(href)
			.removeClass(HIDE_CLASS)
			.addClass(SHOW_CLASS)
			.hide()
			.fadeIn(550);
	});
	$('.form-action-edit').on('click', '.next', function(e) {
		e.preventDefault();
		var $next = $(this),
			href = $next.attr('href'),
			$tab = $(href + '-tab');

		$('.active-edit').removeClass(ACTIVE_CLASS);
		$tab.addClass(ACTIVE_CLASS);

		$('.show-edit')
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

/**
 * On load functions
 */
$(function() {
	$('.is_author').click(function() {
		$(this).hide();
		$('.setup_author').show('fast');
		return false;
	});
	$('.reset_pwd').click(function() {
		$(this).hide();
		$('.pwd_field').show('fast');
		return false;
	});
	$("#social-networks").jsSocials({
		shareIn: "popup",
		showCount: false,
		showLabel: false,
		shares: ["twitter", "facebook", "googleplus", "pinterest", "whatsapp"]
	});

	$("#profileImage").click(function(e) {
		$("#profileUpload").click();
	});
	$("#profileUpload").change(function() {
		fasterPreview(this);
	});
	$("#featuredimage").change(function() {
		featuredPreview(this);
	});
	$("#featuredImage_edit").change(function() {
		featuredPreview_edit(this);
	});

	$("#featuredimage_preview").click(function(e) {
		$(".label_fimage").click();
	});

	/***** Preview galery edit *****/
	$("#gallery0image_preview_edit").click(function(e) {
		$("#gallery0").click();
	});
	$("#gallery1image_preview_edit").click(function(e) {
		$("#gallery1").click();
	});
	$("#gallery2image_preview_edit").click(function(e) {
		$("#gallery2").click();
	});
	$("#gallery3image_preview_edit").click(function(e) {
		$("#gallery3").click();
	});
	$("#gallery4image_preview_edit").click(function(e) {
		$("#gallery4").click();
	});

	$("#gallery0").change(function() {
		galleryPreview_edit(this, 0);
	});
	$("#gallery1").change(function() {
		galleryPreview_edit(this, 1);
	});
	$("#gallery2").change(function() {
		galleryPreview_edit(this, 2);
	});
	$("#gallery3").change(function() {
		galleryPreview_edit(this, 3);
	});
	$("#gallery4").change(function() {
		galleryPreview_edit(this, 4);
	});

	/***** Preview galery add *****/
	$("#gallery0image_preview_add").click(function(e) {
		$("#gallery0add").click();
	});
	$("#gallery1image_preview_add").click(function(e) {
		$("#gallery1add").click();
	});
	$("#gallery2image_preview_add").click(function(e) {
		$("#gallery2add").click();
	});
	$("#gallery3image_preview_add").click(function(e) {
		$("#gallery3add").click();
	});
	$("#gallery4image_preview_add").click(function(e) {
		$("#gallery4add").click();
	});

	$("#gallery0add").change(function() {
		galleryPreview_add(this, 0);
	});
	$("#gallery1add").change(function() {
		galleryPreview_add(this, 1);
	});
	$("#gallery2add").change(function() {
		galleryPreview_add(this, 2);
	});
	$("#gallery3add").change(function() {
		galleryPreview_add(this, 3);
	});
	$("#gallery4add").change(function() {
		galleryPreview_add(this, 4);
	});

	$(".edit-comment").click(function(e) {
		var $comment = $(this),
			$place = $comment.attr('source');
		var $oldvalue = $('#' + $place + '> p').attr('content'),
			$id = $place.replace(/[^0-9]/g, '');

		$('.textarea-edit-comment > .emojionearea-editor').html(emojione.toImage($oldvalue));
		$('#edit-comment-hidden > form').attr('action', $('#edit-comment-hidden > form').attr('action') + '/' + $id);
	});
});

/**
 * @name fasterPreview
 * @description Update Profile Picture on Upload
 */
function fasterPreview(uploader) {
	if (uploader.files && uploader.files[0]) {
		$('#profileImage').attr('src',
			window.URL.createObjectURL(uploader.files[0]));
	}
}

/**
 * @name featuredPreview*
 * @description Featured Image preview on modal
 */
function featuredPreview(uploader) {
	if (uploader.files && uploader.files[0]) {
		$('#featuredimage_preview').attr('src',
			window.URL.createObjectURL(uploader.files[0]));
	}
	$(".label_fimage_add").hide();
	$(".gallery-holder.homide").show();
}

function featuredPreview_edit(uploader) {
	if (uploader.files && uploader.files[0]) {
		$('#featuredimage_preview_edit').attr('src',
			window.URL.createObjectURL(uploader.files[0]));
	}
}

/**
 * @name galleryPreview*
 * @description Gallery Image preview on modal
 */
function galleryPreview_edit(uploader, number) {
	if (uploader.files && uploader.files[0]) {
		$('#gallery' + number + 'image_preview_edit').attr('style',
			'background-image:url(' + window.URL.createObjectURL(uploader.files[0]) + ')');
		$('#gallery' + number + 'image_preview_edit .fa-plus').remove();
	}
}

function galleryPreview_add(uploader, number) {
	if (uploader.files && uploader.files[0]) {
		$('#gallery' + number + 'image_preview_add').attr('style',
			'background-image:url(' + window.URL.createObjectURL(uploader.files[0]) + ')');
		$('#gallery' + number + 'image_preview_add .fa-plus').remove();
	}
}

/**
 * @name searchItem
 * @description Search Products over API
 * @param {string} [query] [Keyword to search]
 * @param {string} [action] [Define where the function is called (add or edit)]
 *
 * @const timer
 */
var timer;
function searchItem(query, action) {
	clearTimeout(timer);
	timer = setTimeout(function validate() {
		if (query.length < 2) {
			return;
		}
		$.ajax({
			url: webRootJs + 'thirdParties/searchProducts',
			type: 'get',
			data: {
				"q": query
			},
			success: function(response) {

				$(".search_results." + action).html("");

				var products = response.products;

				if (products[0] == null) {
					$(".search_results." + action).append("No products found...");
				}

				$.each(products, function(key, value) {
					var list = $('<li></li>');
					var img = $('<img>');

					var src = value.src;
					var encodedSrc = encodeURIComponent(src);
					img.attr('src', src);

					var encodedTitle = value.title;
					var title = decodeURIComponent(encodedTitle);
					img.attr('title', title);

					var url = value.href;
					var encodedUrl = encodeURIComponent(url);

					list.html(`<a onclick="addToBasket(\`${encodedTitle}\`, '${encodedUrl}', '${encodedSrc}', '${action}')"><p>${title}</p><i class="fa fa-square-o" aria-hidden="true"></i></a>`);
					list.find('a').prepend(img);
					$(".search_results." + action).append(list);
				});

				var image = $('mediumimage');

			}
		});

	}, 400);
}

/**
 * @name addToBasket
 * @description Add to input selected product from search
 * @param {string} [title] [Title of product]
 * @param {string} [url] [Url of product]
 * @param {string} [src] [Source of image]
 * @param {string} [action] [Define where the function is called (add or edit)]
 */
function addToBasket(title, url, src, action) {

	$('.hiddenInput.' + action).val($('.hiddenInput.' + action).val() + title + ';' + url + ';' + src + ',');

	$(".search_results." + action).html("");
	$(".liveInput." + action).val("");

	decodedTitle = decodeURIComponent(title);
	decodedSrc = decodeURIComponent(src);

	var list = $('<li></li>');
	var img = $('<img>');
	img.attr('src', decodedSrc);
	list.html(`<a onclick="deleteFromBasket(\`${title}\`,this,'${action}')"><p>${decodedTitle}</p><i class="fa fa-check-square-o" aria-hidden="true"></i></a>`);
	list.find('a').prepend(img);
	$(".basket_items." + action).append(list);
}

/**
 * @name deleteFromBasket
 * @description Delete from input selected product
 * @param {string} [title] [Title of product to delete]
 * @param {string} [parent] [DOM element who triggered the function]
 * @param {string} [action] [Define where the function is called (add or edit)]
 */
function deleteFromBasket(title, parent, action) {

	var ResearchArea = $('.hiddenInput.' + action).val();

	var splitTextInput = ResearchArea.split(",");

	new_arr = $.grep(splitTextInput, function(n, i) { // just use arr
		return n.split(";")[0] != title;
	});

	$('.hiddenInput.' + action).val(new_arr);

	parent.closest('li').remove();

}

/**
 * @name likeSetup
 * @description Call controller with AJAX request to add or remove a like on a setup
 * @param {int} [id] [ID of setup]
 */
function likeSetup(id) {

	if ($(".red_button").hasClass("active")) {
		$.ajax({
			url: webRootJs + 'likes/dislike',
			type: 'get',
			data: {
				"setup_id": id
			},
			success: answer_dislike,
			error: answer_error
		});
	} else {
		$.ajax({
			url: webRootJs + 'likes/like',
			type: 'get',
			data: {
				"setup_id": id
			},
			success: answer_like,
			error: answer_error
		});
	}

	function answer_like(response) {
		$(".red_button").addClass("active");
		printLikes(id);
	}

	function answer_dislike(response) {
		$(".red_button").removeClass("active");
		printLikes(id);
	}

	function answer_error(response) {
		console.log(response);
	}
}

/**
 * @name printLikes
 * @description Display number of likes of a setup - call cake controller AJAX request
 * @param {int} [id] [ID of setup]
 */
function printLikes(id) {
	$.ajax({
		url: webRootJs + "likes/getlikes",
		data: {
			setup_id: id
		},
		dataType: 'html',
		type: 'get',
		success: function(json) {
			$(".pointing_label").html(json);
		}
	});
}

/**
 * @name doesLike
 * @description Check if setup is liked by the current setup - call cake controller AJAX request
 * @param {int} [setup] [ID of setup]
 */
function doesLike(setup) {
	$.ajax({
		url: webRootJs + "likes/doesLike",
		data: {
			setup_id: setup
		},
		dataType: 'html',
		type: 'get',
		success: function(json) {
			if (json == 'true')
				$(".red_button").addClass("active");
		}
	});
}

/**
 * @name siiimpleToast
 * @description SiiimpleToast ES6 functions
 */
class siiimpleToast {
	constructor(settings) {
		// default settings
		if (!settings) {
			settings = {
				vertical: 'bottom',
				horizontal: 'right'
			};
		}
		// throw Parameter Error
		if (!settings.vertical) throw new Error('Please set parameter "vertical" ex) bottom, top ');
		if (!settings.horizontal) throw new Error('Please set parameter "horizontal" ex) left, center, right ');
		// data binding
		this._settings = settings;
		// default Class (DOM)
		this.defaultClass = 'siiimpleToast';
		// default Style
		this.defaultStyle = {
			position: 'fixed',
			padding: '1rem 1.2rem',
			minWidth: '17rem',
			maxWidth: '100%',
			marginLeft: '1rem',
			zIndex: '9999',
			borderRadius: '2px',
			color: 'white',
			fontWeight: 300,
			pointerEvents: 'none',
			opacity: 0,
			boxShadow: '0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)',
			transform: 'scale(0.5)',
			transition: 'all 0.4s ease-out'
		};
		// set vertical direction
		this.verticalStyle = this.setVerticalStyle()[this._settings.vertical];
		// set horizontal direction
		this.horizontalStyle = this.setHorizontalStyle()[this._settings.horizontal];
	}
	setVerticalStyle() {
		return {
			top: {
				top: '-100px'
			},
			bottom: {
				bottom: '-100px'
			}
		};
	}
	setHorizontalStyle() {
		return {
			left: {
				left: '1rem'
			},
			center: {
				left: '50%',
				transform: 'translateX(-50%) scale(0.5)'
			},
			right: {
				right: '1rem'
			}
		};
	}
	setMessageStyle() {
		return {
			default: '#323232',
			success: '#005f84',
			alert: '#db2828',
		};
	}
	init(state, message) {
		const root = document.querySelector('body');
		const newToast = document.createElement('div');

		// set Common class
		newToast.className = this.defaultClass;
		// set message
		newToast.innerHTML = message;
		// set style
		Object.assign(
			newToast.style,
			this.defaultStyle,
			this.verticalStyle,
			this.horizontalStyle
		);
		// set Message mode (Color)
		newToast.style.backgroundColor = this.setMessageStyle()[state];
		// insert Toast DOM
		root.insertBefore(newToast, root.firstChild);

		// Actions...
		let time = 0;
		// setTimeout - instead Of jQuery.queue();
		setTimeout(() => {
			this.addAction(newToast);
		}, time += 100);
		setTimeout(() => {
			this.removeAction(newToast);
		}, time += 5000);
		setTimeout(() => {
			this.removeDOM(newToast);
		}, time += 500);
	}
	addAction(obj) {
		// All toast objects
		const toast = document.getElementsByClassName(this.defaultClass);
		let pushStack = 15;

		// *CSS* transform - scale, opacity
		if (this._settings.horizontal == 'center') {
			obj.style.transform = 'translateX(-50%) scale(1)';
		} else {
			obj.style.transform = 'scale(1)';
		}
		obj.style.opacity = 1;

		// push effect (Down or Top)
		for (let i = 0; i < toast.length; i += 1) {
			const height = toast[i].offsetHeight;
			const objMargin = 15; // interval between objects

			// *CSS* bottom, top
			if (this._settings.vertical == 'bottom') {
				toast[i].style.bottom = `${pushStack}px`;
			} else {
				toast[i].style.top = `${pushStack}px`;
			}

			pushStack += height + objMargin;
		}
	}
	removeAction(obj) {
		const width = obj.offsetWidth;
		const objCoordinate = obj.getBoundingClientRect();

		// remove effect
		// *CSS*  direction: right, opacity: 0
		if (this._settings.horizontal == 'right') {
			obj.style.right = `-${width}px`;
		} else {
			obj.style.left = `${objCoordinate.left + width}px`;
		}
		obj.style.opacity = 0;
	}
	removeDOM(obj) {
		const parent = obj.parentNode;
		parent.removeChild(obj);
	}
	message(message) {
		this.init('default', message);
	}
	success(message) {
		this.init('success', message);
	}
	alert(message) {
		this.init('alert', message);
	}
}

/**
 * @name convertToSlug
 * @description Convert string to a slug which can be use as an url
 * @param {string} [str] [String to sluggify]
 */
function convertToSlug(str)
{
	var rExps=[
	{re:/[\xC0-\xC6]/g, ch:'A'},
	{re:/[\xE0-\xE6]/g, ch:'a'},
	{re:/[\xC8-\xCB]/g, ch:'E'},
	{re:/[\xE8-\xEB]/g, ch:'e'},
	{re:/[\xCC-\xCF]/g, ch:'I'},
	{re:/[\xEC-\xEF]/g, ch:'i'},
	{re:/[\xD2-\xD6]/g, ch:'O'},
	{re:/[\xF2-\xF6]/g, ch:'o'},
	{re:/[\xD9-\xDC]/g, ch:'U'},
	{re:/[\xF9-\xFC]/g, ch:'u'},
	{re:/[\xC7-\xE7]/g, ch:'c'},
	{re:/[\xD1]/g, ch:'N'},
	{re:/[\xF1]/g, ch:'n'} ];
	var $slug = '';
	var trimmed = $.trim(str);
	for(var i=0, len=rExps.length; i<len; i++)
		trimmed=trimmed.replace(rExps[i].re, rExps[i].ch);
	$slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
	replace(/-+/g, '-').
	replace(/^-|-$/g, '');
	return $slug;
}

/**
 * @name infiniteScroll
 * @description Infinite Scroll to display setups lists
 * @param {int} [nbtodisplay] [Number of setups to display at each call of the function]
 */
function infiniteScroll(nbtodisplay) {
	var offset = nbtodisplay;
	$(window).data('ajaxready', true);
	// On déclenche une fonction lorsque l'utilisateur utilise sa molette
	$(window).scroll(function() {
		if ($(window).data('ajaxready') == false) return; //permet de couper les trigger parallèles
		if (($(window).scrollTop() + $(window).height()) + 250 > $(document).height()) {
			$(window).data('ajaxready', false);
			$.ajax({
				url: webRootJs + "api/getSetups",
				data: {
					p: offset,
					n: nbtodisplay,
					o: 'DESC'
				},
				dataType: 'html',
				type: 'get',
				success: function(json) {
					setups = $.parseJSON(json);
					if (setups[0]) {
						// Cache of the template
						var template = document.getElementById("template-list-item");
						// Get the contents of the template
						var templateHtml = template.innerHTML;
						// Final HTML variable as empty string
						var listHtml = "";
						// Simple sanitizer for HTML entities
						var escapeHtml = function(text) {
							var map = {
								'&': '&amp;',
								'<': '&lt;',
								'>': '&gt;',
								'"': '&quot;',
								"'": '&#039;'
							};
							return text.replace(/[&<>"']/g, function(match) {
								return map[match];
							});
						};

						$.each(setups, function(key, value) {

							let title = escapeHtml(value.title);
							let url = webRootJs + `setups/${value.id}-${convertToSlug(escapeHtml(value.title))}`;
							// It's possible that some setups have lost their featured image...
							let img_src = webRootJs;
							if (value.resources[0]) {
								img_src += value.resources[0].src;
							}
							else
							{
								img_src += "img/not_found.jpg";
							}
							let likes = value.like_count;
							let user_name = escapeHtml(value.user.name);
							let user_src = webRootJs + `uploads/files/pics/profile_picture_${value.user_id}.png?` + ("0" + (new Date(value.user.modificationDate)).getMinutes()).slice(-2) + ("0" + (new Date(value.user.modificationDate)).getSeconds()).slice(-2);
							let user_url = webRootJs + `users/` + value.user_id;
							let main_color = $.parseJSON(value.main_colors)[0];
							let rgb_1 = main_color[0];
							let rgb_2 = main_color[1];
							let rgb_3 = main_color[2];

							listHtml += templateHtml.replace(/{{ title }}/g, title)
								.replace(/{{ url }}/g, url)
								.replace(/{{ img_src }}/g, img_src)
								.replace(/{{ likes }}/g, likes)
								.replace(/{{ user_name }}/g, user_name)
								.replace(/{{ user_src }}/g, user_src)
								.replace(/{{ user_url }}/g, user_url)
								.replace(/{{ rgb_1 }}/g, rgb_1)
								.replace(/{{ rgb_2 }}/g, rgb_2)
								.replace(/{{ rgb_3 }}/g, rgb_3);
						});

						$('.fullitem_holder').append(listHtml);

						$(window).data('ajaxready', true);
					} else {
						$('.no_more_setups').html("No more setups to display...");
					}
				}
			});
			offset += nbtodisplay;
		}
	});
}

/**
 * @name logTwitch
 * @description Trigger login of Twitch through OAuth2
 * @param {string} [lang] [Language of user]
 */
function logTwitch(lang) {
	var rand_state = Math.random().toString(36).slice(2) + Math.random().toString(36).slice(2);
	window.location = 'https://api.twitch.tv/kraken/oauth2/authorize?client_id='+twitchClientId+'&redirect_uri='+webRootJs+'twitch/&response_type=code&scope=user_read&state='+lang+rand_state;
}

/**
 * @name checknotification
 * @description Notification check and refresh - call cake controller AJAX request
 */
function checknotification() {
	if (inIframe() == false) {
		$.ajax({
			url: webRootJs + "notifications/getNotifications",
			data: {
				n: '8'
			},
			dataType: 'html',
			type: 'get',
			success: function(json) {
				notifs = $.parseJSON(json);
				$('#notif-container').html('');
				if (notifs[0] != null) {
					$.each(notifs, function(key, value) {
						$('#notif-container').append('<div onclick="markasread(' + value.id + ')" class="notif notifnb-' + value.id + '">' + value.content + '<div class="notif-close"><span onclick="markasread(' + value.id + ')">×</span></div></div>');
					});

					$('#notifications-trigger').addClass('notif-trigger');
					$('#no-notif').hide();
					instance.update(notificationcenter);
				} else {
					$('#notifications-trigger').removeClass('notif-trigger');
					$('#no-notif').show();
					instance.update(notificationcenter);
				}
			}
		});
		setTimeout(function() {
			checknotification();
		}, 20000);
	}
}

/**
 * @name markasread
 * @description Mark a notification as read - call cake controller AJAX request
 * @param {int} [id] [ID of notification]
 */
function markasread(id) {
	$.ajax({
		url: webRootJs + 'notifications/markAsRead',
		type: 'get',
		data: {
			"notification_id": id
		},
	});

	$('.notifnb-' + id).remove();

	if (!$.trim($('#notif-container').html()).length) {
		$('#notifications-trigger').removeClass('notif-trigger');
		$('#no-notif').show();
		instance.update(notificationcenter);
	}
}

/**
 * @name saveasdraft*
 * @description Change status of setup to DRAFT inside the input
 */
function saveasdraftadd() {
	$("#status-add").val('DRAFT');
	$('#publish-add').click();
}

function saveasdraftedit() {
	$("#status-edit").val('DRAFT');
	$('#publish-edit').click();
}

/**
 * @name replaceValidationUI
 * @description Validate the add setup form and change submit button when correct
 */
function replaceValidationUI(form) {
	// Suppress the default bubbles
	form.addEventListener("invalid", function(event) {
		event.preventDefault();
	}, true);

	// Support Safari, iOS Safari, and the Android browser—each of which do not prevent
	// form submissions by default
	form.addEventListener("submit", function(event) {
		if (!this.checkValidity()) {
			event.preventDefault();
		} else {
			$('#publish-add').replaceWith("<span class='float-right button'><i class='fa fa-circle-o-notch fa-spin fa-fw'></i></span>");
		}
	});

	var submitButton = form.querySelector("input[type=submit]");
	submitButton.addEventListener("click", function(event) {
		var invalidFields = form.querySelectorAll(":invalid"),
			listHtml = "",
			label;

		for (var i = 1; i < invalidFields.length; i++) {
			label = form.querySelector("label[for=" + invalidFields[i].id + "]");
			listHtml += "<li>" +
				label.innerHTML +
				" | " +
				invalidFields[i].validationMessage +
				"</li>";
		}

		// If there are errors, give focus to the first invalid field and show
		// the error messages container
		if (invalidFields.length > 0) {
			// Update the list with the new error messages
			toast.alert(listHtml);

			invalidFields[1].focus();
		}
	});
}

// Replace the validation UI for all forms
var forms = document.querySelectorAll("#add_setup_modal form");
for (var i = 0; i < forms.length; i++) {
	replaceValidationUI(forms[i]);
}

/**
 * @name pageTitleNotification
 * @description Display flashing notification in the page title
 */
! function(t, n) {
	t.pageTitleNotification = function() {
		var e = {
			currentTitle: null,
			interval: null
		};
		return {
			on: function(i, l) {
				e.interval || (e.currentTitle = n.title, e.interval = t.setInterval(function() {
					n.title = e.currentTitle === n.title ? i : e.currentTitle;
				}, l || 1e3))
			},
			off: function() {
				t.clearInterval(e.interval), e.interval = null, n.title = e.currentTitle;
			}
		};
	}();
}(window, document);

var callnotif = false;
$(window).focus(function() {
	if (callnotif) {
		pageTitleNotification.off();
		callnotif = false;
	}
});

var recaptchaStatus = 0;
function recaptchaDeferedLoading(){
	if(recaptchaStatus == 0){
		$.getScript('https://www.google.com/recaptcha/api.js');
		recaptchaStatus = 1;
	}
}

function commentModal(action){
	lity(document.getElementById(action + '-comment-script').innerHTML);
	$('#'+ action + '-comment-field').emojioneArea({pickerPosition: `top`});
}
