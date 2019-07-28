/** @license EMBED API mySetup.co v3.7 **/

const r = (f) => {
	/in/.test(document.readyState) ? setTimeout(`r(${f})`, 9) : f();
};

r(() => {
	const renderFrame = (item) => {
		let iframe = document.createElement('iframe');
		iframe.frameBorder = 0;
		iframe.scrolling = 'no';
		iframe.height = 0;
		if (item.getAttribute('ms-width') && item.getAttribute('ms-width') != 'responsive') {
			iframe.width = item.getAttribute('ms-width');
			iframe.height = (iframe.width * 75) / 100;
		} else {
			iframe.width = '100%';
			iframe.height = '100%';
			iframe.setAttribute('style', 'display:block;position:absolute;top:0;left:0');
			item.setAttribute('style', 'position:relative;padding-bottom: 75%');
		}
		if (item.getAttribute('dev')) {
			iframe.src = item.getAttribute('dev') + 'api/embed/' + item.getAttribute('ms-setup');
		} else {
			iframe.src = 'https://mysetup.co/api/embed/' + item.getAttribute('ms-setup');
		}
		item.innerHTML = '';
		item.prepend(iframe);
	};

	let frames = document.getElementsByClassName('mysetup-embed');

	if (frames.length > 0) {
		for (i = 0; i < frames.length; i++) {
			renderFrame(frames[i]);
		}
	}
	else {
		frames = document.getElementById('mysetup-embed');
		if (frames == null) {
			// eslint-disable-next-line no-console
			console.warn('[MYSETUP EMBED API] No mysetup.co frame found, please use our documentation to handle errors.');
		}
		else {
			// eslint-disable-next-line no-console
			console.info('[MYSETUP EMBED API] Fallback to "id" attribute, please consider using "class" instead.');
			renderFrame(frames);
		}
	}
});
