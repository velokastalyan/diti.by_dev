<? CForm::begin($_table); ?>
	
	<div class="alert alert-info">
		<h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>	

	<? if($id): ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('path'); ?>: <? echo $path_title; ?> -> <? CDeprecatedTemplate::loc_string('value'); ?>: <? echo $title; ?></h2>
	<? else: ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('path'); ?>: <? echo $path_title; ?> -> <? CDeprecatedTemplate::loc_string('new_value'); ?></h2>
	<? endif; ?>
	
	<div class="col left_block">
		<div class="row">
			<label for="path_id"><strong><? CDeprecatedTemplate::loc_string('path_id'); ?>:</strong></label>
			<? CTemplate::select('path_id', 'path_id', 'sel admin-sel'); ?>
		</div>
		<div class="row">
			<label for="type"><strong><? CDeprecatedTemplate::loc_string('type'); ?>:</strong></label>
			<? CTemplate::select('type', 'type', 'sel admin-sel'); ?>
		</div>
		<div class="row">
			<label for="path"><strong><? CDeprecatedTemplate::loc_string('path'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'path', 'path', 'inp'); ?>
		</div>
		<div class="row">
			<label for="title"><strong><? CDeprecatedTemplate::loc_string('title'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'title', 'title', 'inp'); ?>
		</div>
		<div class="row">
			<label for="required"><? CDeprecatedTemplate::loc_string('required'); ?>:</label>
			<? CDeprecatedTemplate::input('checkbox', 'required', 'required', 'chk'); ?>
		</div>
		<div class="row">
			<label for="value"><? CDeprecatedTemplate::loc_string('value'); ?>:</label>
			<? CDeprecatedTemplate::textarea('value', 'value', 'inp'); ?>
		</div>
		<div class="buttons">
			<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
			<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
			<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
		</div>
	</div>
	<div class="col">
		<div class="row">
			<label for="valid_type"><? CDeprecatedTemplate::loc_string('valid_type'); ?>:</label>
			<? CTemplate::select('valid_type', 'valid_type', 'sel admin-sel'); ?>
		</div>
		<div class="row">
			<label for="unique_error_mess"><? CDeprecatedTemplate::loc_string('unique_error_mess'); ?>:</label>
			<? CDeprecatedTemplate::input('checkbox', 'unique_error_mess', 'unique_error_mess', 'chk'); ?>
		</div>
		<div class="row">
			<label for="valid_add_info1"><? CDeprecatedTemplate::loc_string('valid_add_info1'); ?>:</label>
			<? CDeprecatedTemplate::textarea('valid_add_info1', 'valid_add_info1', 'inp'); ?>
		</div>
		<div class="row">
			<label for="image_width"><? CDeprecatedTemplate::loc_string('valid_add_info2'); ?>:</label>
			<? CDeprecatedTemplate::textarea('valid_add_info2', 'valid_add_info2', 'inp'); ?>
		</div>
		<div class="row">
			<label for="valid_add_info3"><? CDeprecatedTemplate::loc_string('valid_add_info3'); ?>:</label>
			<? CDeprecatedTemplate::textarea('valid_add_info3', 'valid_add_info3', 'inp'); ?>
		</div>
	</div>
	<div class="clear">&nbsp;</div>

<? CForm::end(); ?>

