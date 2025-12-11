<? CForm::begin($_table); ?>
	<div class="note">
		<span class="note_title"><? CDeprecatedTemplate::loc_string('warning'); ?>!</span> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>		

	<? if($id): ?>
		<h2><? CDeprecatedTemplate::loc_string('vk_user'); ?>: <a href="http://vk.com/<? echo $screen_name; ?>"><? echo $last_name; ?> <? echo $first_name; ?></a></h2>
	<? endif; ?>
	
	<div class="col">
		<div class="row">
			<label for="status"><strong><? CDeprecatedTemplate::loc_string('status'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('select', 'status', 'status', 'sel'); ?>
		</div>
		<div class="row">
			<label for="#"><strong><? CDeprecatedTemplate::loc_string('last_name'); ?>:</strong></label>
			<span class="chk"><? echo $last_name; ?></span>
		</div>
		<div class="row">
			<label for="#"><strong><? CDeprecatedTemplate::loc_string('first_name'); ?>:</strong></label>
			<span class="chk"><? echo $first_name; ?></span>
		</div>
		<div class="row">
			<label for="#"><strong><? CDeprecatedTemplate::loc_string('nickname'); ?>:</strong></label>
			<span class="chk"><? echo $nickname; ?></span>
		</div>
		<div class="row">
			<label for="#"><strong><? CDeprecatedTemplate::loc_string('screen_name'); ?>:</strong></label>
			<span class="chk"><? echo $screen_name; ?></span>
		</div>
		<div class="row">
			<label for="#"><strong><? CDeprecatedTemplate::loc_string('sex'); ?>:</strong></label>
			<span class="chk"><? if(intval($sex) === 1): ?><? CDeprecatedTemplate::loc_string('female'); ?><? else: ?><? CDeprecatedTemplate::loc_string('male'); ?><? endif; ?></span>
		</div>
		<? if(strlen($photo_filename) > 0): ?>
			<div class="row">
				<label for="#"><strong><? CDeprecatedTemplate::loc_string('photo_filename'); ?>:</strong></label>
				<img src="<? echo $HTTP; ?>pub/VKusers/<? echo $id; ?>/<? echo $photo_filename; ?>" class="chk" />
			</div>
		<? endif; ?>
		<div class="row">
			<label for="#"><strong><? CDeprecatedTemplate::loc_string('last_login_date'); ?>:</strong></label>
			<span class="chk"><? echo $last_login_date; ?></span>
		</div>
		<div class="row">
			<label for="#"><strong><? CDeprecatedTemplate::loc_string('create_date'); ?>:</strong></label>
			<span class="chk"><? echo $create_date; ?></span>
		</div>
		<div class="buttons">
			<div class="inpwrapper"><? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'butt'); ?></div>
			<div class="inpwrapper"><? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'butt'); ?></div>
			<div class="inpwrapper"><? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'butt'); ?></div>
		</div>
	</div>
<? CForm::end(); ?>
