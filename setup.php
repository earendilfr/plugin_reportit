<?php

function plugin_reportit_install() {
	// Register Hooks
    api_plugin_register_hook('reportit', 'top_header_tabs', 'reportit_show_tab', 'include/tab.php');
    api_plugin_register_hook('reportit', 'top_graph_header_tabs', 'reportit_show_tab', 'include/tab.php');
    api_plugin_register_hook('reportit', 'draw_navigation_text', 'reportit_draw_navigation_text', 'include/settings.php');
    api_plugin_register_hook('reportit', 'config_insert', 'reportit_config_insert', 'include/settings.php');
	api_plugin_register_hook('reportit', 'config_arrays', 'reportit_config_arrays', 'setup.php');
    api_plugin_register_hook('reportit', 'config_settings', 'reportit_config_settings', 'include/settings.php');
    api_plugin_register_hook('reportit', 'poller_bottom', 'reportit_poller_bottom', 'setup.php');
	
	// Register realms
	api_plugin_register_realm('reportit', 'view.php', 'Plugin -> ReportIt: view', 1);
    api_plugin_register_realm('reportit', 'reports.php,rrdlist.php,items.php,reportit_run.php', 'Plugin -> ReportIt: create', 1);
    api_plugin_register_realm('reportit', 'templates.php,measurands.php,variables.php', 'Plugin -> ReportIt: administrate', 1);
	
	reportit_setup_database();
}

function plugin_reportit_uninstall() {
	global $config, $database_default;
	$result = db_fetch_assoc("SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA = '$database_default' AND TABLE_NAME LIKE 'reportit%'");
	foreach($result as $index => $arr) {
		db_execute("DROP TABLE IF EXISTS ".$arr['TABLE_NAME']);
	}
	db_execute("DELETE FROM settings WHERE name LIKE 'reportit%'");
	db_execute("DELETE FROM settings_user WHERE name LIKE 'reportit%'");
	db_execute("DELETE FROM settings_user_group WHERE name LIKE 'reportit%'");
}

function plugin_reportit_version() {
	global $config;
	$info = parse_ini_file($config['base_path'] . '/plugins/reportit/INFO', true);
	return $info['info'];
}

