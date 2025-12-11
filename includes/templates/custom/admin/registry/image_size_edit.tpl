<? CForm::begin($_table); ?>
	
	<div class="alert alert-info">
		<h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>	

	<? if($id): ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('image_size'); ?>: <? echo $image_width; ?>x<? echo $image_height; ?></h2>
	<? else: ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('new_image_size'); ?></h2>
	<? endif; ?>
	
	<div class="col">
		<div class="row">
			<label for="name"><strong><? CDeprecatedTemplate::loc_string('system_key'); ?>:</strong></label>
			<? if($id): ?>
				<? CDeprecatedTemplate::input('text', 'system_key', 'system_key', 'inp', true); ?>
			<? else: ?>
				<? CDeprecatedTemplate::input('text', 'system_key', 'system_key', 'inp'); ?>
			<? endif; ?>
		</div>
		<div class="row">
			<label for="image_width"><? CDeprecatedTemplate::loc_string('image_width'); ?>:</label>
			<? CDeprecatedTemplate::input('text', 'image_width', 'image_width', 'inp inp1'); ?>
		</div>
		<div class="row">
			<label for="image_height"><? CDeprecatedTemplate::loc_string('image_height'); ?>:</label>
			<? CDeprecatedTemplate::input('text', 'image_height', 'image_height', 'inp inp1'); ?>
		</div>
		<div class="row">
			<label for="thumbnail_method"><? CDeprecatedTemplate::loc_string('thumbnail_method'); ?>:</label>
			<? CTemplate::select('thumbnail_method', 'thumbnail_method', 'sel admin-sel'); ?>
		</div>
		<div class="buttons">
			<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
			<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
			<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
		</div>
	</div>
<? CForm::end(); ?>

