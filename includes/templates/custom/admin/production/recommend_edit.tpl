<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
<? CControl::process('AdminFilters', $_table); ?>
<? CTemplate::input('hidden', 'product_id', 'product_id'); ?>


<div class="col">
	<? CControl::process('DBNavigator', $_table); ?>

	<? CDeprecatedTemplate::button('add_bot', 'add_product_recommend', CDeprecatedTemplate::get_loc_string('add'), 'btn btn-small'); ?>

</div>
<? CForm::end(); ?>
