<? CForm::begin($_table); ?>
	<div class="alert alert-info">
		<h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>

	<? if($id): ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('user_role'); ?>: <? echo $title; ?></h2>
	<? else: ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('new_user_role'); ?></h2>
	<? endif; ?>
	
	<div class="col">
		<div class="row">
			<label for="id"><strong><? CDeprecatedTemplate::loc_string('id'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', '_id', '_id', 'inp'); ?>
		</div>
		<div class="row">
			<label for="title"><strong><? CDeprecatedTemplate::loc_string('title'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'title', 'title', 'inp'); ?>
		</div>
		<div class="row">
			<label for="description"><? CDeprecatedTemplate::loc_string('description'); ?>:</label>
			<? CDeprecatedTemplate::input('text', 'description', 'description', 'inp'); ?>
		</div>
		<div class="buttons">
			<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
			<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
			<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
		</div>
	</div>

<? CForm::end(); ?>
