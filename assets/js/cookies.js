var smp_cookie_name = 'social-media-popup';

function smp_setCookie(name, value, options) {
	options = options || {};
	let expires = options.expires;

	if (typeof expires == "number" && expires) {
		let d = new Date();
		d.setTime(d.getTime() + expires*1000);
		expires = options.expires = d;
	}

	if (expires && expires.toUTCString) {
		options.expires = expires.toUTCString();
	}

	value = encodeURIComponent(value);
	let updatedCookie = name + "=" + value;

	for(let propName in options) {
		updatedCookie += "; " + propName;
		let propValue = options[propName];
		if (propValue !== true) {
			updatedCookie += "=" + propValue;
		}
	}

	document.cookie = updatedCookie;
}
window.smp_setCookie = smp_setCookie;

function smp_getCookie(name) {
	let nameEQ = name + "=";
	let ca = document.cookie.split(';');

	for(let i=0;i < ca.length;i++) {
		let c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}

	return null;
}
window.smp_getCookie = smp_getCookie;

function smp_deleteCookie(name) {
	let date = new Date().getTime();
	smp_setCookie(name, '', { expires: date - 3600, path: '/' });
}
window.smp_deleteCookie = smp_deleteCookie;

function smp_clearAllPluginCookies() {
	if (window.confirm(window.smp_cookies.clearCookiesMessage)) {
		smp_deleteCookie(smp_cookie_name);
		smp_deleteCookie('smp-page-views');
		document.location.reload(true);
	}
}
window.smp_clearAllPluginCookies = smp_clearAllPluginCookies;
