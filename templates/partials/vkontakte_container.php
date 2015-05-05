<script type="text/javascript" src="//vk.com/js/api/openapi.js?105"></script>
<div id="scp_vk_groups" style="height:250px !important;"></div>
<script>
	var vk_initialized = 0;

	jQuery(document).ready(function($) {
		$('#social-community-popup .vk-tab').on('click', function() {
			if ((typeof(VK) !== 'undefined') && !vk_initialized) {
				VK.Widgets.Group('scp_vk_groups', {mode: %s, width: '%s', height: '%s', color1: '%s', color2: '%s', color3: '%s'}, %s);
				vk_initialized = true;
			}
		});
	});
</script>
