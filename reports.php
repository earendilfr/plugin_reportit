<?php

chdir('../..');

include_once('./include/auth.php');
include_once('./plugins/reportit/include/global_reports.php');

switch (get_request_var('action')) {
	case 'save':
		report_save();
		break;
	case 'actions':
		report_action();
		break;
	case 'edit':
		top_header();
		report_edit();
		bottom_footer();
		break;
	default:
		top_header();
		report();
		bottom_footer();
		break;
}

function report_save() {
	// TODO
}

function report_action() {
	// TODO
}

function report_edit() {
	// TODO
}

function report() {
	// TODO
}

?>