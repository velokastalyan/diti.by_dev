<? CForm::begin('registry_path'); ?>

	<div class="alert alert-info">
		<h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>	

	<? if($id): ?>
		<h2><? CDeprecatedTemplate::loc_string('path'); ?>: <? echo $title; ?></h2>
	<? else: ?>
		<h2><? CDeprecatedTemplate::loc_string('new_path'); ?></h2>
	<? endif; ?>
	
	<script type="text/javascript" language="javascript" src="<? echo $JS; ?>jquery/jquery.cookie.js"></script>
	
	<ul class="nav nav-tabs" id="tabs">
		<li class="active"><a href="#tab-1"><? CDeprecatedTemplate::loc_string('path'); ?></a></li>
		<? if($id): ?>
			<li><a href="#tab-2"><? CDeprecatedTemplate::loc_string('path_fields'); ?></a></li>
		<? endif; ?>
	</ul>
	
	<div class="tab-content">
		<div class="tab-pane active" id="tab-1">
			<div class="col">
				<div class="row">
					<label for="language_id"><strong><? CDeprecatedTemplate::loc_string('language_id'); ?>:</strong></label>
					<? CTemplate::select('language_id', 'language_id', 'sel admin-sel'); ?>
				</div>
				<div class="row">
					<label for="parent_id"><? CDeprecatedTemplate::loc_string('parent_id'); ?>:</label>
					<? CTemplate::select('parent_id', 'parent_id', 'sel admin-sel'); ?>
				</div>
				<div class="row">
					<label for="path"><strong><? CDeprecatedTemplate::loc_string('path'); ?>:</strong></label>
					<? CDeprecatedTemplate::input('text', 'path', 'path', 'inp'); ?>
				</div>
				<div class="row">
					<label for="title"><strong><? CDeprecatedTemplate::loc_string('title'); ?>:</strong></label>
					<? CDeprecatedTemplate::input('text', 'title', 'title', 'inp'); ?>
				</div>
			</div>
		</div>
		<? if($id): ?>
			<div class="tab-pane" id="tab-2">
				<? CControl::process('AdminFilters', 'registry_value'); ?>
				<div class="top_buttons">
					<? if($remove_btn_show): ?>
						<? CDeprecatedTemplate::button('del_sel_obj_top', 'delete_selected_objects', CDeprecatedTemplate::get_loc_string('delete_selected'), 'btn btn-small'); ?>
					<? endif; ?>
					<? CDeprecatedTemplate::button('add_top', 'add_registry_value', CDeprecatedTemplate::get_loc_string('add'), 'btn btn-small'); ?>
				</div>
				
				<? CControl::process('DBNavigator', 'registry_value'); ?>
				
				<div class="bottom_buttons">
					<? if($remove_btn_show): ?>
						<? CDeprecatedTemplate::button('del_sel_obj_bot', 'delete_selected_objects', CDeprecatedTemplate::get_loc_string('delete_selected'), 'btn btn-small'); ?>
					<? endif; ?>
					<? CDeprecatedTemplate::button('add_bot', 'add_registry_value', CDeprecatedTemplate::get_loc_string('add'), 'btn btn-small'); ?>
				</div>
			</div>
		<? endif; ?>
	</div>
	<div class="buttons">
		<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
	</div>
	
<? CForm::end(); ?>

