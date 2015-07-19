<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
<div id="scp_vk_groups" style="height:250px !important;"></div>
<script>
	var vk_initialized = 0;

	function initialize_VK_Widgets() {
		VK.Widgets.Group('scp_vk_groups', {mode: %s, width: '%s', height: '%s', color1: '%s', color2: '%s', color3: '%s'}, %s);
		vk_initialized = 1;
	}

	jQuery(document).ready(function($) {
		$vk_tab = $('#social-community-popup .vk-tab');

		if ($vk_tab.length && parseInt($vk_tab.data('index')) == 1) {
			setTimeout("initialize_VK_Widgets();", %s);
		}

		$vk_tab.on('click', function() {
			if ((typeof(VK) !== 'undefined') && !vk_initialized) {
				initialize_VK_Widgets();
			}
		});
	});
</script>
