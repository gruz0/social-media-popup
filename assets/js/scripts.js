var smp_eventFired = false;
var smp_firedEventDescription = '';

// jshint ignore:start
var smp_Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=smp_Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=smp_Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}};
// jshint ignore:end

jQuery(document).ready(function($){
	smp_renderPopup();

	$('#social-community-popup ul.tabs, #social-community-popup ul.smp-icons').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
	});

	$('#social-community-popup ul.tabs li:first, #social-community-popup ul.smp-icons li:first').addClass('current');
	$('#social-community-popup .section .box:first').addClass('visible');
});

function is_smp_cookie_present() {
	return (smp_getCookie('social-community-popup') && smp_getCookie('social-community-popup') == 'true');
}

function smp_destroyPlugin(after_n_days, container_id) {
	var date = new Date( new Date().getTime() + (1000 * 60 * 60 * 24 * after_n_days) );
	smp_setCookie("social-community-popup", "true", { "expires": date, "path": "/" } );
	smp_deleteCookie('smp-page-views');
	jQuery(container_id || '#social-community-popup').hide();
}

function smp_renderPopup() {
	// jshint ignore:start
	eval(smp_Base64.decode(smp.encodedContent));
	// jshint ignore:end
}

function smp_getWindowHeight() {
	return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight || 0;
}

function smp_getWindowYscroll() {
	return window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop || 0;
}

function smp_getDocHeight() {
	return Math.max(
		document.body.scrollHeight || 0,
		document.documentElement.scrollHeight || 0,
		document.body.offsetHeight || 0,
		document.documentElement.offsetHeight || 0,
		document.body.clientHeight || 0,
		document.documentElement.clientHeight || 0
		);
}

function smp_getScrollPercentage() {
	return parseInt(Math.abs(((smp_getWindowYscroll() + smp_getWindowHeight()) / smp_getDocHeight()) * 100));
}
