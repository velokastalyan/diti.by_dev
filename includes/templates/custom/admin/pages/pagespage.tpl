<? CForm::begin($_table); ?>
<? CControl::process('AdminFilters', $_table); ?>

<div class="top_buttons">
	<? if($remove_btn_show && $is_debug_mode): ?>
		<? CTemplate::button('del_sel_obj_bot', 'delete_selected_objects', CTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
	<? endif; ?>
	<? CTemplate::button('add_bot', 'add', CTemplate::get_loc_string('add'), 'btn btn-small'); ?>
</div>

<? CControl::process('DBNavigator', $_table); ?>

<div class="bottom_buttons">
	<? if($remove_btn_show && $is_debug_mode): ?>
		<? CTemplate::button('del_sel_obj_bot', 'delete_selected_objects', CTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
	<? endif; ?>
	<? CTemplate::button('add_bot', 'add', CTemplate::get_loc_string('add'), 'btn btn-small'); ?>
</div>
<? CForm::end(); ?>