<div class="alert alert-info">
    <h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
</div>
<script type="text/javascript" language="javascript" src="<? echo $JS; ?>jquery/jquery.cookie.js"></script>

<? if($id): ?>
<h2>Консультация: <? echo $r_title; ?></h2>
<? else: ?>
<h2>Новый запрос на консультацию</h2>
<? endif; ?>


<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
<div class="col">
    <div class="row">
        <label for="image"><strong><? CTemplate::loc_string('image'); ?>:</strong></label>
		<img src="<? echo $HTTP.'pub/products/'.$r_product_arr[1]['id'].'/130x190/'.$r_product_arr[1]['image_filename']; ?>" />
    </div>
    <div class="row">
        <label for="status"><strong><? CTemplate::loc_string('status'); ?>:</strong></label>
		<? CTemplate::select('status', 'status', 'sel admin-sel'); ?>
    </div>
    <div class="row">
        <label for="name"><strong><? CTemplate::loc_string('name'); ?>:</strong></label>
		<? CTemplate::input('text', 'name', 'name', 'inp'); ?>
    </div>
    <div class="row">
        <label for="name"><strong><? CTemplate::loc_string('phone'); ?>:</strong></label>
		<? CTemplate::input('text', 'phone', 'phone', 'inp'); ?>
    </div>
    <div class="row">
        <label for="comment"><strong><? CTemplate::loc_string('comment'); ?>:</strong></label>
        <? CTemplate::textarea('comment', 'comment', 'inp'); ?>
    </div>


    <div class="buttons">
		<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
    </div>
</div>

<? CForm::end(); ?>