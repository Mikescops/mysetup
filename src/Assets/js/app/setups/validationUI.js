/**
 * @name replaceValidationUI
 * @description Validate the add setup form and change submit button when correct
 */
function replaceValidationUI(form) {
	// Suppress the default bubbles
	form.addEventListener('invalid', function (event) {
		event.preventDefault();
	}, true);

	// Support Safari, iOS Safari, and the Android browser—each of which do not prevent
	// form submissions by default
	form.addEventListener('submit', function (event) {
		if (!this.checkValidity()) {
			event.preventDefault();
		} else {
			$('#publish-add').replaceWith('<span class=\'float-right button\'><i class=\'fa fa-circle-o-notch fa-spin fa-fw\'></i></span>');
		}
	});

	var submitButton = form.querySelector('input[type=submit]');
	submitButton.addEventListener('click', function (event) {
		var invalidFields = form.querySelectorAll(':invalid'),
			listHtml = '',
			label;

		for (var i = 1; i < invalidFields.length; i++) {
			label = form.querySelector('label[for=' + invalidFields[i].id + ']');
			listHtml += '<li>' +
				label.innerHTML +
				' | ' +
				invalidFields[i].validationMessage +
				'</li>';
		}

		// If there are errors, give focus to the first invalid field and show
		// the error messages container
		if (invalidFields.length > 0) {
			// Update the list with the new error messages
			toast.alert(listHtml);

			invalidFields[1].focus();
		}
	});
}

// Replace the validation UI for all forms
var forms = document.querySelectorAll('#add_setup_modal form');
for (var i = 0; i < forms.length; i++) {
	replaceValidationUI(forms[i]);
}

/**
 * @name saveasdraft*
 * @description Change status of setup to DRAFT inside the input
 */
function saveasdraftadd() {
	$('#status-add').val('DRAFT');
	$('#publish-add').click();
}

function saveasdraftedit() {
	$('#status-edit').val('DRAFT');
	$('#publish-edit').click();
}
