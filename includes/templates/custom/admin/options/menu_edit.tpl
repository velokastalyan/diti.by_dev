<?php CForm::begin($_table); ?>
	<div class="alert alert-info">
		<h4><?php CTemplate::loc_string('warning'); ?>!</h4> <?php CTemplate::loc_string('fields_marked_with'); ?> <strong><?php CTemplate::loc_string('bold'); ?></strong> <?php CTemplate::loc_string('has_been_required'); ?>
	</div>

	<script type="text/javascript">
		$(function() {
			$('#menu_title').change(function() {
				call('Inputs', 'generate_uri_ereg', [$(this).val()]).listen(function(res){
					if(res.errors)
					{
						$('#menu_path').val('');
						alert(res.errors);
						return false;
					}
					$('#menu_path').val(res.response);
				});
			});


			$('#menu_child').nestable({
				group: 0,
				maxDepth: 3
			});

			//show info from item menu(for edit)
			$('.show-info').live('click', function() {
				var id = parseInt($(this).attr('data-id'));

				if ($(this).hasClass('showed')) {
					$('.item-data-' + id).slideUp();
					$(this).removeClass('showed');
				} else {
					$('.item-data-' + id).slideDown();
					$(this).addClass('showed');
				}
			});

			//cancel info
			$('.item-cancel').live('click', function() {
				var id = parseInt( $(this).attr('id').replace('cancel-','') );

				$('.show-info[data-id="'+id+'"]').removeClass('showed');
				$('.item-data-' + id).slideUp();
				return false;
			});

			//delete item menu
			$('.item-delete').live('click', function() {
				var id = parseInt( $(this).attr('id').replace('delete-','') );

				$('.item-data-' + id).slideUp(200);
				$('.dd-item[data-id="'+id+'"] .dd-handle').css('background','#FA2626');
				setTimeout(function() { $('.dd-item[data-id="'+id+'"]').remove(); }, 200);

				return false;
			});

			//show/hide info add-links-box
			$('.add-item-menu-title').live('click', function() {
				var action = $(this).attr('rel');
				if ($(this).hasClass('showed')) {
					$(this).removeClass('showed');
					$('.add-'+action+'-links .add-links-body').slideUp();
				} else {
					$(this).addClass('showed');
					$('.add-'+action+'-links .add-links-body').slideDown();
				}
			});

			//Add to menu
			$('.add_to_menu').click(function() {
				var action = $(this).attr('rel');
				var maxId = 1;

				if ($('.dd-item').length) {
					$('.dd-item').children().each(function () {
						if (parseInt($(this).attr('data-id')) > maxId)
							maxId = parseInt($(this).attr('data-id'));
					});
					maxId++;
				}

				if(action == 'custom') {
					var url = $('#add_custom_url').attr('value'),
						label = $('#add_custom_label').attr('value');

					if (url != '' && url != 'http://' && label != '') {
						var newItem = '<li class="dd-item" data-id="'+ maxId +'">\
							<div class="dd-handle btn">'+ label +'</div><div class="show-info" data-id="'+ maxId +'"><span class="caret"></span></div>\
							<div class="menu-item-data item-data-'+ maxId +'">\
							<?php CTemplate::loc_string('url') ?>: <br>\
							<input type="text" id="item-menu-url-'+ maxId +'" name="item-menu-data['+ maxId +'][url]" class="inp" value="'+ url +'"><br>\
							<?php CTemplate::loc_string('label'); ?>: <br>\
							<input type="text" id="item-menu-label-'+ maxId +'" name="item-menu-data['+ maxId +'][label]" class="inp" value="'+ label +'">\
							<div class="menu-item-actions">\
							<a class="item-delete" id="delete-'+ maxId +'" href="#"><?php CTemplate::loc_string('remove'); ?></a>\
                            <span class="meta-sep"> | </span>\
                             <a class="item-cancel" id="cancel-'+ maxId +'" href="#"><?php CTemplate::loc_string('cancel'); ?></a>\
							</div>\
							<input type="hidden" id="item-menu-parent-'+ maxId +'" name="item-menu-data['+ maxId +'][parent]" value="0">\
							</div>\
							</li>';
						$('.dd .dd-list.main').append( newItem );
						$('#add_custom_url').attr('value','http://');
						$('#add_custom_label').attr('value','');
					}
				}
				else { //for checkbox-panel
					$( '.' + action + '-checkbox:checked').each(function() {
						var url = $(this).attr('value'),
							label = $(this).attr('data-title');

						if (url != '' && url != 'http://' && label != '') {
							var newItem = '<li class="dd-item" data-id="'+ maxId +'">\
								<div class="dd-handle btn">'+ label +'</div><div class="show-info" data-id="'+ maxId +'"><span class="caret"></span></div>\
								<div class="menu-item-data item-data-'+ maxId +'">\
                                <?php CTemplate::loc_string('url') ?>: <br>\
								<input type="text" id="item-menu-url-'+ maxId +'" name="item-menu-data['+ maxId +'][url]" class="inp" value="'+ url +'"><br>\
								<?php CTemplate::loc_string('label'); ?>: <br>\
								<input type="text" id="item-menu-label-'+ maxId +'" name="item-menu-data['+ maxId +'][label]" class="inp" value="'+ label +'">\
								<div class="menu-item-actions">\
								<a class="item-delete" id="delete-'+ maxId +'" href="#"><?php CTemplate::loc_string('remove'); ?></a>\
                                <span class="meta-sep"> | </span> \
                                <a class="item-cancel" id="cancel-'+ maxId +'" href="#"><?php CTemplate::loc_string('cancel'); ?></a>\
								</div>\
								<input type="hidden" id="item-menu-parent-'+ maxId +'" name="item-menu-data['+ maxId +'][parent]" value="0">\
								</div>\
								</li>';
							$('.dd .dd-list.main').append( newItem );
							maxId++;
						}
					});
					$( '.' + action + '-checkbox:checked').removeAttr('checked');
				}
				return false;
			});

			//Update value in hidden-input "item-parent"
			$('.dd').on('change', function() {
				var struct = window.JSON.stringify($('.dd').nestable('serialize'));
				var obj = $.parseJSON( struct );

				update_item_data( obj, 0 );
			});

		});

		function update_item_data(obj, parentID)
		{
			for(var i in obj) {
				var row = obj[i];
				if (row.children) {
					update_item_data(row.children, row.id);
				} else {
					$('#item-menu-parent-' + row.id).attr('value', parentID );
				}
			}
		}
	</script>

	<script src="<?php echo $HTTP; ?>js/jquery.nestable.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $HTTP; ?>css/admin.menu-page.css">

