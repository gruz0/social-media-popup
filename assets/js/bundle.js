import { Base64 } from 'js-base64';

let smp_container_id = '#social_media_popup';
let smp_cookie_name = 'social-media-popup';

let smp_eventFired = false;
let smp_firedEventDescription = '';

jQuery(document).ready(function($){
	smp_renderPopup();

	$(smp_container_id + ' ul.tabs, ' + smp_container_id + ' ul.smp-icons').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
	});

	$(smp_container_id + ' ul.tabs li:first, ' + smp_container_id + ' ul.smp-icons li:first').addClass('current');
	$(smp_container_id + ' .section .box:first').addClass('visible');
});

function is_smp_cookie_present() {
	return (smp_getCookie(smp_cookie_name) && smp_getCookie(smp_cookie_name) == 'true');
}
window.is_smp_cookie_present = is_smp_cookie_present;

function smp_destroyPlugin(after_n_days, container_id) {
	let date = new Date( new Date().getTime() + (1000 * 60 * 60 * 24 * after_n_days) );
	smp_setCookie(smp_cookie_name, "true", { "expires": date, "path": "/" } );
	smp_deleteCookie('smp-page-views');
	jQuery(container_id || smp_container_id).hide();
}
window.smp_destroyPlugin = smp_destroyPlugin;

function smp_renderPopup() {
	// jshint ignore:start
	eval(Base64.decode(smp.encodedContent));
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
window.smp_getScrollPercentage = smp_getScrollPercentage;
