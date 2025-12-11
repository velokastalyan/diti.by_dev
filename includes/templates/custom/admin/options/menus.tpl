<?php CForm::begin($_table); ?>
<?php CControl::process('AdminFilters', $_table); ?>

	<div class="top_buttons">
		<?php if($remove_btn_show): ?>
			<?php CTemplate::button('del_sel_obj_bot', 'delete_selected_objects', CTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
		<?php endif; ?>
		<?php CTemplate::button('add_bot', 'add', CTemplate::get_loc_string('add'), 'btn btn-small'); ?>
	</div>

<?php CControl::process('DBNavigator', $_table); ?>

	<div class="bottom_buttons">
		<?php if($remove_btn_show): ?>
			<?php CTemplate::button('del_sel_obj_bot', 'delete_selected_objects', CTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
		<?php endif; ?>
		<?php CTemplate::button('add_bot', 'add', CTemplate::get_loc_string('add'), 'btn btn-small'); ?>
	</div>
<?php CForm::end(); ?>