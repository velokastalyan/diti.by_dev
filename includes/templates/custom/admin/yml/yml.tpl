<? CForm::begin('yml', 'POST', '', 'multipart/form-data'); ?>
<h2><? CTemplate::loc_string('yml'); ?></h2>
<div class="col">

    <div class="buttons">
        <div class="inpwrapper"><? CTemplate::submit('save', 'save', 'Экспорт в YML', 'butt'); ?></div>
    </div>
</div>
<? CForm::end(); ?>