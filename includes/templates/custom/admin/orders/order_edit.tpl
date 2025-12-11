<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
<div class="alert alert-info">
	<h4><? CTemplate::loc_string('warning'); ?>!</h4> <? CTemplate::loc_string('fields_marked_with'); ?> <strong><? CTemplate::loc_string('bold'); ?></strong> <? CTemplate::loc_string('has_been_required'); ?>
</div>
<? if($id):?>
	<h2>Редактирование заказа</h2>
<? else: ?>
	<h2>Новый заказ</h2>
<? endif; ?>
<div>&nbsp;</div>

<ul class="nav nav-tabs" id="<? echo $_table; ?>_tabs">
	<li><a href="#page-tab-1">Данные о заказе</a></li>
	<li><a href="#page-tab-2">Данные о продуктах</a></li>
</ul>

<div class="tab-content">
		<div id="page-tab-1" class="tab-pane">
			<div class="col left_block">
				<table class="nav_t" cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr class="nav_t_r nav_t_r1 nav_row">
							<td class="hand nav_row">Статус</td>
							<td class="hand nav_row"><? CTemplate::select('status', 'status', 'sel'); ?></td>
						</tr>
                                                 <tr class="nav_t_r nav_t_r1 nav_row">
							<td class="hand nav_row">Оплата</td>
							<td class="hand nav_row"><?php CTemplate::select('payment', 'payment', 'sel'); ?></td>
						</tr>
						<tr class="nav_t_r nav_t_r1 nav_row">
							<td class="hand nav_row">Имя</td>
							<td class="hand nav_row"><? CTemplate::input('text', 'name', 'name', 'inp'); ?></td>
						</tr>
						<tr class="nav_t_r nav_t_r1 nav_row">
							<td class="hand nav_row">Адрес</td>
							<td class="hand nav_row"><? CTemplate::input('text', 'address', 'address', 'inp'); ?></td>
						</tr>
						<tr class="nav_t_r nav_t_r1 nav_row">
							<td class="hand nav_row">Телефон</td>
							<td class="hand nav_row"><? CTemplate::input('text', 'phone', 'phone', 'inp'); ?></td>
						</tr>
						<tr class="nav_t_r nav_t_r1 nav_row">
							<td class="hand nav_row">Комментарий</td>
							<td class="hand nav_row"><? CTemplate::input('text', 'comment', 'comment', 'inp'); ?></td>
						</tr>
						<tr class="nav_t_r nav_t_r1 nav_row">
							<td class="hand nav_row"><strong>Всего</strong></td>
							<td class="hand nav_row"><?php echo $total_price; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="clear">&nbsp;</div>
		</div>
		<div id="page-tab-2" class="tab-pane">
			<?php CControl::process('AdminFilters', 'products'); ?>
			<div class="top_buttons">&nbsp;</div>
			<?php CControl::process('DBNavigator', 'products'); ?>
			<strong>Итого:</strong> <?php echo $total_price; ?></strong> BYN
		</div>
</div>
<div class="buttons">
	<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
	<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
</div>

<? CForm::end(); ?>
