/**
 * @name likeSetup
 * @description Call controller with AJAX request to add or remove a like on a setup
 * @param {int} [id] [ID of setup]
 */
const likeSetup = (id) => {
	const answer_like = () => {
		$('.like_button').addClass('active');
		printLikes(id);
	};

	const answer_dislike = () => {
		$('.like_button').removeClass('active');
		printLikes(id);
	};

	const answer_error = (response) => {
		// console.log(response);
	};
	
	if ($('.like_button').hasClass('active')) {
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
};

/**
 * @name printLikes
 * @description Display number of likes of a setup - call cake controller AJAX request
 * @param {int} [id] [ID of setup]
 */
const printLikes = (id) => {
	$.ajax({
		url: webRootJs + 'likes/getlikes',
		data: {
			setup_id: id
		},
		dataType: 'html',
		type: 'get',
		success: (json) => {
			$('.pointing_label').html(json);
		},
		error: (request, status, error) => {
			// console.log(error);
		}
	});
};

/**
 * @name doesLike
 * @description Check if setup is liked by the current setup - call cake controller AJAX request
 * @param {int} [setup] [ID of setup]
 */
const doesLike = (setup) => {
	$.ajax({
		url: webRootJs + 'likes/doesLike',
		data: {
			setup_id: setup
		},
		dataType: 'html',
		type: 'get',
		success: (json) => {
			if (json == 'true')
				$('.like_button').addClass('active');
		}
	});
};
