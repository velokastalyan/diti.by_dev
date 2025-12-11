<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
<? CTemplate::input('hidden', 'product_id', 'product_id'); ?>
<div class="alert alert-info">
    <h4><? CTemplate::loc_string('warning'); ?>!</h4> <? CTemplate::loc_string('fields_marked_with'); ?> <strong><? CTemplate::loc_string('bold'); ?></strong> <? CTemplate::loc_string('has_been_required'); ?>
</div>


<h2>Изображение продукта: <? echo $p_title; ?></h2>

<div class="col">

    <div class="row">
        <label for="image_filename"><strong><? CTemplate::loc_string('image'); ?>:</strong></label>
		<? CControl::process('AdminUploader', 'image_filename'); ?>
    </div>

    <div class="row">
        <label for="is_core"><? CTemplate::loc_string('is_core'); ?>:</label>
		<? CTemplate::checkbox('is_core', 'is_core', 'chk'); ?>
    </div>

    <div class="buttons">
		<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
    </div>
</div>
<? CForm::end(); ?>

