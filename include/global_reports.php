<?php

include_once($config['base_path'] . '/plugins/reportit/include/constants.php');

$reports_tab = array(
	'general' => __('General'),
	'presets' => __('Presets'),
	'email' => __('Email'),
	'admin' => __('Administration'),
);

$shift_time = array();

for($hour = 0;$hour<=23;$hour++) {
	for($minute = 0;$minute <=55;$minute+=5) {
		$shift_time[] = sprinf("%02d:%02d:00",$hour,$minute);
	}
}

$export_format = array(
	RT_EXPORT_NONE => '[None]',
	RT_EXPORT_CSV => 'CSV',
	RT_EXPORT_XML => 'XML'
);

$schedule_freq = array(
	RT_FREQ_DAILY => 'Daily',
    RT_FREQ_WEEKLY => 'Weekly',
	RT_FREQ_MONTHLY => 'Monthly',
    RT_FREQ_QUATERLY => 'Quaterly',
    RT_FREQ_YEARLY => 'Yearly',
);

$export_meth = array(
	RT_EXPORT_METH_FILE => 'File',
	#RT_EXPORT_METH_FTP => 'FTP',
	#RT_EXPORT_METH_SCP => 'SCP',
);

$reports_tab_general = array(
	'id' => array(
		'method' => 'hidden_zero',
		'value' => '|arg1:id|'),
	'tab' => array(
		'method' => 'hidden',
		'value' => 'general'),
	'general_spacer1' => array(
		'method' => 'spacer',
		'friendly_name' => __('General settings for report')),
	'name' => array(
		'method' => 'textbox',
		'friendly_name' => __('Name'),
		'description' => __('Name of the report'),
		'max_length' => 255,
		'default' => '',
		'value' => '|arg1:name|'),
	'template' => array(
		'method' => 'custom',
		'friendly_name' => __('Template'),
		'description' => __('The template your configuration depends on'),
		'value' => db_fetch_cell("SELECT name FROM reportit_templates WHERE id = '|arg1:template_id|'")),
	'public' => array(
		'method' => 'checkbox'
		'friendly_name' => __('Public'),
		'description' => __('If enabled everyone can see your report under he reports tab '),
		'default' => 0,
		'value' => '|arg1:public|'),
	'general_spacer2' => array(
		'method' => 'spacer',
		'friendly_name' => __('Reporting Period')),
	'period_sliding' => array(
		'method' => 'checkbox',
		'friendly_name' => __('Sliding Time Frame'),
		'description' => _('If checked the reporting period will be configured automatically in relation to the point of time the calculation starts.'),
		'default' => 0,
		'value' => '|arg1:period_sliding|'),
);

$reports_tab_email = array(
	'id' => array(
		'method' => 'hidden_zero',
		'value' => '|arg1:id|'),
	'tab' => array(
		'method' => 'hidden',
		'value' => 'email'),
	'email_spacer1' => array(
		'method' => 'spacer',
		'friendly_name' => __('Email general')),
	'email' => array(
		'method' => 'checkbox',
		'friendly_name' => __('Enable'),
		'description' => __('Enable/disable email reporting.'),
		'default' => '0',
		'value' => '|arg1:email|'),
	'email_subject' => array(
		'method' => 'textbox',
		'friendly_name' => __('Subject'),
		'description' => __('Enter the subject of your email.<br/>Following variables will be supported (without quotes): \'|title|\' and \'|period|\'.'),
		'max_length' => 255,
		'default' => 'Scheduled report - |title| - |period|',
		'value' => '|arg1:email_subject|'),
	'email_body' => array(
		'method' => 'textarea',
		'friendly_name' => __('Body (optional)'),
		'description' => __('Enter a message which will be displayed in the body of your email'),
		'textarea_rows' => 4
		'textarea_cols' => 80,
		'default' => 'This is a scheduled report generated from Cacti.',
		'value' => '|arg1:email_body|'),
	'email_format' => array(
		'method' => 'drop_array',
		'friendly_name' => __('Attachment'),
		'description' => __('To only receive a mail that a report is available, choose \'None\'. Otherwise, the selected format will be attached to the message'),
		'array' => $export_format,
		'default' => RT_EXPORT_CSV,
		'value' => '|arg1:email_format|'),
	'email_spacer2' => array(
		'method' => 'spacer',
		'friendly_name' => __('Email recipients')),
	'email_recipient' => array(
		'method' => 'textarea',
		'friendly_name' => __('New Email Recipients'),
		'description' => __('Enter a comma separated list of Email addresses for this notification list.'),
		'textarea_rows' => 4,
		'textarea_cols' => 80,
		'default' => '',
		'value' => '|arg1:email_recipient|'),
);
		
$reports_tab_scheduling = array(
	'id' => array(
		'method' => 'hidden_zero',
		'value' => '|arg1:id|'),
	'tab' => array(
		'method' => 'hidden',
		'value' => 'scheduling'),
	'reports_sched_spacer1' => array(
		'method' => 'spacer',
		'friendly_name' => __('Scheduled Reporting')),
	'reports_scheduled' => array(
		'method' => 'checkbox',
		'friendly_name' => __('Enable'),
		'description' => __('Enable/disable scheduled reporting. Sliding time frame should be enabled.'),
		'default' => '0',
		'value' => '|arg1:scheduled|'),
	'reports_schedule_frequency' => array(
		'method' => 'drop_array',
		'friendly_name' => __('Frequency'),
		'description' => __('Select the frequency for processing this report. Be sure that there\'s a cronjob (or scheduled task) running for the choice you made. This won\'t be done automatically by ReportIt.'),
		'array' => $schedule_freq,
		'default' => RT_FREQ_WEEKLY,
		'value' => '|arg1:scheduled_frequency|'),
	'report_schedule_autorrdlist' => array(
		'method' => 'checkbox',
		'friendly_name' => __('Auto Generated Data Items'),
		'description' => __('Enable/disable automatic creation of all data items based on given filters.This will be called before report execution. Obsolete RRDs will be deleted and all RRDs matching the filter settings will be added.'),
		'default' => '0',
		'value' => '|arg1:scheduled_autorrdlist|'),
);

$reports_tab_export  = array(
	'id' => array(
		'method' => 'hidden_zero',
		'value' => '|arg1:id|'),
	'tab' => array(
		'method' => 'hidden',
		'value' => 'export'),
	'report_export_spacer1' => array(
		'method' => 'spacer',
		'friendly_name' => __('Report Exporting')),
	'report_exporting' => array(
		'method' => 'checkbox',
		'friendly_name' => __('Enabled'),
		'description' => __('If enabled, reports can be exported automatically to a specified folder.'),
		'default' => 0,
		'value' => '|arg1:exporting|'),
	'report_export_methode' => array(
		'method' => 'drop_array',
		'friendly_name' => __('Method'),
		'description' => __('Method used to export the report to the specified directory'),
		'array' => $export_meth,
		'default' => 0,
		'value' => '|arg1:export_methode|'),
	'report_export_directory' => array(
		'method' => 'textbox',
		'friendly_name' => __('Directory'),
		'description' => __('Directory where export the report. If export method is File, we check the reachability of the directory. In other case, not'),
		'max_length' => 255,
		'default' => '',
		'value' => '|arg1:export_directory|'),
);

?>