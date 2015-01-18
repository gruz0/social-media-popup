<div id="scp_ok_group_widget"></div>
<script>
	!function (d, id, did, st) {
		var js = d.createElement("script");
		js.src = "http://connect.ok.ru/connect.js";
		js.onload = js.onreadystatechange = function () {
			if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
				if (!this.executed) {
					this.executed = true;
					setTimeout(function () {
						OK.CONNECT.insertGroupWidget(id,did,st);
					}, 0);
				}
			}
		}
		d.documentElement.appendChild(js);
	}(document,"scp_ok_group_widget","%s","{width:%s,height:%s}");
</script>
