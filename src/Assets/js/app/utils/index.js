//=require twitch.js

/**
 * @name convertToSlug
 * @description Convert string to a slug which can be use as an url
 * @param {string} [str] [String to sluggify]
 */
const convertToSlug = (str) => {
	var rExps = [
		{ re: /[\xC0-\xC6]/g, ch: 'A' },
		{ re: /[\xE0-\xE6]/g, ch: 'a' },
		{ re: /[\xC8-\xCB]/g, ch: 'E' },
		{ re: /[\xE8-\xEB]/g, ch: 'e' },
		{ re: /[\xCC-\xCF]/g, ch: 'I' },
		{ re: /[\xEC-\xEF]/g, ch: 'i' },
		{ re: /[\xD2-\xD6]/g, ch: 'O' },
		{ re: /[\xF2-\xF6]/g, ch: 'o' },
		{ re: /[\xD9-\xDC]/g, ch: 'U' },
		{ re: /[\xF9-\xFC]/g, ch: 'u' },
		{ re: /[\xC7-\xE7]/g, ch: 'c' },
		{ re: /[\xD1]/g, ch: 'N' },
		{ re: /[\xF1]/g, ch: 'n' }];

	let trimmed = $.trim(str);
	for (let i = 0, len = rExps.length; i < len; i++)
		trimmed = trimmed.replace(rExps[i].re, rExps[i].ch);
	
	return trimmed.replace(/[^a-z0-9-]/gi, '-')
		.replace(/-+/g, '-')
		.replace(/^-|-$/g, '');
};
