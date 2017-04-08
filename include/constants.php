<?php
	
define('RT_PERIOD_LAST_TODAY',0);
define('RT_PERIOD_LAST_1D',1);
define('RT_PERIOD_LAST_2D',2);
define('RT_PERIOD_LAST_3D',3);
define('RT_PERIOD_LAST_4D',4);
define('RT_PERIOD_LAST_5D',5);
define('RT_PERIOD_LAST_6D',6);
define('RT_PERIOD_LAST_7D',7);
define('RT_PERIOD_LAST_1W',8);
define('RT_PERIOD_LAST_2W',9);
define('RT_PERIOD_LAST_3W',10);
define('RT_PERIOD_LAST_1M',11);
define('RT_PERIOD_LAST_2M',12);
define('RT_PERIOD_LAST_3M',13);
define('RT_PERIOD_LAST_4M',14);
define('RT_PERIOD_LAST_5M',15);
define('RT_PERIOD_LAST_6M',16);
define('RT_PERIOD_LAST_1Y',17);
define('RT_PERIOD_LAST_2Y',18);

define('RT_DAYS_MON',1);
define('RT_DAYS_TUE',2);
define('RT_DAYS_WED',3);
define('RT_DAYS_THU',4);
define('RT_DAYS_FRI',5);
define('RT_DAYS_SAT',6);
define('RT_DAYS_SUN',7);

define('RT_FREQ_DAILY',0);
define('RT_FREQ_WEEKLY',1);
define('RT_FREQ_MONTHLY',2);
define('RT_FREQ_QUATERLY',3);
define('RT_FREQ_YEARLY',4);

define('RT_EXPORT_NONE',0);
define('RT_EXPORT_CSV',1);
define('RT_EXPORT_XML',2);

define('RT_EXPORT_METH_FILE',0);
#define('RT_EXPORT_METH_FTP',1); // TODO
#define('RT_EXPORT_METH_SCP',2); // TODO

define('RT_TEMPLATE_MEAS_TYPE_BINARY',0);
define('RT_TEMPLATE_MEAS_TYPE_FLOATING',1);
define('RT_TEMPLATE_MEAS_TYPE_INTEGER',2);
define('RT_TEMPLATE_MEAS_TYPE_UINTEGER',3);
define('RT_TEMPLATE_MEAS_TYPE_HEXA_LC',4);
define('RT_TEMPLATE_MEAS_TYPE_HEXA_UC',5);
define('RT_TEMPLATE_MEAS_TYPE_OCTAL',6);
define('RT_TEMPLATE_MEAS_TYPE_SCIENTIFIC',7);

define('RT_TEMPLATE_MEAS_PREFIX_NONE', 0);
define('RT_TEMPLATE_MEAS_PREFIX_DECIMAL', 1);
define('RT_TEMPLATE_MEAS_PREFIX_BINARY', 2);

define('RT_TEMPLATE_VAR_TYPE_DROPDOWN',0);
define('RT_TEMPLATE_VAR_TYPE_INPUTFIELD',1);

define('RT_DATA_FILTER_LIKE',0);
define('RT_DATA_FILTER_REGX',1);

?>