function reportit_setup_database($upgrade = FALSE) {
	global $config, $database_default;
	
	include_once($config['base_path'] . '/plugins/reportit/include/constants.php');
	
	$result = db_fetch_assoc("SELECT TABLE_NAME FROM information_schema.tables WHERE TABLE_SCHEMA = '$database_default' AND TABLE_NAME LIKE 'reportit%'");
	$tables = array();
	foreach($result as $index => $arr) {
		$tables[] = $arr['TABLE_NAME'];
	}
	
	$sql_create_tables = array();
	
	# Create table for report template
	if (in_array('reportit_templates',$tables)) {
		db_execute("DROP TABLE IF EXISTS reportit_templates");
	}
	api_plugin_db_table_create('reportit','reportit_templates',array(
			'columns' => array(
				array('name' => 'id', 'type' => 'mediumint(8)', 'NULL' => false, 'auto_increment' => true),
				array('name' => 'name', 'type' => 'varchar(255)', 'NULL' => false, 'default' => ''),
				array('name' => 'pre_filter', 'type' => 'varchar(255)', 'NULL' => false, 'default' => ''),
				array('name' => 'data_template_id', 'type' => 'mediumint(8)', 'NULL' => false, 'default' => 0),
				array('name' => 'locked', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 1),
			),
			'primary' => 'id',
			'unique_keys' => array(
				array('name' => 'name', 'columns' => 'name'),
			),
			'type' => 'InnoDB',
		));
	
	# Create table for templates measurands
	if (in_array('reportit_templates_measurands', $tables)) {
		db_execute("DROP TABLE IF EXISTS reportit_templates_measurands");
	}
	api_plugin_db_table_create('reportit','reportit_templates_measurands', array(
			'columns' => array(
				array('name' => 'id', 'type' => 'mediumint(8)', 'NULL' => false, 'auto_increment' => true),
				array('name' => 'name', 'type' => 'varchar(255)', 'NULL', false, 'default' => ''),
				array('name' => 'template_id', 'type' => 'mediumint(8)', 'NULL' => false, 'default' => 0),
				array('name' => 'abbreviation', 'type' => 'varchar(10)', 'NULL' => false, 'default' => ''),
				array('name' => 'unit', 'type' => 'varchar(10)', 'NULL' => false, 'default' => ''),
				array('name' => 'consolidation_function_id', 'type' => 'tinyint(2)', 'NULL' => false, 'default' => 0),
				array('name' => 'visible', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 1),
				array('name' => 'separate', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'data_type', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_TEMPLATE_MEAS_TYPE_FLOATING),
				array('name' => 'data_precision', 'type' => 'tinyint(1) signed', 'NULL' => false, 'default' => 2),
				array('name' => 'data_prefix', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_TEMPLATE_MEAS_PREFIX_DECIMAL),
				array('name' => 'calc_formula', 'type' => 'varchar(500)', 'NULL' => false, 'default' => ''),
			),
			'primary' => 'id', 
			'unique_keys' => array(
				array('name' => 'name', 'columns' => 'name'),
				array('name' => 'abbreviation', 'columns' => 'abbreviation'),
			),
			'type' => 'InnoDB',
		));
	
	# Create table for tempates variables
	if (in_array('reportit_templates_variables', $tables)) {
		db_execute("DROP TABLE IF EXISTS reportit_templates_variables");
	}
	api_plugin_db_table_create('reportit','reportit_templates_variables', array(
			'columns' => array(
				array('name' => 'id', 'type' => 'mediumint(8)', 'NULL' => false, 'auto_increment' => true),
				array('name' => 'name', 'type' => 'varchar(20)', 'NULL', false, 'default' => ''),
				array('name' => 'template_id', 'type' => 'mediumint(8)', 'NULL' => false, 'default' => 0),
				array('name' => 'internal_name', 'type' => 'varchar(255)', 'NULL' => false, 'default' => ''),
				array('name' => 'description', 'type' => 'varchar(255)', 'NULL' => false, 'default' => ''),
				array('name' => 'unit', 'type' => 'varchar(10)', 'NULL' => false, 'default' => ''),
				array('name' => 'max_value', 'type' => 'float', 'NULL' => false, 'default' => 0),
				array('name' => 'min_value', 'type' => 'float', 'NULL' => false, 'default' => 0),
				array('name' => 'def_value', 'type' => 'float', 'NULL' => false, 'default' => 0),
				array('name' => 'input_type', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_TEMPLATE_VAR_TYPE_DROPDOWN),
			),
			'primary' => 'id',
			'unique_keys' => array(
				array('name' => 'name', 'columns' => 'name'),
				array('name' => 'internal_name', 'columns' => 'internal_name'),
			),
			'type' => 'InnoDB',
		));
	
	# Create table for report
	if (in_array('reportit_reports',$tables)) {
		db_execute("DROP TABLE IF EXISTS reportit_reports");
	}
	api_plugin_db_table_create('reportit','reportit_reports', array(
			'columns' => array(
				array('name' => 'id', 'type' => 'mediumint(8)', 'NULL' => false, 'auto_increment' => true),
				array('name' => 'name', 'type' => 'varchar(255)', 'NULL', false, 'default' => ''),
				array('name' => 'running', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'last_run', 'type' => 'datetime', 'NULL' => false, 'default' => '0000-00-00 00:00:00'),
				array('name' => 'template_id', 'type' => 'mediumint(8)', 'NULL' => false, 'default' => 0),
				array('name' => 'user_auth_id', 'type' => 'mediumint(8)', 'NULL' => false, 'default' => 0),
				array('name' => 'public', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'period_sliding', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'period_timeframe', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_PERIOD_LAST_1D),
				array('name' => 'period_upto', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'period_start_date', 'type' => 'date', 'NULL' => false, 'default' => '0000-00-00'),
				array('name' => 'period_end_date', 'type' => 'date', 'NULL' => false, 'default' => '0000-00-00'),
				array('name' => 'scheduled', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'sched_frequency', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_FREQ_WEEKLY),
				array('name' => 'sched_auto_rrd', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'export_format', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_EXPORT_NONE),
				array('name' => 'export_method', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_EXPORT_METH_FILE),
				array('name' => 'export_directory', 'type' => 'varchar(255)', 'NULL' => false, 'default' => ''),
				array('name' => 'export_filename', 'type' => 'varchar(255)', 'NULL' => false, 'default' => ''),
				array('name' => 'email', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'email_subject', 'type' => 'varchar(255)', 'NULL' => false, 'default' => ''),
				array('name' => 'email_body', 'type' => 'text', 'NULL' => false, 'default' => ''),
				array('name' => 'email_format', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_EXPORT_NONE),
				array('name' => 'email_recipient', 'type' => 'varchar(255)', 'NULL' => false, 'default' > ''),
				array('name' => 'data_subhead', 'type' => 'text', 'NULL' => false, 'default' => ''),
				array('name' => 'data_host_template_id', 'type' => 'mediumint(8)', 'NULL' => false, 'default' => 0),
				array('name' => 'data_host_up', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => 0),
				array('name' => 'data_filter_type', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_DATA_FILTER_LIKE),
				array('name' => 'data_filter', 'type' => 'varchar(500)', 'NULL' => false, 'default' => ''),
				array('name' => 'data_work_day_from', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_DAYS_MON),
				array('name' => 'data_work_day_to', 'type' => 'tinyint(1)', 'NULL' => false, 'default' => RT_DAYS_SUN),
				array('name' => 'data_work_hour_from', 'type' => 'time', 'NULL' => false, 'default' => '00:00:00'),
				array('name' => 'data_work_hour_to', 'type' => 'time', 'NULL' => false, 'default' => '24:00:00'),
			),
			'primary' => 'id',
			'unique_keys' => array(
				array('name' => 'name', 'columns' => 'name'),
			),
			'type' => 'InnoDb',
		));
				
	# Create table for report items
	if (in_array('reportit_report_items',$tables)) {
		db_execute("DROP TABLE IF EXISTS reportit_report_items");
	}
	api_plugin_db_table_create('reportit','reportit_report_items', array(
			'columns' => array(
				array('name' => 'local_data_id', 'type' => 'mediumint(8)', 'NULL' => false, 'default' => 0),
				array('name' => 'report_id', 'type' => 'mediumint(8)', 'NULL' => false, 'default' => 0),
			),
			'primary' => 'local_data_id`,`report_id',
			'keys' => array(
				array('name' => 'report_id', 'columns' => 'report_id'),
				array('name' => 'local_data_id', 'columns' => 'local_data_id'),
			),
			'type' => 'InnoDb',
		));
}

?>
