<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
<? CTemplate::input('hidden', 'article_id', 'article_id'); ?>
<div class="alert alert-info">
    <h4><? CDeprecatedTemplate::loc_string('warning'); ?>!</h4> <? CDeprecatedTemplate::loc_string('fields_marked_with'); ?> <strong><? CDeprecatedTemplate::loc_string('bold'); ?></strong> <? CDeprecatedTemplate::loc_string('has_been_required'); ?>
</div>

<script type="text/javascript">
    $(function(){
        $('#title').change(function(){
            call('Inputs', 'generate_uri_ereg', [$(this).val()]).listen(set_uri);
        });
    });

    function set_uri(res)
    {
        if(res.errors)
        {
            $('#uri').val('');
            alert(res.errors);
            return false;
        }
        $('#uri').val(res.response);
    }
</script>

<? if($id): ?>
<h2>Статья: <? echo $title; ?></h2>
<? else: ?>
<h2>Новая статья</h2>
<? endif;  ?>


<div class="col">

    <div class="row right_block">
        <label for="public_date"><? CTemplate::loc_string('public_date'); ?>:</label>
		<? CTemplate::input('text', 'public_date', 'public_date', 'inp inpDate'); ?> &nbsp;&nbsp;&nbsp; <? CTemplate::input('text', 'hours', 'hours', 'inp inp25'); ?> : <? CTemplate::input('text', 'minutes', 'minutes', 'inp inp25'); ?> : <? CTemplate::input('text', 'seconds', 'seconds', 'inp inp25'); ?><br />
        <label for="hours" class="sign" style="margin-left: 195px;"><? CTemplate::loc_string('hours'); ?></label> <label for="minutes" class="sign" style="margin-left: 5px;"><? CTemplate::loc_string('minutes'); ?></label> <label for="seconds" class="sign" style="margin-left: 5px;"><? CTemplate::loc_string('seconds'); ?></label>
    </div>
	<div class="row">
        <label for="status"><strong><? CTemplate::loc_string('status'); ?>:</strong></label>
		<? CTemplate::select('status', 'status', 'sel admin-sel'); ?>
    </div>
    <div class="row">
        <label for="id_cat"><strong><? CTemplate::loc_string('category'); ?>:</strong></label>
		<? CTemplate::select('id_cat', 'id_cat', 'sel admin-sel'); ?>
    </div>
    <div class="row">
        <label for="image_filename"><? CTemplate::loc_string('image'); ?>:</label>
		<? CControl::process('AdminUploader', 'image_filename'); ?>
    </div>

    <div class="row">
        <label for="title"><strong><? CTemplate::loc_string('title'); ?>:</strong></label>
		<? CTemplate::input('text', 'title', 'title', 'inp'); ?>
    </div>
    <div class="row">
        <label for="uri"><strong><? CTemplate::loc_string('uri'); ?>:</strong></label>
		<? CTemplate::input('text', 'uri', 'uri', 'inp'); ?>
    </div>

    <div class="row">
        <label for="description"><strong><? CTemplate::loc_string('description'); ?>:</strong></label>
		<? CTemplate::htmlarea('description', 'description', 'inp', true); ?>
    </div>

    <div class="row">
        <label for="meta_title"><strong><? CTemplate::loc_string('meta_title'); ?>:</strong></label>
		<? CTemplate::input('text', 'meta_title', 'meta_title', 'inp'); ?>
    </div>
    <div class="row">
        <label for="meta_description"><? CTemplate::loc_string('meta_description'); ?>:</label>
		<? CTemplate::textarea('meta_description', 'meta_description', 'inp'); ?>
    </div>


    <div class="buttons">
		<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
    </div>

</div>

<? CForm::end(); ?>

