<h2><? CDeprecatedTemplate::loc_string('welcome_to_admin_panel'); ?></h2>
<ul class="nav nav-tabs" id="myTab">
  <li class="active"><a href="#home">Home</a></li>
  <li><a href="#profile">Profile</a></li>
  <li><a href="#messages">Messages</a></li>
  <li><a href="#settings">Settings</a></li>
</ul>
<? CForm::begin('test'); ?>
	<div class="tab-content">
		<div class="tab-pane active" id="home">
			<? CControl::process('AdminFilters', 'test1'); ?>
			<div class="col">
				<div class="row">
					<? CControl::process('AdminUploader', 'test'); ?>
                </div>
            </div>
		</div>
		<div class="tab-pane" id="profile">...sdf</div>
		<div class="tab-pane" id="messages">..dfgfdgdf.</div>
		<div class="tab-pane" id="settings">..asdasdasda.</div>
	</div>
	<div class="buttons">
		<? CTemplate::submit('uploading', 'uploading', CTemplate::get_loc_string('uploading'), 'btn btn-small'); ?>
    </div>
<? CForm::end(); ?>
<script>
	$('#myTab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})
</script>
