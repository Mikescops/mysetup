// Colorize url input to prevent random text in video area
$('.video-url-input').on('input', () => {
	const input = $(this);
	if (input.val().substring(0, 4) == 'www.') { input.val('http://www.' + input.val().substring(4)); }
	const re = /(http|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?/;
	const is_url = re.test(input.val());
	if (is_url) { input.removeClass('invalid').addClass('valid'); }
	else { input.removeClass('valid').addClass('invalid'); }
});


/**
 * EDIT SETUP tabs
 */
$(() => {
	// constants
	const SHOW_CLASS = 'show-edit',
		HIDE_CLASS = 'hide-edit',
		ACTIVE_CLASS = 'active-edit';

	$('.tabs-edit').on('click', 'li a', function (e) {
		e.preventDefault();
		const $tab = $(this),
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
		const $next = $(this),
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

const featuredPreviewChange = () => {
	$('.gallery-holder.homide').removeClass('homide');
};
