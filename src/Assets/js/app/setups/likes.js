/**
 * @name likeSetup
 * @description Call controller with AJAX request to add or remove a like on a setup
 * @param {int} [id] [ID of setup]
 */
function likeSetup(id) {

	if ($('.red_button').hasClass('active')) {
		$.ajax({
			url: webRootJs + 'likes/dislike',
			type: 'get',
			data: {
				'setup_id': id
			},
			success: answer_dislike,
			error: answer_error
		});
	} else {
		$.ajax({
			url: webRootJs + 'likes/like',
			type: 'get',
			data: {
				'setup_id': id
			},
			success: answer_like,
			error: answer_error
		});
	}

	function answer_like() {
		$('.red_button').addClass('active');
		printLikes(id);
	}

	function answer_dislike() {
		$('.red_button').removeClass('active');
		printLikes(id);
	}

	function answer_error(response) {
		// console.log(response);
	}
}

/**
 * @name printLikes
 * @description Display number of likes of a setup - call cake controller AJAX request
 * @param {int} [id] [ID of setup]
 */
function printLikes(id) {
	$.ajax({
		url: webRootJs + 'likes/getlikes',
		data: {
			setup_id: id
		},
		dataType: 'html',
		type: 'get',
		success: function (json) {
			$('.pointing_label').html(json);
		},
		error: function (request, status, error) {
			// console.log(error);
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
		url: webRootJs + 'likes/doesLike',
		data: {
			setup_id: setup
		},
		dataType: 'html',
		type: 'get',
		success: function (json) {
			if (json == 'true')
				$('.red_button').addClass('active');
		}
	});
}
