$j = jQuery.noConflict();

$j(document).ready(function() {

	// Клик по табу и открытие соответствующей вкладки
	$j('#scp_welcome_screen ul.tabs').on('click', 'li:not(.current)', function() {
		$j(this).addClass('current').siblings().removeClass('current')
		.parents('#scp_welcome_screen').find('div.box').eq($j(this).index()).fadeIn(150).siblings('div.box').hide();
	});

	$j('#scp_upload_background_image').click(function() {
		tb_show('Upload a background image', 'media-upload.php?referer=social_community_popup&type=image&TB_iframe=true&post_id=0', false);
		return false;
	});

	window.send_to_editor = function(html) {
		$j('.scp-background-image').html(html);
		var image_src = $j('.scp-background-image img').attr('src');
		$j('#scp_background_image').val(image_src);
		tb_remove();
	}
});
