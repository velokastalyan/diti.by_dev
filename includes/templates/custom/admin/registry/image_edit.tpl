<? CForm::begin($_table); ?>
	<div class="alert alert-info">
		<h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>		

	<? if($id): ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('image'); ?>: <? echo $title; ?></h2>
	<? else: ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('new_image'); ?></h2>
	<? endif; ?>
	
	<div class="col">
		<div class="row">
			<label for="title"><strong><? CDeprecatedTemplate::loc_string('title'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'title', 'title', 'inp'); ?>
		</div>
		<div class="row">
			<label for="system_key"><strong><? CDeprecatedTemplate::loc_string('system_key'); ?>:</strong></label>
			<? if($id): ?>
				<? CDeprecatedTemplate::input('text', 'system_key', 'system_key', 'inp', true); ?>
			<? else: ?>
				<? CDeprecatedTemplate::input('text', 'system_key', 'system_key', 'inp'); ?>
			<? endif; ?>
		</div>
		<div class="row">
			<label for="path"><strong><? CDeprecatedTemplate::loc_string('path'); ?>:</strong></label>
			<? if($id): ?>
				<? CDeprecatedTemplate::input('text', 'path', 'path', 'inp', true); ?>
			<? else: ?>
				<? CDeprecatedTemplate::input('text', 'path', 'path', 'inp'); ?>
			<? endif; ?>
			<br /><i>e.g. pub/user/{user_id}/avatar/</i>
		</div>
		<div class="buttons">
			<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
			<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
			<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
		</div>
	</div>

<? if($id): ?>
	<? CControl::process('AdminFilters', 'image_sizes'); ?>
	<div class="top_buttons">
		<? if($remove_btn_show): ?>
			<? CDeprecatedTemplate::button('del_sel_obj_top', 'delete_selected_objects', CDeprecatedTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
		<? endif; ?>
		<? CDeprecatedTemplate::button('add_top', 'add_image_size', CDeprecatedTemplate::get_loc_string('add'), 'btn btn-small'); ?>
	</div>
	
	<? CControl::process('DBNavigator', 'image_size'); ?>
	
	<div class="bottom_buttons">
		<? if($remove_btn_show): ?>
			<? CDeprecatedTemplate::button('del_sel_obj_bot', 'delete_selected_objects', CDeprecatedTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
		<? endif; ?>
		<? CDeprecatedTemplate::button('add_bot', 'add_image_size', CDeprecatedTemplate::get_loc_string('add'), 'btn btn-small'); ?>
	</div>
<? endif; ?>
<? CForm::end(); ?>
