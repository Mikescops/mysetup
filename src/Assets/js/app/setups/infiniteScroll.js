/**
 * @name infiniteScroll
 * @description Infinite Scroll to display setups lists
 * @param {int} [nbtodisplay] [Number of setups to display at each call of the function]
 */
const infiniteScroll = (nbtodisplay) => {
	let offset = nbtodisplay;
	$(window).data('ajaxready', true);
	// On déclenche une fonction lorsque l'utilisateur utilise sa molette
	$(window).scroll(() => {
		if ($(window).data('ajaxready') == false) return; //permet de couper les trigger parallèles
		if (($(window).scrollTop() + $(window).height()) + 500 > $(document).height()) {
			$(window).data('ajaxready', false);
			$.ajax({
				url: webRootJs + 'api/getSetups',
				data: {
					p: offset,
					n: nbtodisplay,
					o: 'DESC'
				},
				dataType: 'json',
				type: 'get',
				success: (setups) => {
					if (setups[0]) {
						// Cache of the template
						const template = document.getElementById('template-list-item');
						// Get the contents of the template
						const templateHtml = template.innerHTML;
						// Final HTML variable as empty string
						let listHtml = '';
						// Simple sanitizer for HTML entities
						const escapeHtml = (text) => {
							const map = {
								'&': '&amp;',
								'<': '&lt;',
								'>': '&gt;',
								'"': '&quot;',
								'\'': '&#039;'
							};
							return text.replace(/[&<>"']/g, (match) => {
								return map[match];
							});
						};

						$.each(setups, (key, value) => {
							let title = escapeHtml(value.title);
							let url = webRootJs + `setups/${value.id}-${convertToSlug(escapeHtml(value.title))}`;
							// It's possible that some setups have lost their featured image...
							let img_src = webRootJs;
							if (value.resources[0]) {
								img_src += value.resources[0].src;
							}
							else {
								img_src += 'img/not_found.jpg';
							}
							let likes = value.like_count;
							let user_name = escapeHtml(value.user.name);
							let user_src = webRootJs + `uploads/files/pics/profile_picture_${value.user_id}.png?` + ('0' + (new Date(value.user.modificationDate)).getMinutes()).slice(-2) + ('0' + (new Date(value.user.modificationDate)).getSeconds()).slice(-2);
							let user_url = webRootJs + 'users/' + value.user_id;
							let main_color = [0, 0, 0];
							if (value.main_colors) {
								main_color = $.parseJSON(value.main_colors)[0];
							}
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

						$('#grid-holder').append(listHtml);

						$(window).data('ajaxready', true);
					} else {
						$('.no_more_setups').html('No more setups to display...');
					}
				}
			});
			offset += nbtodisplay;
		}
	});
};
