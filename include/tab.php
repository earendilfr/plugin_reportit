<?php

function reportit_show_tab() {
	global $config;
	
	if (api_user_realm_auth('reportit_view.php')) {
		$cp = false;
		if (basename($_SERVER['PHP_SELF']) == 'reportit_view.php')
			$cp = true;
		
		print '<a href="' . $config['url_path'] . 'plugins/reportit/reportit_view.php"><img src="' . $config['url_path'] . 'plugins/reportit/images/tab_reportit_' . (basename($_SERVER['PHP_SELF']) == 'reportit_view.php' ? 'down' : 'up'). '.png" alt="reportit" align="absmiddle" border="0"></a>';
	}
}

?>