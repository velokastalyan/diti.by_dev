<? CForm::begin($_table); ?>
	<div class="alert alert-info">
		<h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>	

	<? if($id): ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('language'); ?>: <? echo $title; ?></h2>
	<? else: ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('new_language'); ?></h2>
	<? endif; ?>
	
	<div class="col">
		<div class="row">
			<label for="title"><strong><? CDeprecatedTemplate::loc_string('title'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'title', 'title', 'inp'); ?>
		</div>
		<div class="row">
			<label for="abbreviation"><strong><? CDeprecatedTemplate::loc_string('abbreviation'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'abbreviation', 'abbreviation', 'inp inp1'); ?>
		</div>
		<div class="buttons">
			<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
			<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
			<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
		</div>
	</div>
<? CForm::end(); ?>
