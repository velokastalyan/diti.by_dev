<? CForm::begin($_table); ?>
	<div class="alert alert-info">
		<h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
	</div>
	
	<? if($id): ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('user'); ?>: <? echo $name; ?></h2>
	<? else: ?>
		<h2 class="title"><? CDeprecatedTemplate::loc_string('new_user'); ?></h2>
	<? endif; ?>
	
	<div class="col">
		<div class="row">
			<label for="email"><strong><? CDeprecatedTemplate::loc_string('email'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'email', 'email', 'inp'); ?>
		</div>
		<div class="row">
			<? if(!$id): ?>
				<label for="password"><strong><? CDeprecatedTemplate::loc_string('password'); ?>:</strong></label>
			<? else: ?>
				<label for="password"><? CDeprecatedTemplate::loc_string('password'); ?>:</label>
			<? endif;?>
			<? CDeprecatedTemplate::input('password', 'password', 'password', 'inp'); ?>
		</div>
		<div class="row">
			<label for="name"><strong><? CDeprecatedTemplate::loc_string('name'); ?>:</strong></label>
			<? CDeprecatedTemplate::input('text', 'name', 'name', 'inp'); ?>
		</div>
		<div class="row">
			<label for="address"><? CDeprecatedTemplate::loc_string('address'); ?>:</label>
			<? CDeprecatedTemplate::input('text', 'address', 'address', 'inp'); ?>
		</div>
		<div class="row">
			<label for="city"><? CDeprecatedTemplate::loc_string('city'); ?>:</label>
			<? CDeprecatedTemplate::input('text', 'city', 'city', 'inp'); ?>
		</div>
		<div class="row">
			<label for="state_id"><? CDeprecatedTemplate::loc_string('state_id'); ?>:</label>
			<? CTemplate::select('state_id', 'state_id', 'sel admin-sel'); ?>
		</div>
		<div class="row">
			<label for="zip"><? CDeprecatedTemplate::loc_string('zip'); ?>:</label>
			<? CDeprecatedTemplate::input('text', 'zip', 'zip', 'inp inp1'); ?>
		</div>
		<div class="row">
			<label for="user_role_id"><strong><? CDeprecatedTemplate::loc_string('user_role_id'); ?>:</strong></label>
			<? CTemplate::select('user_role_id', 'user_role_id', 'sel admin-sel'); ?>
		</div>
		<div class="row">
			<label for="status"><strong><? CDeprecatedTemplate::loc_string('status'); ?>:</strong></label>
			<? CTemplate::select('status', 'status', 'sel admin-sel'); ?>
		</div>
		<div class="buttons">
			<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
			<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
			<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
		</div>
	</div>
<? CForm::end(); ?>