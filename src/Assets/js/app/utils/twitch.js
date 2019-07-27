/**
 * @name logTwitch
 * @description Trigger login of Twitch through OAuth2
 * @param {string} [lang] [Language of user]
 */
const logTwitch = (lang) => {
	const rand_state = Math.random().toString(36).slice(2) + Math.random().toString(36).slice(2);
	window.location = `https://api.twitch.tv/kraken/oauth2/authorize?client_id=${twitchClientId}&redirect_uri=${webRootJs}twitch/&response_type=code&scope=user_read&state=${lang}${rand_state}`;
};
