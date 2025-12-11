<h2><? CTemplate::loc_string('welcome_to_production_management'); ?></h2>
<? CForm::begin('xml_form'); ?>
    <div class="top_buttons">
        <? CDeprecatedTemplate::button('del_sel_obj_top', 'generate_xml', CDeprecatedTemplate::get_loc_string('generate_xml'), 'btn btn-small btn btn-inverse'); ?>
    </div>

<? CForm::end(); ?>
