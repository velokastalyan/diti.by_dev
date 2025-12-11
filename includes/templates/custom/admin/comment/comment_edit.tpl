<div class="alert alert-info">
    <h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
</div>
<script type="text/javascript" language="javascript" src="<? echo $JS; ?>jquery/jquery.cookie.js"></script>

<? if($id): ?>
<h2>Отзыв: <? if($id && intval($product_id) > 0): ?><? echo $title; ?><? endif; ?></h2>
<? else: ?>
<h2>Новый Отзыв</h2>
<? endif; ?>


<? CForm::begin('comment', 'POST', '', 'multipart/form-data'); ?>
<div class="col">
    <div class="row">
        <label for="product_id"><strong><? CTemplate::loc_string('product_id'); ?>:</strong></label>
		<? CTemplate::select('product_id', 'product_id', 'sel admin-sel'); ?>
    </div>
    <div class="row">
        <label for="status"><strong><? CTemplate::loc_string('status'); ?>:</strong></label>
		<? CTemplate::select('status', 'status', 'sel admin-sel'); ?>
    </div>
    <div class="row">
        <label for="rate"><strong><? CTemplate::loc_string('rate'); ?>:</strong></label>
		<? CTemplate::select('rate', 'rate', 'sel admin-sel'); ?>
    </div>
    <div class="row">
        <label for="title"><strong><? CTemplate::loc_string('title'); ?>:</strong></label>
		<? CTemplate::input('text', 'title', 'title', 'inp'); ?>
    </div>
    <div class="row">
        <label for="name"><strong><? CTemplate::loc_string('name'); ?>:</strong></label>
		<? CTemplate::input('text', 'name', 'name', 'inp'); ?>
    </div>
    <div class="row">
        <label for="description"><strong><? CTemplate::loc_string('description'); ?>:</strong></label>
		<? CTemplate::textarea('description', 'description', 'inp'); ?>
    </div>
    <div class="row">
        <label for="answer"><strong><? CTemplate::loc_string('answer'); ?>:</strong></label>
        <? CTemplate::textarea('answer', 'answer', 'inp'); ?>
    </div>


    <div class="buttons">
		<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
    </div>
</div>

<? CForm::end(); ?>