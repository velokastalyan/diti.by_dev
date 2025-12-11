<div class="breadcrumb <? if(!$titles || empty($titles)): ?>inv<? endif; ?>">
	<? foreach ($titles as $key => $title): ?>
		<? if($is_current[$key]): ?>
			<span class="act"><? echo $title ?></span>
		<? else: ?>
			<a href="<? echo $HTTP; ?>admin/?r=<? echo $links[$key] ?>" title="<? CDeprecatedTemplate::loc_string('go_to'); ?> <? echo $title ?>"><? echo $title ?></a> <span class="separator">&nbsp;</span>
		<? endif; ?>
	<? endforeach; ?>
</div>