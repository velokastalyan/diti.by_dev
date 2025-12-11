<? CForm::begin($_table); ?>
<? CControl::process('AdminFilters', $_table); ?>
<div class="top_buttons">
	<? if($remove_btn_show): ?>
		<? CDeprecatedTemplate::button('del_sel_obj_top', 'delete_selected_objects', CDeprecatedTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
	<? endif; ?>
	<? CDeprecatedTemplate::button('add_top', 'add', CDeprecatedTemplate::get_loc_string('add'), 'btn btn-small'); ?>
</div>

<? CControl::process('DBNavigator', $_table); ?>

<div class="bottom_buttons">
	<? if($remove_btn_show): ?>
		<? CDeprecatedTemplate::button('del_sel_obj_bot', 'delete_selected_objects', CDeprecatedTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
	<? endif; ?>
	<? CDeprecatedTemplate::button('add_bot', 'add', CDeprecatedTemplate::get_loc_string('add'), 'btn btn-small'); ?>
</div>
<? CForm::end(); ?>