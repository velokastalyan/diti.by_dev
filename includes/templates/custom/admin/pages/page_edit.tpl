<? CForm::begin($_table, 'POST', '', 'multipart/form-data'); ?>
	<div class="alert alert-info">
		<h4><? CTemplate::loc_string('warning'); ?>!</h4> <? CTemplate::loc_string('fields_marked_with'); ?> <strong><? CTemplate::loc_string('bold'); ?></strong> <? CTemplate::loc_string('has_been_required'); ?>
	</div>
	<? if($id): 
		$last = end($languages);
		reset($languages);
	?>
		<h2><? CTemplate::loc_string('page'); ?>: <? foreach ($languages as $l): ?><? if(strlen(${'data_'.$l['abbreviation'].'_title'})): ?><? echo ${'data_'.$l['abbreviation'].'_title'}; ?><? if($l['id'] !== $last['id']): ?> / <? endif; ?><? endif; ?><? endforeach; ?></h2>
	<? else: ?>
		<h2><? CTemplate::loc_string('new_page'); ?></h2>
	<? endif; ?>
	<div>&nbsp;</div>
	
	<ul class="nav nav-tabs" id="<? echo $_table; ?>_tabs">
		<? foreach ($languages as $l): ?>
			<li><a href="#page-tab-<? echo $l['id']; ?>"><? echo $l['title']; ?></a></li>
		<? endforeach; ?>
	</ul>
	
	<div class="col">
		<div class="row right_block">
			<label for="public_date"><? CTemplate::loc_string('public_date'); ?>:</label>
			<? CTemplate::input('text', 'public_date', 'public_date', 'inp inpDate'); ?> &nbsp;&nbsp;&nbsp; <? CTemplate::input('text', 'hours', 'hours', 'inp inp25'); ?> : <? CTemplate::input('text', 'minutes', 'minutes', 'inp inp25'); ?> : <? CTemplate::input('text', 'seconds', 'seconds', 'inp inp25'); ?><br />
			<label for="hours" class="sign" style="margin-left: 195px;"><? CTemplate::loc_string('hours'); ?></label> <label for="minutes" class="sign" style="margin-left: 5px;"><? CTemplate::loc_string('minutes'); ?></label> <label for="seconds" class="sign" style="margin-left: 5px;"><? CTemplate::loc_string('seconds'); ?></label> 
		</div>
		<div class="row">
			<label for="status"><strong><? CTemplate::loc_string('status'); ?>:</strong></label>
			<? CTemplate::select('status', 'status', 'sel admin-sel'); ?>
		</div>
	</div>
	
	<div class="tab-content">
		<? foreach ($languages as $l): ?>
			<? CTemplate::input('hidden', 'data_'.$l['abbreviation'].'_id', 'data_'.$l['abbreviation'].'_id'); ?>
			<? CTemplate::input('hidden', 'data_'.$l['abbreviation'].'_language_id', 'data_'.$l['abbreviation'].'_language_id'); ?>
			<script type="text/javascript">
			$(function(){
				$('#data_<? echo $l['abbreviation']; ?>_title').change(function(){
					$('#save').attr('disabled', true);
					call('Inputs', 'generate_uri_ereg', [$(this).val()]).listen(function(res){
						setTimeout(function(){$('#save').attr('disabled', false);}, 150);
						if(res.errors)
						{
							$('#data_<? echo $l['abbreviation']; ?>_uri').val('');
							alert(res.errors);
							return false;
						}
						$('#data_<? echo $l['abbreviation']; ?>_uri').val(res.response);
					});
                    $('#data_<? echo $l['abbreviation']; ?>_save_data').attr({checked: true});
				});
				
				$('#data_<? echo $l['abbreviation']; ?>_is_ready').change(function(){
					if($(this).is(':checked'))
						$('#data_<? echo $l['abbreviation']; ?>_save_data').attr({
							checked: true,
							readonly: true
						});
					<? if(!intval(${'data_'.$l['abbreviation'].'_id'})): ?>
						else $('#data_<? echo $l['abbreviation']; ?>_save_data').attr({readonly: false});
					<? endif; ?>
				});
				<? if(intval(${'data_'.$l['abbreviation'].'_id'})): ?>
					$('#data_<? echo $l['abbreviation']; ?>_save_data').attr({checked: true, readonly: true});
				<? endif; ?>
			});
			</script>
			<div id="page-tab-<? echo $l['id']; ?>" class="tab-pane">
				<div class="ui-separator">&nbsp;</div>
				<div class="col">
					<? if($id): ?>
						<h2><? CTemplate::loc_string('page'); ?>: <? echo ${'data_'.$l['abbreviation'].'_title'}; ?></h2>
					<? endif; ?>
					<div>&nbsp;</div>
					<div class="row">
						<label for="data_<? echo $l['abbreviation']; ?>_save_data"><? CTemplate::loc_string('save_data'); ?>:</label>
						<? CTemplate::checkbox('data_'.$l['abbreviation'].'_save_data', 'data_'.$l['abbreviation'].'_save_data', 'inp'); ?>
					</div>
					<div class="row">
						<label for="data_<? echo $l['abbreviation']; ?>_is_ready"><? CTemplate::loc_string('translate_is_ready'); ?>:</label>
						<? CTemplate::checkbox('data_'.$l['abbreviation'].'_is_ready', 'data_'.$l['abbreviation'].'_is_ready', 'inp'); ?>
					</div>
					<div class="row">
						<label for="data_<? echo $l['abbreviation']; ?>_title"><strong><? CTemplate::loc_string('title'); ?>:</strong></label>
						<? CTemplate::input('text', 'data_'.$l['abbreviation'].'_title', 'data_'.$l['abbreviation'].'_title', 'inp'); ?>
					</div>
					<div class="row">
						<label for="data_<? echo $l['abbreviation']; ?>_uri"><strong><? CTemplate::loc_string('uri'); ?>:</strong></label>
						<? CTemplate::input('text', 'data_'.$l['abbreviation'].'_uri', 'data_'.$l['abbreviation'].'_uri', 'inp'); ?>
					</div>
					<div class="row">
						<label for="data_<? echo $l['abbreviation']; ?>_description"><? CTemplate::loc_string('description'); ?>:</label>
						<? CTemplate::htmlarea('data_'.$l['abbreviation'].'_description', 'data_'.$l['abbreviation'].'_description'); ?>
					</div>
					<div class="row">
						<label for="data_<? echo $l['abbreviation']; ?>_meta_title"><strong><? CTemplate::loc_string('meta_title'); ?>:</strong></label>
						<? CTemplate::input('text', 'data_'.$l['abbreviation'].'_meta_title', 'data_'.$l['abbreviation'].'_meta_title', 'inp'); ?>
					</div>
					<div class="row">
						<label for="data_<? echo $l['abbreviation']; ?>_meta_description"><strong><? CTemplate::loc_string('meta_description'); ?>:</strong></label>
						<? CTemplate::textarea('data_'.$l['abbreviation'].'_meta_description', 'data_'.$l['abbreviation'].'_meta_description', 'inp'); ?>
					</div>
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<div class="buttons">
		<? CTemplate::submit('save', 'save', CTemplate::get_loc_string('btn_save'), 'btn btn-small'); ?>
		<? CTemplate::reset('reset', 'reset', CTemplate::get_loc_string('btn_reset'), 'btn btn-small btn-warning'); ?>
		<? CTemplate::button('close', 'close', CTemplate::get_loc_string('btn_close'), 'btn btn-small'); ?>
	</div>
	
<? CForm::end(); ?>
