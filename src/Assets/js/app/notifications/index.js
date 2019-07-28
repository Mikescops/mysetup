/**
 * @name checknotification
 * @description Notification check and refresh - call cake controller AJAX request
 */
const checknotification = () => {
	if (inIframe() == false) {
		$.ajax({
			url: webRootJs + 'notifications/getNotifications',
			data: {
				n: '8'
			},
			dataType: 'html',
			type: 'get',
			success: (json) => {
				notifs = $.parseJSON(json);
				$('#notif-container').html('');
				if (notifs['notifications'].length) {
					$.each(notifs['notifications'], (key, value) => {
						$('#notif-container').append(`<div onclick="markNotificationAsRead(${value.id})" id="notifnb-${value.id}" class="notif notifnb-${value.id}">
							${value.content}
							<div class="notif-close">
								<span onclick="markNotificationAsRead(${value.id})"><i class="fa fa-eye-slash"></i></span>
							</div>
						</div>`);
					});

					$('#notifications-trigger').addClass('notif-trigger');
					$('#no-notif').hide();
					notificationInstance.update(notificationcenter);
				} else {
					$('#notifications-trigger').removeClass('notif-trigger');
					$('#no-notif').show();
					notificationInstance.update(notificationcenter);
				}
			}
		});
		setTimeout(() => {
			checknotification();
		}, 30000);
	}
};

/**
 * @name markNotificationAsRead
 * @description Mark a notification as read - call cake controller AJAX request
 * @param {int} [id] [ID of notification]
 */
const markNotificationAsRead = (id) => {
	$.ajax({
		url: webRootJs + 'notifications/markAsRead',
		type: 'get',
		data: {
			id
		},
		success: (response) => {
			if (response == 'MARKED') {
				$('#notifnb-' + id).remove();

				$(`.notifnb-${id}`).removeClass('unread');
				$(`.notifnb-${id} .notif-close .notif-read`).html(`<span onclick="markNotificationAsUnread(${id})"><i class="fa fa-eye"></i></span>`);

				if (!$.trim($('#notif-container').html()).length) {
					$('#notifications-trigger').removeClass('notif-trigger');
					$('#no-notif').show();
					notificationInstance.update(notificationcenter);
				}
			}
		}
	});
};

/**
 * @name markNotificationAsUnread
 * @description Mark a notification as read - call cake controller AJAX request
 * @param {int} [id] [ID of notification]
 */
const markNotificationAsUnread = (id) => {
	$.ajax({
		url: webRootJs + 'notifications/markAsUnread',
		type: 'get',
		data: {
			id
		},
		success: (response) => {
			if (response == 'MARKED') {
				$(`.notifnb-${id}`).addClass('unread');
				$(`.notifnb-${id} .notif-close .notif-read`).html(`<span onclick="markNotificationAsRead(${id})"><i class="fa fa-eye-slash"></i></span>`);
			}
		}
	});
};

/**
 * @name deleteNotification
 * @description Mark a notification as read - call cake controller AJAX request
 * @param {int} [id] [ID of notification]
 */
const deleteNotification = (id) => {
	$.ajax({
		url: webRootJs + 'notifications/delete',
		type: 'get',
		data: {
			id
		},
		success: (response) => {
			if (response == 'DELETED') {
				$(`.notifnb-${id}`).remove();

				if (!$.trim($('#notif-container').html()).length) {
					$('#notifications-trigger').removeClass('notif-trigger');
					$('#no-notif').show();
					notificationInstance.update(notificationcenter);
				}
			}
		}
	});
};
