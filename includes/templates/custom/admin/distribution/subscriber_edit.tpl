<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
<div class="alert alert-info">
    <h4><? CTemplate::loc_string('warning'); ?>!</h4> <? CTemplate::loc_string('fields_marked_with'); ?> <strong><? CTemplate::loc_string('bold'); ?></strong> <? CTemplate::loc_string('has_been_required'); ?>
</div>

<? if($id): ?>
<h2 class="title"><? CTemplate::loc_string('subscriber'); ?>: <? echo $email; ?></h2>
<? else: ?>
<h2 class="title"><? CTemplate::loc_string('new_subscriber'); ?></h2>
<? endif; ?>

<div class="col">
    <div class="row">
        <label for="email"><strong><? CTemplate::loc_string('email'); ?>:</strong></label>
		<? CTemplate::input('text', 'email', 'email', 'inp'); ?>
    </div>
    <div class="buttons">
		<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
    </div>
</div>
<? CForm::end(); ?>
