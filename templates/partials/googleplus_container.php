<div class="g-%s" data-width="%s" data-href="%s" data-theme="%s" data-showtagline="%s" data-showcoverphoto="%s" data-rel="%s"></div>

<!-- Place this tag after the last widget tag. -->
<script type="text/javascript">
	var google_plus_initialized = 0;

	function initialize_GooglePlus_Widgets() {
		if (google_plus_initialized) return;

		var po = document.createElement('script');
		po.type  = 'text/javascript';
		po.async = true;
		po.src   = 'https://apis.google.com/js/platform.js';
		var s    = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(po, s);

		google_plus_initialized = 1;
	}

	function scp_prependGooglePlus($) {
		$tabs            = $('#social-community-popup .tabs');
		$google_plus_tab = $('#social-community-popup .google-plus-tab');

		if ($google_plus_tab.length && parseInt($google_plus_tab.data('index')) == 1) {
			initialize_GooglePlus_Widgets();
		} else if ($tabs.length == 0) {
			initialize_GooglePlus_Widgets();
		}

		$google_plus_tab.on('click', function() {
			initialize_GooglePlus_Widgets();
		});
	}
</script>
