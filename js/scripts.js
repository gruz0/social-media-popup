jQuery(document).ready(function($){
	$('#social-community-popup ul.tabs').on('click', 'li:not(.current)', function() {
		$(this).addClass('current').siblings().removeClass('current')
			.parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();
	});

	$('#social-community-popup ul.tabs li:first').addClass('current');
	$('#social-community-popup .section .box:first').addClass('visible');
});

function is_scp_cookie_present() {
	return (scp_getCookie('social-community-popup') && scp_getCookie('social-community-popup') == 'true');
}

function scp_destroyPlugin($, after_n_days) {
	var date = new Date( new Date().getTime() + (1000 * 60 * 60 * 24 * after_n_days) );
	scp_setCookie("social-community-popup", "true", { "expires": date, "path": "/" } );
	scp_deleteCookie('scp-page-views');
	$("#social-community-popup").hide();
}
