<div id="container_<? echo $id; ?>" class="plupload-container">
	<? if($options['is_image']): ?>
		<img id="upload_preview_<? echo $id; ?>" src="<? if($options['output_dir'] && $current_file && file_exists(ROOT.$options['output_dir'].$current_file)): ?><? echo $HTTP.$options['output_dir'].$current_file; ?><? elseif(file_exists($options['upload_path'].$current_file)): ?><? echo $options['upload_url'].$current_file; ?><? endif; ?>" width="150" class="<? if(!$options['output_dir'] || !$current_file): ?>inv<? endif; ?> img-polaroid" /><br /><br />
	<? else: ?>
		<h5><a id="upload_preview_<? echo $id; ?>" href="<? if($options['output_dir'] && $current_file && file_exists(ROOT.$options['output_dir'].$current_file)): ?><? echo $HTTP.$options['output_dir'].$current_file; ?><? elseif(file_exists($options['upload_path'].$current_file)): ?><? echo $options['upload_url'].$current_file; ?><? endif; ?>" class="<? if(!$options['output_dir'] || !$current_file): ?>inv<? endif; ?>"><? CTemplate::loc_string('download'); ?></a></h5>
	<? endif; ?>
	<? if($options['note']): ?>
		<div class="control-group error">
	<? endif; ?>
	<a id="btn_upload_<? echo $id; ?>" class="btn btn-mini btn-primary"><? CTemplate::loc_string('upload'); ?></a> <span id="uploader_standard_<? echo $id; ?>"rel="tooltip" data-placement="right" data-original-title="<? CTemplate::loc_string('uploader_used_standart'); ?>" class="inv label label-warning"></span>
	<? if($options['note']): ?>	
			<span class="help-inline"><? echo $options['note']; ?></span>
		</div>
	<? endif; ?>
	<? CTemplate::input('hidden', 'uploader_id_'.$id, 'uploader_'.$options['input_name']); ?>
	<? CTemplate::input('hidden', 'uploader_input_'.$id, $options['input_name']); ?>
	<h6 id="uploaded_file_<? echo $id; ?>"></h6>
	<div class="progress progress-striped active">
		<div id="progress_bar_<? echo $id; ?>" class="bar" style="width: 40%;"></div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	//$('#uploader_input_<? echo $id; ?>').val('');
	var uploader_<? echo $id; ?> = new plupload.Uploader({
		runtimes : '<? echo $options['standarts']; ?>',
		browse_button : 'btn_upload_<? echo $id; ?>',
		container: 'container_<? echo $id; ?>',
		max_file_size : '<? echo $options['max_file_size']; ?>',
		multi_selection : <? echo (($options['type'] !== 'simple') ? 1 : 0); ?>,
		url : '<? echo $HTTP; ?>ajax/admin/upload.php',
		//resize : {width : 320, height : 240, quality : 90},
		flash_swf_url : '<? echo $JS; ?>plupload/plupload.flash.swf',
		silverlight_xap_url : '<? echo $JS; ?>plupload/plupload.silverlight.xap',
		filters : <? echo json_encode($options['file_types']); ?>
	});
	
	uploader_<? echo $id; ?>.bind('Init', function(up, params) {
		$('#uploader_standard_<? echo $id; ?>').html(params.runtime);
		$('#progress_bar_<? echo $id; ?>').width('0%');
	});
	
	uploader_<? echo $id; ?>.bind('QueueChanged', function(up) {
		var files = uploader_<? echo $id; ?>.files
		for (var i in files) {
			$('#uploaded_file_<? echo $id; ?>').html('' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b>').fadeIn();
		}
		$('#progress_bar_<? echo $id; ?>').width('0%').parent().fadeIn();
		uploader_<? echo $id; ?>.start();
	});
	
	uploader_<? echo $id; ?>.bind('UploadProgress', function(up, file) {
		$('#progress_bar_<? echo $id; ?>').width(file.percent +'%');
	});
	
	uploader_<? echo $id; ?>.bind('FileUploaded', function(up, file, data) {
		var response = $.parseJSON(data.response);
		setTimeout(function(){
			$('#uploaded_file_<? echo $id; ?>').fadeOut();
			$('#progress_bar_<? echo $id; ?>').parent().fadeOut();
			<? if($options['is_image']): ?>
				$('#upload_preview_<? echo $id; ?>').attr('src', '<? echo $options['upload_url']; ?>'+ response.result.saved_filename).fadeIn();
			<? else: ?>
				$('#upload_preview_<? echo $id; ?>').attr('href', '<? echo $options['upload_url']; ?>'+ response.result.saved_filename).fadeIn();
			<? endif; ?>
			$('#uploader_input_<? echo $id; ?>').val(response.result.saved_filename);
		}, 800);
	});
	
	uploader_<? echo $id; ?>.init();
})
</script>