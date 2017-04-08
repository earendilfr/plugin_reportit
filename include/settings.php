<?php

function reportit_draw_navigation_text($nav) {
	global $config;
	
	$temp_nav = array(
		'reports.php' => array(
			'title' => __('Reports'),
			'mapping' => "index.php:",
			'url' => "reports.php",
			'level' => 1),
		'reports.php:edit' => array(
			'title' => __('(edit)'),
			'mapping' => "index.php:,reports.php:", 
			'url' => "",
			'level' => 2),
		'reports.php:add' => array(
			'title' => __('(add)'),
			'mapping' => 'index.php:,reports.php:',
			'url' => '',
			'level' => 2),
		'reports.php:actions' => array(
			'title' => __('actions)'),
			'mapping' => 'index.php:,reports.php:',
			'url' => '',
			'level' => 2),
		'reports.php:save' => array(
			'title' => __('(save)'),
			'mapping' => 'index.php:,reports.php:',
			'url' => '',
			'level' => 2),
		'rrdlist.php' => array(
			'title' => __('Data Items'),
			'mapping' => 'index.php:,reports.php:',
			'url' => "rrdlist.php",
			'level' => 2),
		'rrdlist.php:actions' => array(
			'title' => __('(actions)'),
			'mapping' => 'index.php:,reports.php:,rrdlist.php:',
			'urk' => '',
			'level' => 3),
		'rrditems.php' => array(
			'title' => __('(add)'),
			'mapping' => 'index.php:,reports.php:,rrdlist.php:',
			'urk' => 'rrditems.php',
			'level' => 3),
		'templates.php' => array(
			'title' => __('Report Templates'),
			'mapping' => "index.php:",
			'url' => "templates.php",
			'level' => 1),
		'templates.php:add' => array(
			'title' => __('(add)'),
			'mapping' => "index.php:,templates.php:",
			'url' => '',
			'level' => 2),
		'templates.php:actions' => array(
			'title' => __('(actions)'),
			'mapping' => "index.php:,templates.php:",
			'url' => '',
			'level' => 2),
		'templates.php:edit' => array(
			'title' => __('(edit)'),
			'mapping' => "index.php:,templates.php:",
			'url' => '',
			'level' => 2),
		'templates.php:save' => array(
			'title' => __('(save)'),
			'mapping' => "index.php:,templates.php:",
			'url' => '',
			'level' => 2),
		'measurands.php' => array(
			'title' => __('Measurands'),
			'mapping' => "index.php:,templates.php:",
			'url' => "measurands.php",
			'level' => 3),
		'measurands.php:add' => array(
			'title' => __('(add)'),
			'mapping' => "index.php:,templates.php:,measurands.php:",
			'utl' => '',
			'level' => 4),
		'measurands.php:edit' => array(
			'title' => __('(edit)'),
			'mapping' => "index.php:,templates.php:,measurands.php:",
			'utl' => '',
			'level' => 4),
		'measurands.php:actions' => array(
			'title' => __('(actions)'),
			'mapping' => "index.php:,templates.php:,measurands.php:",
			'utl' => '',
			'level' => 4),
		'measurands.php:save' => array(
			'title' => __('(save)'),
			'mapping' => "index.php:,templates.php:,measurands.php:",
			'utl' => '',
			'level' => 4),
		'variables.php' => array(
			'title' => __('Variables'),
			'mapping' => "index.php:,templates.php:",
			'utl' => 'variables.php',
			'level' => 3),
		'variables.php:add' => array(
			'title' => __('(add)'),
			'mapping' => "index.php:,templates.php:,variables.php:",
			'utl' => '',
			'level' => 4),
		'variables.php:edit' => array(
			'title' => __('(edit)'),
			'mapping' => "index.php:,templates.php:,variables.php:",
			'utl' => '',
			'level' => 4),
		'variables.php:actions' => array(
			'title' => __('(actions)'),
			'mapping' => "index.php:,templates.php:,variables.php:",
			'utl' => '',
			'level' => 4),
		'variables.php:save' => array(
			'title' => __('(save)'),
			'mapping' => "index.php:,templates.php:,variables.php:",
			'utl' => '',
			'level' => 4),
	);
	
	$nav = array_merge($nav,$temp_nav);
}

