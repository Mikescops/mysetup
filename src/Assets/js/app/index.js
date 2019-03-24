/*!
 * mySetup v3.1.0
 * https://mysetup.co
 *
 * Copyright (c) 2019 Corentin Mors / Samuel Forestier
 * All rights reserved
 */

/*jshint esversion: 6 */

//=require ui/index.js

//=require setups/index.js

//=require users/index.js

//=require utils/index.js

//=require notifications/index.js

//=require comments/index.js

/**
 * On load functions
 */
$(function () {
	$('.is_author').click(function () {
		$(this).hide();
		$('.setup_author').show('fast');
		return false;
	});
	$('.reset_pwd').click(function () {
		$(this).hide();
		$('.pwd_field').show('fast');
		return false;
	});
	$('#social-networks').jsSocials({
		shareIn: 'popup',
		showCount: false,
		showLabel: false,
		shares: ['twitter', 'facebook', 'googleplus', 'pinterest', 'whatsapp']
	});

	$('#profileImage').click(function () {
		$('#profileUpload').click();
	});
	$('#profileUpload').change(function () {
		fasterPreview(this);
	});


	$('.edit-comment').click(function () {
		var $comment = $(this),
			$place = $comment.attr('source');
		var $oldvalue = $('#' + $place + '> p').attr('content'),
			$id = $place.replace(/[^0-9]/g, '');

		$('.textarea-edit-comment > .emojionearea-editor').text($oldvalue);
		$('#edit-comment-hidden > form').attr('action', $('#edit-comment-hidden > form').attr('action') + '/' + $id);
	});
});
