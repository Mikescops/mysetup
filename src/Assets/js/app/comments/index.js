function commentModal(action) {
	lity(document.getElementById(action + '-comment-script').innerHTML);
	$('#' + action + '-comment-field').emojioneArea({ pickerPosition: 'top' });
}
