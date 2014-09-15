jQuery(document).ready(function($){
    $('#social-community-popup ul.tabs').on('click', 'li:not(.current)', function() {  
        $(this).addClass('current').siblings().removeClass('current')  
            .parents('div.section').find('div.box').eq($(this).index()).fadeIn(150).siblings('div.box').hide();  
    });                 

    $('#social-community-popup ul.tabs li:first').addClass('current');
    $('#social-community-popup .section .box:first').addClass('visible');

});

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

// возвращает cookie с именем name, если есть, если нет, то undefined
function scp_getCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function scp_deleteCookie(name) {
	scp_setCookie(name, "", { expires: -1 })
}
