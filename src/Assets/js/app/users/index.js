/**
 * @name fasterPreview
 * @description Update Profile Picture on Upload
 */
const fasterPreview = (uploader) => {
	if (uploader.files && uploader.files[0]) {
		$('#profileImage').attr('src',
			window.URL.createObjectURL(uploader.files[0]));
	}
};
