<ul class="paginator dbnavsize">
	<li><? CDeprecatedTemplate::loc_string('items_on_page'); ?>:</li>
	<? foreach ($sizes as $s): ?>
		 <? if($s == $size_act): ?>
			<li class="act"><span><? echo $s; ?></span></li>
		<? else: ?>
			<li><a href="<? echo $link; ?><? echo $s; ?>" title="<? CDeprecatedTemplate::loc_string('dbnav_size_show'); ?> <? echo $s; ?> <? CDeprecatedTemplate::loc_string('dbnav_size_items'); ?>"><? echo $s; ?></a></li>
		<? endif; ?>
	<? endforeach; ?>
</ul>