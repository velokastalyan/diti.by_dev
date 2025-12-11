<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
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
<h2>Видеозапись:<? echo $title; ?></h2>
<? else: ?>
<h2>Новая видеозапись</h2>
<? endif; ?>


<div class="col">

    <div class="row">
        <label for="title"><strong><? CTemplate::loc_string('title'); ?>:</strong></label>
		<? CTemplate::input('text', 'title', 'title', 'inp'); ?>
    </div>

    <div class="row">
        <label for="uri"><strong><? CTemplate::loc_string('link'); ?>:</strong></label>
		<? CTemplate::input('text', 'link', 'link', 'inp'); ?>
    </div>

    <div class="row">
        <label for="position"><strong><? CTemplate::loc_string('position'); ?>:</strong></label>
		<? CTemplate::input('text', 'position', 'position', 'inp inp25'); ?>
    </div>


    <div class="buttons">
		<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
    </div>

</div>

<? CForm::end(); ?>

