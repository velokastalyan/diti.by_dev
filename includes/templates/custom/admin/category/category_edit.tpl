<? CForm::begin('category', 'POST', '', 'multipart/form-data'); ?>
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
<h2>Категория: <? if($id && intval($parent_id) > 0): ?><? echo $parent_path; ?>/<? endif; ?><? echo $title; ?></h2>
<? else: ?>
<h2>Новая категория</h2>
<? endif; ?>


<div class="col">
    <div class="row">
        <label for="parent_id"><? CTemplate::loc_string('parent_id'); ?>:</label>
		<? CTemplate::select('parent_id', 'parent_id', 'sel admin-sel'); ?>
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
        <label for="image_filename"><strong><? CTemplate::loc_string('image'); ?>:</strong></label>
		<? CControl::process('AdminUploader', 'image_filename'); ?>
	</div>

    <div class="row">
        <label for="description"><? CTemplate::loc_string('description'); ?>:</label>
		<? CTemplate::htmlarea('description', 'description', 'inp', true); ?>
    </div>

    <div class="row">
        <label for="h1_text"><? CTemplate::loc_string('h1_text'); ?>:</label>
        <? CTemplate::input('text', 'h1_text', 'h1_text', 'inp'); ?>
    </div>

    <div class="row">
        <label for="meta_title"><strong><? CTemplate::loc_string('meta_title'); ?>:</strong></label>
		<? CTemplate::input('text', 'meta_title', 'meta_title', 'inp'); ?>
    </div>
    <div class="row">
        <label for="meta_description"><strong><? CTemplate::loc_string('meta_description'); ?>:</strong></label>
		<? CTemplate::textarea('meta_description', 'meta_description', 'inp'); ?>
    </div>
    <div class="row">
        <label for="position"><strong><? CTemplate::loc_string('position'); ?>:</strong></label>
        <? CTemplate::input('text', 'position', 'position', 'inp inp25'); ?>
    </div>
    <div class="row">
        <label for="dollar_currency"><strong>Курс доллара:</strong></label>
        <? CTemplate::input('text', 'dollar_currency', 'dollar_currency', 'inp'); ?>
    </div>
    <div class="row">
        <label for="rouble_currency"><strong>Курс рубля:</strong></label>
        <? CTemplate::input('text', 'rouble_currency', 'rouble_currency', 'inp'); ?>
    </div>
    <div class="buttons">
        <? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
    </div>

</div>

<? CForm::end(); ?>