function reportit_config_insert() {
	global $config, $menu;
	
	$menu[__('Management')]['plugins/reportit/reports.php'] = __('Report Configurations');
	$menu[__('Templates')]['plugins/reportit/templates.php'] = __('Reports');

	include_once($config['base_path'] . '/plugins/reportit/include/constants.php');
}

function reportit_config_settings() {
	global $config, $settings, $settings_graph, $item_rows;
	
	$report_settings_general = array(
		'reportit_general_header' => array(
			'method' => "spacer",
			'friendly_name' => __('General')),
		'reportit_max_exectime' => array(
			'method' => "textbox",
			'friendly_name' => __('Maximum execution time'),
			'description' => __('Maximum execution time of one calculation'),
			'max_length' => 5,
			'default' => "300"),
		'reportit_max_rec_count_chg' => array(
			'method' => 'textbox',
			'friendly_name' => __('Maximum record count change'),
			'description' => __('Optional (Auto-Generate RRD List): Do not change RRD list of any report if record count change is greater than this number. This is to avoid unwanted and disastrous changes on RRD lists'),
			'max_length' => 5,
			'default' => "100"),
		'reportit_use_tmz' => array(
			'method' => 'checkbox',
			'friendly_name' => __('Time Zones'),
			'description' => __('Enable/disable the use of time zones for data item\'s configuration and report calculation. In the former case server time has to be set up to GMT/UTC!'),
			'default' => ''),
		'reportit_show_tmz' => array(
			'method' => 'checkbox',
			'friendly_name' => __('Show Time Zones'),
			'description' => __('Enable/disable to display server\'s timezone on the headlines.'),
			'default' => ''),
		'reportit_use_sipref' => array(
			'method' => 'checkbox',
			'friendly_name' => __('Use SI-prefix'),
			'description' => __('Enable/disable the use of correct SI-Prefixes for binary multiples under the terms of <a href=\'http://www.ieee.org\'>IEEE 1541</a> and <a href=\'http://www.iec.ch/zone/si/si_bytes.htm\'>IEC 60027-2</a>'),
			'default' => ''),
		'reportit_operators' => array(
			'method' => 'drop_array',
			'friendly_name' => __('Operator for Scheduled Reporting'),
			'description' => __('Choose the level which is necessary to configure all options of scheduled reporting in a report configuration.'),
			'array' => array('Power User (Report Owner)', 'Super User (Report Admin)'),
			'default' => 1)
	);
	$report_settings_rrdtool = array(
		'reportit_rrdtool_header' => array(
			'method' => 'spacer',
			'friendly_name' => __('RRDtool')),
		'reportit_rrdtool_api' => array(
			'method' => 'drop_array',
			'friendly_name' => __('RRDtool Connection'),
			'description' => __('Choose the way to connect to RRDtool.'),
			'array' => array('PHP BINDINGS (FAST)', 'RRDTOOL CACTI (SLOW)', 'RRDTOOL SERVER (SLOW)'),
			'default' => 1),
		'reportit_rrdtool_ip' => array(
			'method' => 'textbox',
			'friendly_name' => __('RRDtool Server IP'),
			'description' => __('Optional: Configured IP address of the RRDtool server.'),
			'max_length' => "253",
			'default' => '127.0.0.1'),
		'reportit_rrdtool_port' => array(
			'method' => 'textbox',
			'friendly_name' => __('RRDtool Server Port'),
			'description' => __('Optional: Configured port of the RRDtool server.'),
			'max_length' => "5",
			'default' => '13900'),
	);
	$report_settings_export = array(
		'reportit_export_header' => array(
			'method' => 'spacer',
			'friendly_name' => __('Export Settings')),
		'reportit_export_filename' => array(
			'method' => 'textbox',
			'friendly_name' => __('Filename Format'),
			'description' => __('The name format for the export files created on demand.'),
			'max_length' => 100,
			'default' => 'cacti_report_<report_id>'),
		'reportit_export_head' => array(
			'method' => 'textarea',
			'friendly_name' => __('Export Header'),
			'description' => __('The header description for export files.'),
			'textarea_rows' => 3,
			'textarea_cols' => 60,
			'default' => "# Your report header\n# <cacti_version> <reportit_version>"),
	);
	$report_settings_automation = array(
		'reportit_automation_header1' => array(
			'method' => 'spacer',
			'friendly_name' => __('Auto Archiving')),
		'reportit_archive' => array(
			'method' => 'checkbox',
			'friendly_name' => __('Enabled'),
			'description' => __('If enabled the result of every scheduled report will be archived automatically.'),
			'default' => ''),
		'reportit_archive_clc' => array(
			'method' => 'textbox',
			'friendly_name' => __('Cache Life Cyle (in seconds)'),
			'description' => __('Number of seconds an archived report will be cached without any hit.'),
			'max_length' => 4,
			'default' => '300'),
		'reportit_archive_path' => array(
			'method' => 'dirpath',
			'friendly_name' => __('Archive Path'),
			'description' => __('Optional: The path to an archive folder for saving. If not defined subfolder "archive" will be used.'),
			'max_length' => 255,
			'default' => $config['base_path'] . '/plugins/reportit/archive'),
		'reportit_automation_header2' => array(
			'method' => 'spacer',
			'friendly_name' => __('Auto Emailing')),
		'reportit_emailing' => array(
			'method' => 'checkbox',
			'friendly_name' => __('Enabled'),
			'description' => __('If enabled scheduled reports can be emailed automatically to a list of recipients.'),
			'default' => ''),
		'reportit_automation_header3' => array(
			'method' => 'spacer',
			'friendly_name' => __('Auto Exporting')),
		'reportit_export' => array(
			'method' => 'checkbox',
			'friendly_name' => __('Enabled'),
			'description' => __('If enabled scheduled reports can be exported automatically to a specified folder.<br/>Therefore a full structured path architecture will be used:<br/>Main Folder -> Template Folder (if defined) or Template ID -> Report ID -> Report'),
			'default' => ''),
		'reportit_export_dir' => array(
			'method' => 'dirpath',
			'friendly_name' => __('Export Path'),
			'description' => __('Optional: The main path to an export folder for saving the exports. If not defined subfolder "exports" will be used.'),
			'max_length' => 255,
			'default' => $config['base_path'] . '/plugins/reportit/exports'),
	);
	
	$report_users_settings_graph = array(
		'reportit_view_filter' => array(
			'method' => 'checkbox',
			'friendly_name' => __('Separate Report View filter'),
			'description' => __('Enable/disable the use of an individual filter per report'),
			'default' => 'on'),
		'reportit_max_rows' => array(
			'method' => 'drop_array',
			'friendly_name' => __('Rows Per Page'),
			'description' => __('The number of rows to display on a single page'),
			'array' => $item_rows,
			'default' => '30'),
		'reportit_csv_col_separator' => array(
			'method' => 'drop_array',
			'friendly_name' => __('CSV Column Separator'),
			'description' => __('The column seperator to be used for CSV exports'),
			'array' => array(",", ";", "Tab", "Blank"),
			'default' => '1'),
		'reportit_csv_decimal_separator' => array(
			'method' => 'drop_array',
			'friendly_name' => __('CSV Decimal Separator'),
			'description' => __('The symbol indicating the end of the integer part and the beginning of the fractional part.'),
			'array' => array(",", "."),
			'default' => '1'),
		'reportit_graph_default' => array(
			'method' => 'drop_array',
			'friendly_name' => __('Default Chart'),
			'description' => __('Define your default chart that should be shown first'),
			'array' => array('bar' => __('Bar (vertical)'),'horizontalBar' => __('Bar (horizontal)'), 'line' => __('Line'), 'pie' => __('Pie'), 'doughnut' => __('Doughnut'), 'polar' => __('Polar'), 'radar' => __('Radar')),
			'default' => 'pie'),
		'reportit_graph_height' => array(
			'method' => 'textbox',
			'friendly_name' => __('Graph Height'),
			'description' => __('The height of Reportit graphs in pixel.'),
			'max_length' => 4,
			'default' => '320'),
		'reportit_graph_width' => array(
			'method' => 'textbox',
			'friendly_name' => __('Graph Width'),
			'description' => __('The width of Reportit graphs in pixel.'),
			'max_length' => 4,
			'default' => '480'),
		'reportit_graph_grid' => array(
			'method' => 'checkbox',
			'friendly_name' => __('Show Graph Grid'),
			'description' => __('Enable/disable Graph Grid for Reportit Graphs'),
			'default' => ''),
	);
		
	
	//$settings['ReportIT'] = array_merge($report_settings_general,$report_settings_rrdtool,$report_settings_export,$report_settings_automation,$report_settings_graph);
	$settings_graph['ReportIT Settings'] = $report_users_settings_graph;
}

?>
