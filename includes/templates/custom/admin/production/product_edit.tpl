<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
	<div class="alert alert-info">
		<h4><? CTemplate::loc_string('warning'); ?>!</h4> <? CTemplate::loc_string('fields_marked_with'); ?> <strong><? CTemplate::loc_string('bold'); ?></strong> <? CTemplate::loc_string('has_been_required'); ?>
	</div>
	<script type="text/javascript">
		$(function(){
			$('#title').change(function(){
				call('Inputs', 'generate_uri', ['<? echo $_table; ?>', $(this).val()]).listen(set_uri);

                <?php if (!$id): ?>
                    $('#meta_title').val(  $(this).val() + ' Купить в Минске.' );
                    $('#meta_description').val( 'Купить ' +$(this).val() + ' в интернет-магазине. Доставка по Беларуси и в Москву. Технические характеристики, отзывы.' );
                <?php endif; ?>
			});
			$('#price').change(function(){
				var price_rub = document.getElementById("price_rub");
				var price = document.getElementById('price');
				price_rub.innerText='В рублях: ' + (price.value * <? echo $this->tv['sd_dollar']; ?>) + ' (Курс: <? echo $this->tv['sd_dollar'].'BYN'; ?>)';
			})
            $('#discount').change(function(){
                var discount_rub = document.getElementById("discount_rub");
                var discount = document.getElementById('discount');
                discount_rub.innerText='В рублях: ' + (discount.value * <? echo $this->tv['sd_dollar']; ?>) + ' (Курс: <? echo $this->tv['sd_dollar'].'BYN'; ?>)';
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
		<h2><? CTemplate::loc_string('product'); ?>: <? echo $title; ?></h2>
	<? else: ?>
		<h2><? CTemplate::loc_string('new_product'); ?></h2>
	<? endif; ?>

	<ul class="nav nav-tabs" id="<? echo $_table; ?>-tabs">
		<li><a href="#<? echo $_table; ?>-tab"><? CTemplate::loc_string('product'); ?></a></li>
		<? if($id): ?>
			<li><a href="#<? echo $_table; ?>-images-tab"><? CTemplate::loc_string('gallery'); ?></a></li>
        	<li><a href="#<? echo $_table; ?>-recommend-tab"><? CTemplate::loc_string('recommend'); ?></a></li>
		<? endif; ?>
	</ul>

	<div class="tab-content">
		<div id="<? echo $_table; ?>-tab" class="tab-pane">
			<div class="col">
				<div class="row">
					<label for="category_id"><strong><? CTemplate::loc_string('category_id'); ?>:</strong></label>
					<? CTemplate::select('category_id', 'category_id', 'sel admin-sel'); ?>
				</div>
				<div class="row">
					<label for="brand_id"><strong><? CTemplate::loc_string('brand_id'); ?>:</strong></label>
					<? CTemplate::select('brand_id', 'brand_id', 'sel admin-sel'); ?>
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
					<label for="price"><strong><? CTemplate::loc_string('price'); ?>:</strong></label>
					<? CTemplate::input('text', 'price', 'price', 'inp'); ?> <span id="price_rub"></span>
				</div>
				<div class="row">
					<label for="discount"><? CTemplate::loc_string('discount'); ?>:</label>
					<? CTemplate::input('text', 'discount', 'discount', 'inp'); ?> <span id="discount_rub"></span>
				</div>
                <div class="row">
                    <label for="is_sale"><? CTemplate::loc_string('sale'); ?>:</label>
					<? CTemplate::checkbox('is_sale', 'is_sale', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="is_recommend"><? CTemplate::loc_string('recommend'); ?>:</label>
					<? CTemplate::checkbox('is_recommend', 'is_recommend', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="is_new"><? CTemplate::loc_string('is_new'); ?>:</label>
					<? CTemplate::checkbox('is_new', 'is_new', 'inp'); ?>
                </div>
				<div class="row">
                    <label for="is_delivery_minsk"><? CTemplate::loc_string('is_delivery_minsk'); ?>:</label>
					<? CTemplate::checkbox('is_delivery_minsk', 'is_delivery_minsk', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="description_delivery_minsk"><? CTemplate::loc_string('description_delivery_minsk'); ?>:</label>
					<? CTemplate::input('text', 'description_delivery_minsk', 'description_delivery_minsk', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="is_delivery_belarus"><? CTemplate::loc_string('is_delivery_belarus'); ?>:</label>
					<? CTemplate::checkbox('is_delivery_belarus', 'is_delivery_belarus', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="description_delivery_belarus"><? CTemplate::loc_string('description_delivery_belarus'); ?>:</label>
					<? CTemplate::input('text', 'description_delivery_belarus', 'description_delivery_belarus', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="is_delivery_moscow"><? CTemplate::loc_string('is_delivery_moscow'); ?>:</label>
					<? CTemplate::checkbox('is_delivery_moscow', 'is_delivery_moscow', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="description_delivery_moscow"><? CTemplate::loc_string('description_delivery_moscow'); ?>:</label>
					<? CTemplate::input('text', 'description_delivery_moscow', 'description_delivery_moscow', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="size_arr"><? CTemplate::loc_string('size_arr'); ?>:</label>
                    <? CTemplate::input('text', 'size_arr', 'size_arr', 'inp'); ?>
                </div>
                <div class="row">
                    <label for="wheel"><strong><? CTemplate::loc_string('wheel'); ?>:</strong></label>
                    <? CTemplate::select('wheel', 'wheel', 'sel admin-sel'); ?>
                </div>
                <div class="row">
                    <label for="suspension"><strong><? CTemplate::loc_string('suspension'); ?>:</strong></label>
                    <? CTemplate::select('suspension', 'suspension', 'sel admin-sel'); ?>
                </div>
                <div class="row">
                    <label for="category_id"><strong><? CTemplate::loc_string('category_id'); ?>:</strong></label>
                    <? CTemplate::select('category_id', 'category_id', 'sel admin-sel'); ?>
                </div>
				<div class="row">
					<label for="sex"><strong><? CTemplate::loc_string('sex'); ?>:</strong></label>
					<? CTemplate::select('sex', 'sex', 'sel admin-sel'); ?>
				</div>
				<div class="row">
					<label for="sex"><strong>Год выпуска:</strong></label>
					<? CTemplate::input('text', 'year', 'year', 'inp'); ?>				</div>

				<div class="row">
					<label for="wheel">Тормоза:</strong></label>
					<? CTemplate::select('tormoza', 'tormoza', 'sel admin-sel'); ?>
				</div>

				<div class="row">
					<label for="wheel">Вилка:</strong></label>
					<? CTemplate::select('vilka', 'vilka', 'sel admin-sel'); ?>
				</div>
				<div class="row">
					<label for="wheel">Материал рамы:</strong></label>
					<? CTemplate::select('rama', 'rama', 'sel admin-sel'); ?>
				</div>


                <div class="row">
                    <label for="status"><? CTemplate::loc_string('status'); ?>:</label>
                    <? CTemplate::select('status', 'status', 'sel admin-sel'); ?>
                </div>
				<div class="row">
					<label for="description"><strong><? CTemplate::loc_string('description'); ?>:</strong></label>
					<? CTemplate::htmlarea('description', 'description', 'inp', true); ?>
				</div>
				<div class="row">
					<label for="video"><strong><? CTemplate::loc_string('video'); ?>:</strong></label>
					<? CTemplate::htmlarea('video', 'video', 'inp', true); ?>
				</div>

				<div class="row">
					<label for="meta_title"><strong><? CTemplate::loc_string('meta_title'); ?>:</strong></label>
					<? CTemplate::input('text', 'meta_title', 'meta_title', 'inp'); ?>
				</div>
				<div class="row">
					<label for="meta_description"><strong><? CTemplate::loc_string('meta_description'); ?>:</strong></label>
					<? CTemplate::textarea('meta_description', 'meta_description', 'inp'); ?>
				</div>
			</div>
		</div>
		<? if($id): ?>
			<div id="<? echo $_table; ?>-images-tab" class="tab-pane">
				<? CControl::process('AdminFilters', 'product_images'); ?>
                <div class="top_buttons">
					<? if($remove_images_btn_show): ?>
						<? CTemplate::button('del_sel_obj_top_image', 'delete_selected_images', CTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
					<? endif; ?>
					<? CDeprecatedTemplate::button('add_top_image', 'add_image', CTemplate::get_loc_string('add'), 'btn btn-small'); ?>
                </div>

				<? CControl::process('DBNavigator', 'product_images'); ?>

                <div class="bottom_buttons">
					<? if($remove_images_btn_show): ?>
						<? CTemplate::button('del_sel_obj_top_image', 'delete_selected_images', CTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
					<? endif; ?>
					<? CTemplate::button('add_top_image', 'add_image', CTemplate::get_loc_string('add'), 'btn btn-small'); ?>
                </div>
			</div>
		<? endif; ?>
        <? if($id): ?>
        <div id="<? echo $_table; ?>-recommend-tab" class="tab-pane">
            <? CControl::process('AdminFilters', 'product_recommend'); ?>
            <div class="top_buttons">
                <? if($remove_recommend_btn_show): ?>
                <? CTemplate::button('del_sel_obj_top_recommend', 'delete_selected_recommend', CTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
                <? endif; ?>
                <? CDeprecatedTemplate::button('add_top_recommend', 'add_recommend', CTemplate::get_loc_string('add'), 'btn btn-small'); ?>
            </div>

            <? CControl::process('DBNavigator', 'product_recommend'); ?>

            <div class="bottom_buttons">
                <? if($remove_recommend_btn_show): ?>
                <? CTemplate::button('del_sel_obj_top_recommend', 'delete_selected_recommend', CTemplate::get_loc_string('delete_selected'), 'btn btn-small btn btn-inverse'); ?>
                <? endif; ?>
                <? CTemplate::button('add_top_recommend', 'add_recommend', CTemplate::get_loc_string('add'), 'btn btn-small'); ?>
            </div>
        </div>
        <? endif; ?>
	</div>
	<div class="buttons">
		<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
	</div>
<? CForm::end(); ?>