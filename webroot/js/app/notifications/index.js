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
			success: function (json) {
				notifs = $.parseJSON(json);
				$('#notif-container').html('');
				if (notifs['notifications'].length) {
					$.each(notifs['notifications'], function (key, value) {
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
		setTimeout(function () {
			checknotification();
		}, 30000);
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