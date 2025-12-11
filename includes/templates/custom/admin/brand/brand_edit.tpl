<? CForm::begin('brand', 'POST', '', 'multipart/form-data'); ?>
<div class="alert alert-info">
    <span class="note_title"><? CTemplate::loc_string('warning'); ?>!</span> <? CTemplate::loc_string('fields_marked_with'); ?> <strong><? CTemplate::loc_string('bold'); ?></strong> <? CTemplate::loc_string('has_been_required'); ?>
</div>
<script type="text/javascript">
    $(function(){
        $('#title').change(function(){
            call('Inputs', 'generate_uri', ['brand', $(this).val()]).listen(set_uri);
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
        $('#uri').val(res.response.uri);
    }
</script>
<? if($id): ?>
<h2><? CTemplate::loc_string('brand'); ?>: <? echo $title; ?></h2>
<? else: ?>
<h2><? CTemplate::loc_string('new_brand'); ?></h2>
<? endif; ?>

<div class="col">
    <div class="row">
        <label for="title"><strong><? CTemplate::loc_string('title'); ?>:</strong></label>
		<? CTemplate::input('text', 'title', 'title', 'inp'); ?>
    </div>
    <div class="row">
        <label for="uri"><strong><? CTemplate::loc_string('uri'); ?>:</strong></label>
		<? CTemplate::input('text', 'uri', 'uri', 'inp'); ?>
    </div>
    <div class="row">
        <label for="image_filename"><strong><? CTemplate::loc_string('image_filename')?></strong></label>
		<? CControl::process('AdminUploader', 'image_filename'); ?>
    </div>
    <div class="row">
        <label for="description"><? CTemplate::loc_string('description'); ?>:</label>
		<? CTemplate::textarea('description', 'description', 'inp', true); ?>
    </div>
    <div class="row">
        <label for="link"><? CTemplate::loc_string('link'); ?>:</label>
		<? CTemplate::input('text', 'link', 'link', 'inp'); ?>
    </div>
    <div class="row">
        <label for="meta_title"><strong><? CTemplate::loc_string('meta_title'); ?>:</strong></label>
		<? CTemplate::input('text', 'meta_title', 'meta_title', 'inp'); ?>
    </div>
    <div class="row">
        <label for="meta_description"><strong><? CTemplate::loc_string('meta_description'); ?>:</strong></label>
		<? CTemplate::textarea('meta_description', 'meta_description', 'inp'); ?>
    </div>
   <!--<*? CControl::process('AdminTags'); ?>-->
    <div class="buttons">
		<? CDeprecatedTemplate::submit('save', 'save', CDeprecatedTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CDeprecatedTemplate::reset('reset', 'reset', CDeprecatedTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CDeprecatedTemplate::button('close', 'close', CDeprecatedTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
    </div>
</div>
<? CForm::end(); ?>
