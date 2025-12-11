<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
	<div class="alert alert-info">
		<h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>	

	<? if($id): ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('localizer_string'); ?>: <? echo $name; ?></h2>
	<? else: ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('new_localizer_string'); ?></h2>
	<? endif; ?>
	
	<div class="col">
		<div class="row">
			<label for="name"><strong><? CDeprecatedTemplate::loc_string('name'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'name', 'name', 'inp'); ?>
		</div>
		<div class="row">
			<label for="value"><? CDeprecatedTemplate::loc_string('value'); ?>:</label>
			<? CDeprecatedTemplate::input('text', 'value', 'value', 'inp'); ?>
		</div>
		<div class="buttons">
			<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
			<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
			<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
		</div>
	</div>
<? CForm::end(); ?>
