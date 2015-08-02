<a data-pin-do="embedUser" href="%s" data-pin-scale-width="%s" data-pin-board-width="%s" data-pin-scale-height="%s"></a>

<script type="text/javascript">
	var pinterest_initialized = 0;

	function initialize_Pinterest_Widgets() {
		if (pinterest_initialized) return;

		var d = document;
		var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
		p.type = 'text/javascript';
		p.async = true;
		p.src = '//assets.pinterest.com/js/pinit.js';
		f.parentNode.insertBefore(p, f);

		pinterest_initialized = 1;
	}

	function scp_prependPinterest($) {
		$tabs          = $('#social-community-popup .tabs');
		$pinterest_tab = $('#social-community-popup .pinterest-tab');

		if ($pinterest_tab.length && parseInt($pinterest_tab.data('index')) == 1) {
			initialize_Pinterest_Widgets();
		} else if ($tabs.length == 0) {
			initialize_Pinterest_Widgets();
		}

		$pinterest_tab.on('click', function() {
			initialize_Pinterest_Widgets();
		});
	}
</script>
