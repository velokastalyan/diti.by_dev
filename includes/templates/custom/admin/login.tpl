<?CForm::begin('login_form');?>
	<h4><? CDeprecatedTemplate::loc_string('administator_panel'); ?></h4>
	<div class="row">
		<label for="email">E-mail:</label>
		<?CDeprecatedTemplate::input('text', 'email', 'email', 'inp')?>
	</div>
	<div class="row">
		<label for="password">Password:</label>
		<?CDeprecatedTemplate::input('password', 'password', 'password', 'inp')?>
	</div>
	<div class="row-chkbx">
		<?CDeprecatedTemplate::input('checkbox', 'remember_me', 'remember_me')?>
		<label for="remember_me"> - <? CDeprecatedTemplate::loc_string('remember_me'); ?></label>
	</div>
	<div class="row">
		<?CDeprecatedTemplate::submit('login_butt', 'login_butt', CDeprecatedTemplate::get_loc_string('login'), 'btn btn-small');?>
	</div>
<?CForm::end();?>