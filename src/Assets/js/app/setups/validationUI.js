/**
 * @name replaceValidationUI
 * @description Validate the add setup form and change submit button when correct
 */
const replaceValidationUI = (form) => {
	// Suppress the default bubbles
	form.addEventListener('invalid', (event) => {
		event.preventDefault();
	}, true);

	// Support Safari, iOS Safari, and the Android browser—each of which do not prevent
	// form submissions by default
	form.addEventListener('submit', (event) => {
		if (!this.checkValidity()) {
			return event.preventDefault();
		}
		$('#publish-add').replaceWith('<span class=\'float-right button\'><i class=\'fa fa-circle-o-notch fa-spin fa-fw\'></i></span>');
	});

	const submitButton = form.querySelector('input[type=submit]');
	submitButton.addEventListener('click', () => {
		const invalidFields = form.querySelectorAll(':invalid');
		let listHtml = '', label;

		for (let i = 0; i < invalidFields.length; i++) {
			label = form.querySelector(`label[for=${invalidFields[i].id}]`);
			listHtml += `<li>${label.innerHTML} | ${invalidFields[i].validationMessage}</li>`;
		}

		// If there are errors, give focus to the first invalid field and show
		// the error messages container
		if (invalidFields.length > 0) {
			// Update the list with the new error messages
			toast.alert(listHtml);

			invalidFields[1].focus();
		}
	});
};

// Replace the validation UI for all forms
const forms = document.querySelectorAll('.form_add_setup');
for (let i = 0; i < forms.length; i++) {
	replaceValidationUI(forms[i]);
}

/**
 * @name saveasdraft*
 * @description Change status of setup to DRAFT inside the input
 */
const saveasdraftadd = () => {
	$('#status-add').val('DRAFT');
	$('#publish-add').click();
};

const saveasdraftedit = () => {
	$('#status-edit').val('DRAFT');
	$('#publish-edit').click();
};
