/**
 * ADD SETUP tabs
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


// Colorize url input to prevent random text in video area
$('.video-url-input').on('input', function () {
	var input = $(this);
	if (input.val().substring(0, 4) == 'www.') { input.val('http://www.' + input.val().substring(4)); }
	var re = /(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?/;
	var is_url = re.test(input.val());
	if (is_url) { input.removeClass('invalid').addClass('valid'); }
	else { input.removeClass('valid').addClass('invalid'); }
});


/**
 * EDIT SETUP tabs
 */
$(function () {
	// constants
	var SHOW_CLASS = 'show-edit',
		HIDE_CLASS = 'hide-edit',
		ACTIVE_CLASS = 'active-edit';

	$('.tabs-edit').on('click', 'li a', function (e) {
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
	$('.form-action-edit').on('click', '.next', function (e) {
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

function featuredPreviewChange() {
	$('.gallery-holder.homide').removeClass('homide');
}
