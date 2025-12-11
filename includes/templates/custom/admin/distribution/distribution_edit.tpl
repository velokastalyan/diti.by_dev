<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
<div class="alert alert-info">
    <h4><? CTemplate::loc_string('warning'); ?>!</h4> <? CTemplate::loc_string('fields_marked_with'); ?> <strong><? CTemplate::loc_string('bold'); ?></strong> <? CTemplate::loc_string('has_been_required'); ?>
</div>

<div>&nbsp;</div>

<div class="tab-content">

        <div class="col">
			<? if($id): ?>
            <h2><? CTemplate::loc_string('distribution'); ?>: <? echo ${'subject'}; ?></h2>
			<? endif; ?>
            <div>&nbsp;</div>
			<? if(!${'send'}): ?>
            <div class="row">
                <label for="send"><? CTemplate::loc_string('send'); ?>:</label>
				<? CTemplate::checkbox('send', 'send', 'chk'); ?>
            </div>
			<? else: ?>
            <div class="row">
                <label for="was_sent"><? CTemplate::loc_string('was_sent'); ?>:</label>
				<? echo ${'dispatch_date'}; ?>
				<? CTemplate::input('hidden', 'send', 'send'); ?>
            </div>
			<? endif; ?>
            <div class="row">
                <label for="subject"><strong><? CTemplate::loc_string('subject'); ?>:</strong></label>
				<? CTemplate::input('text', 'subject', 'subject', 'inp'); ?>
            </div>
            <div class="row">
                <label for="message"><strong><? CTemplate::loc_string('message'); ?>:</strong></label>
				<? CTemplate::htmlarea('message', 'message', 'inp'); ?>
            </div>
        </div>
</div>

<div class="buttons">
	<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
	<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
	<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
</div>

<? CForm::end(); ?>
