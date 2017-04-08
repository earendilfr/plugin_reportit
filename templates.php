<?php

chdir("../..");

include_once('./include/auth.php');
include_once('./plugins/reportit/include/global_templates.php');

switch (get_request_var('action')) {
	case 'template_actions':
		template_actions();
		break;
	case 'template_edit':
		top_header();
		template_edit();
		bottom_footer();
		break;
	case 'template_save':
		template_save();
		break;
	default:
		top_header();
		template_display();
		bottom_footer();
		break;
}

function template_actions() {
	// TODO
}

function template_edit() {
	// TODO
}

function template_save() {
	// TODO
}

function template_display() {
	global $template_actions, $item_rows;
	
	/* ================= input validation and session storage ================= */
	$filters = array(
		'rows' => array(
			'filter' => FILTER_VALIDATE_INT, 
			'pageset' => true,
			'default' => '-1'
			),
		'page' => array(
			'filter' => FILTER_VALIDATE_INT, 
			'default' => '1'
			),
		'filter' => array(
			'filter' => FILTER_CALLBACK, 
			'pageset' => true,
			'default' => '', 
			'options' => array('options' => 'sanitize_search_string')
			),
		'sort_column' => array(
			'filter' => FILTER_CALLBACK, 
			'default' => 'name', 
			'options' => array('options' => 'sanitize_search_string')
			),
		'sort_direction' => array(
			'filter' => FILTER_CALLBACK, 
			'default' => 'ASC', 
			'options' => array('options' => 'sanitize_search_string')
			),
	);

	validate_store_request_vars($filters, 'sess_gt');
	/* ================= input validation ================= */

	if (get_request_var('rows') == '-1') {
		$rows = read_config_option('num_rows_table');
	}else{
		$rows = get_request_var('rows');
	}
	html_start_box(__('Reports Templates'), '100%', '', '3', 'center', 'plugins/reportit/templates.php?action=template_edit');

	?>
	<tr class='even noprint'>
		<td>
		<form id='form_graph_template' action='graph_templates.php'>
			<table class='filterTable'>
				<tr>
					<td>
						<?php print __('Search');?>
					</td>
					<td>
						<input id='filter' type='text' name='filter' size='25' value='<?php print htmlspecialchars(get_request_var('filter'));?>'>
					</td>
					<td class='nowrap'>
						<?php print __('Templates');?>
					</td>
					<td>
						<select id='rows' name='rows' onChange='applyFilter()'>
							<option value='-1'<?php print (get_request_var('rows') == '-1' ? ' selected>':'>') . __('Default');?></option>
							<?php
							if (sizeof($item_rows) > 0) {
								foreach ($item_rows as $key => $value) {
									print "<option value='" . $key . "'"; if (get_request_var('rows') == $key) { print ' selected'; } print '>' . htmlspecialchars($value) . "</option>\n";
								}
							}
							?>
						</select>
					</td>
					<td>
						<input type='button' id='refresh' value='<?php print __('Go');?>' title='<?php print __('Set/Refresh Filters');?>'>
					</td>
					<td>
						<input type='button' id='clear' value='<?php print __('Clear');?>' title='<?php print __('Clear Filters');?>'>
					</td>
				</tr>
			</table>
			<input type='hidden' id='page' name='page' value='<?php print get_request_var('page');?>'>
		</form>
		<script type='text/javascript'>
		var disabled = true;

		function applyFilter() {
			strURL = 'graph_templates.php?filter='+$('#filter').val()+'&rows='+$('#rows').val()+'&page='+$('#page').val()+'&has_graphs='+$('#has_graphs').is(':checked')+'&header=false';
			loadPageNoHeader(strURL);
		}

		function clearFilter() {
			strURL = 'graph_templates.php?clear=1&header=false';
			loadPageNoHeader(strURL);
		}

		$(function() {
			$('#refresh').click(function() {
				applyFilter();
			});
			
			$('#clear').click(function() {
				clearFilter();
			});
	
			$('#form_graph_template').submit(function(event) {
				event.preventDefault();
				applyFilter();
			});
		});
		</script>
		</td>
	</tr>
	<?php

	html_end_box();
	
	/* form the 'where' clause for our main sql query */
	if (get_request_var('filter') != '') {
		$sql_where = "WHERE (rt.name LIKE '%" . get_request_var('filter') . "%')";
	}else{
		$sql_where = '';
	}
	
	$total_rows = db_fetch_cell("SELECT COUNT(*) FROM reportit_templates $sql_where GROUP BY id");
	$template_list = db_fetch_assoc("SELECT rt.name, rt.locked, dt.name AS data_template FROM reportit_templates AS rt LEFT JOIN data_template AS dt ON (rt.data_template_id = dt.id) $sql_where GROUP BY rt.id");
	
	$nav = html_nav_bar('plugins/reportit/templates.php?filter=' . get_request_var('filter'), MAX_DISPLAY_PAGES, get_request_var('page'), $rows, $total_rows, 8, __('Reports Templates'), 'page', 'main');
	
	form_start('templates.php', 'chk');
	print $nav;
	html_start_box('', '100%', '', '3', 'center', '');
	
	$display_text = array(
		'name'		=> array('display' => __('Report Template Name'), 'align' => 'left', 'sort' => 'ASC', 'tip' => __('The name of this Report Template.')),
		'id'		=> array('display' => __('ID'), 'align' => 'right', 'sort' => 'ASC', 'tip' => __('The internal ID for this Report Template.  Useful when performing automation or debugging.')),
		'locked'	=> array('display' => __('Locked'), 'align' => 'left', 'sort' => 'ASC', 'tip' => __('Indicate that this Report Template is locked.')),
		'data_template' => array('display' => __('Data Template'), 'align' => 'left', 'sort' => 'ASC', 'tip' => __('Name of the Data Template used by this template.')),
	);
	
	html_header_sort_checkbox($display_text, get_request_var('sort_column'), get_request_var('sort_direction'), false);
	
	$i = 0;
	if (sizeof($template_list)) {
		foreach ($template_list as $template) {
			if ($template['locked']) {
				$disabled = true;
			} else {
				$disabled = false;
			}
			form_alternate_row('line' . $template['id'], true, $disabled);
			form_selectable_cell(filter_value($template['name'], get_request_var('filter'), 'plugins/reportit/templates.php?action=template_edit&id=' . $template['id']), $template['id']);
			form_selectable_cell($template['id'], $template['id'], '', 'text-align:right');
			form_selectable_cell($template['locked'], $template['id'], '', 'text-align:right');
			form_selectable_cell($template['data_template'], $template['id'], '', 'text-align:right');
			form_checkbox_cell($template['name'], $template['id']);
			form_end_row();
		}
	} else {
		print "<tr class='tableRow'><td colspan='4'><em>" . __('No Reports Templates Found') . "</em></td></tr>\n";
	}
	
	html_end_box(false);

	if (sizeof($template_list)) {
		print $nav;
	}
}


?>
