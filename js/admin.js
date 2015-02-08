$j = jQuery.noConflict();

$j(document).ready(function() {

	// Клик по табу и открытие соответствующей вкладки
    $j('#scp_welcome_screen ul.tabs').on('click', 'li:not(.current)', function() {  
        $j(this).addClass('current').siblings().removeClass('current')
        .parents('#scp_welcome_screen').find('div.box').eq($j(this).index()).fadeIn(150).siblings('div.box').hide();  
    });                 
});
