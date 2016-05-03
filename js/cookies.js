function scp_setCookie(name, value, options) {
	options = options || {};
	var expires = options.expires;

	if (typeof expires == "number" && expires) {
		var d = new Date();
		d.setTime(d.getTime() + expires*1000);
		expires = options.expires = d;
	}

	if (expires && expires.toUTCString) { 
		options.expires = expires.toUTCString();
	}

	value = encodeURIComponent(value);
	var updatedCookie = name + "=" + value;

	for(var propName in options) {
		updatedCookie += "; " + propName;
		var propValue = options[propName];    
		if (propValue !== true) { 
			updatedCookie += "=" + propValue;
		}
	}

	document.cookie = updatedCookie;
}

function scp_getCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');

	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}

	return null;
}

function scp_deleteCookie(name) {
	var date = new Date().getTime();
	scp_setCookie(name, '', { expires: date - 3600, path: '/' })
}

function scp_clearAllPluginCookies() {
	if (window.confirm(window.scp_cookies.clearCookiesMessage)) {
		scp_deleteCookie('social-community-popup');
		scp_deleteCookie('scp-page-views');
		document.location.reload(true);
	}
}