<?php if($id): ?>
	<h2 class="title"><?php CTemplate::loc_string('edit_menu'); ?>: <?php echo $title; ?></h2>
<?php else: ?>
	<h2 class="title"><?php CTemplate::loc_string('new_menu'); ?></h2>
<?php endif; ?>

	<div class="col">

		<div class="row">
			<label for="menu_title"><strong><?php CTemplate::loc_string('menu_title'); ?>:</strong></label>
			<?php CTemplate::input('text', 'menu_title', 'menu_title', 'inp'); ?>
		</div>
		<div class="row">
			<label for="menu_path"><strong><?php CTemplate::loc_string('menu_path'); ?>:</strong></label>
			<?php CTemplate::input('text', 'menu_path', 'menu_path', 'inp'); ?>
		</div>
		<div class="row">
			<label for="language_id"><strong><?php CTemplate::loc_string('language'); ?></strong></label>
			<?php CTemplate::select('menu_lang', 'menu_lang', 'sel admin-sel'); ?>
		</div>

	</div>

	<hr/>

	<h2><?php CTemplate::loc_string('Menu Structure'); ?></h2>

	<div class="cols">
		<div class="row row-left">

			<div class="control-section">
				<div class="add-links-box add-custom-links">
					<h3 class="add-item-menu-title btn showed" rel="custom"><?php CTemplate::loc_string('links'); ?></h3>
					<div class="add-links-body">

						<div class="row">
                            <span class="left-add-links-box">
                                <label for="add_custom_url"><?php CTemplate::loc_string('url'); ?></label>
                            </span>
                            <span class="right-add-links-box">
                                <input type="text" class="inp" id="add_custom_url" value="http://">
                            </span>
						</div>
						<div class="row">
                            <span class="left-add-links-box">
                                <label for="add_custom_label"><?php CTemplate::loc_string('label'); ?></label>
                            </span>
                            <span class="right-add-links-box">
                                <input type="text" class="inp" id="add_custom_label" value="" placeholder="<?php CTemplate::loc_string('menu_item') ?>">
                            </span>
						</div>

						<input type="button" class="btn add_to_menu" rel="custom" value="<?php CTemplate::loc_string('add_to_menu'); ?>">
					</div>
				</div>

				<!-- PAGES -->
				<?php if (!empty($addPages)): ?>
					<div class="add-links-box add-pages-links">
						<h3 class="add-item-menu-title btn" rel="pages">Страницы</h3>
						<div class="add-links-body" style="display: none;">

							<div class="checks-panel">
								<ul>
									<?php foreach($addPages as $item): ?>
										<li><input id="pages-<?php echo $item['id']; ?>" class="pages-checkbox" type="checkbox" data-title="<?php echo $item['title'] ?>" value="<?php echo $HTTP .''. $item['uri']; ?>.html"> <label for="pages-<?php echo $item['id']; ?>"><?php echo $item['title']; ?> (<?php echo strtoupper($item['lang']); ?>)</label> </li>
									<?php endforeach; ?>
								</ul>
							</div>

							<input type="button" class="btn add_to_menu" rel="pages" value="<?php CTemplate::loc_string('add_to_menu'); ?>">
						</div>
					</div>
				<?php endif; ?>
				<!-- PAGES END -->

                <!-- CATEGORY -->
                <?php if (!empty($addCategories)): ?>
                <div class="add-links-box add-category-links">
                    <h3 class="add-item-menu-title btn" rel="category">Категории</h3>
                    <div class="add-links-body" style="display: none;">

                        <div class="checks-panel">
                            <ul>
                                <?php foreach($addCategories as $item): ?>
                                <li><input id="category-<?php echo $item['id']; ?>" class="category-checkbox" type="checkbox" data-title="<?php echo $item['title'] ?>" value="<?php echo $HTTP ?><?php if ($item['parent_path_uri']) echo $item['parent_path_uri'].'/'; ?><?php echo $item['uri']; ?>"> <label for="category-<?php echo $item['id']; ?>"><?php echo $item['title']; ?></label> </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <input type="button" class="btn add_to_menu" rel="category" value="<?php CTemplate::loc_string('add_to_menu'); ?>">
                    </div>
                </div>
                <?php endif; ?>
                <!-- CATEGORY END -->
			</div>
		</div>

		<div class="row row-right">
			<div class="dd" id="menu_child">
				<ol class="dd-list main">
					<?php if ($menu_child): ?>

						<?php function print_menu_item($menu_items, $path = 0) { ?>
							<?php $sizeOf = sizeof($menu_items) ?>
							<?php for($i = 0; $i < $sizeOf; $i++): ?>
								<?php $item = $menu_items[$i]; ?>
								<?php if ($item['path'] == $path): ?>
									<li class="dd-item" data-id="<?php echo $item['id']; ?>">
										<div class="dd-handle btn"><?php echo $item['title']; ?></div><div class="show-info" data-id="<?php echo $item['id']; ?>"><span class="caret"></span></div>
										<div class="menu-item-data item-data-<?php echo $item['id']; ?>">

											<?php CTemplate::loc_string('url'); ?>: <br>
											<input id="item-menu-url-<?php echo $item['id']; ?>" name="item-menu-data[<?php echo $item['id']; ?>][url]" type="text" class="inp" value="<?php echo $item['value'] ?>"><br>

											<?php CTemplate::loc_string('label'); ?>: <br>
											<input id="item-menu-label-<?php echo $item['id']; ?>" name="item-menu-data[<?php echo $item['id']; ?>][label]" type="text" class="inp" value="<?php echo $item['title']; ?>">

											<div class="menu-item-actions">
												<a class="item-delete" id="delete-<?php echo $item['id']; ?>" href="#"><?php CTemplate::loc_string('remove') ?></a> <span class="meta-sep"> | </span> <a class="item-cancel" id="cancel-<?php echo $item['id']; ?>" href="#"><?php CTemplate::loc_string('cancel'); ?></a>
											</div>
											<input type="hidden" id="item-menu-parent-<?php echo $item['id']; ?>" name="item-menu-data[<?php echo $item['id']; ?>][parent]" value="<?php echo $item['path']; ?>">
										</div>
										<?php
										$sizeOf1 = sizeof($menu_items);
										for($j = 0; $j < $sizeOf1; $j++) {
											if ($menu_items[$j]['path'] == $item['id']) {
												echo '<ol class="dd-list">';
												print_menu_item($menu_items, $item['id']);
												echo '</ol>';
												break;
											}
										}
										?>
									</li>
								<?php endif; ?>

							<?php endfor; ?>
						<?php } ?>

						<?php print_menu_item($menu_child, 0); ?>

					<?php endif; ?>
				</ol>
			</div>
		</div>

		<div class="clear"></div>
	</div>

	<div class="buttons">
		<?php CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<?php CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<?php CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
	</div>


<?php CForm::end(); ?>