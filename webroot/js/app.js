/*!
 * mySetup v3.1.0
 * https://mysetup.co
 *
 * Copyright (c) 2019 Corentin Mors / Samuel Forestier
 * All rights reserved
 */

/*jshint esversion: 6 */

// @koala-prepend "./app/ui/index.js"

// @koala-prepend "./app/setups/index.js"

// @koala-prepend "./app/users/index.js"

// @koala-prepend "./app/utils/index.js"

// @koala-prepend "./app/notifications/index.js"

// @koala-prepend "./app/comments/index.js"

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
	$("#social-networks").jsSocials({
		shareIn: "popup",
		showCount: false,
		showLabel: false,
		shares: ["twitter", "facebook", "googleplus", "pinterest", "whatsapp"]
	});

	$("#profileImage").click(function (e) {
		$("#profileUpload").click();
	});
	$("#profileUpload").change(function () {
		fasterPreview(this);
	});


	$(".edit-comment").click(function (e) {
		var $comment = $(this),
			$place = $comment.attr('source');
		var $oldvalue = $('#' + $place + '> p').attr('content'),
			$id = $place.replace(/[^0-9]/g, '');

		$('.textarea-edit-comment > .emojionearea-editor').text($oldvalue);
		$('#edit-comment-hidden > form').attr('action', $('#edit-comment-hidden > form').attr('action') + '/' + $id);
	});
});

