<? if(!$navi_is_empty): ?>
	<script type="text/javascript">
	$(function(){
		$("#left_nav .section:first").addClass('top');
		$("#left_nav .section, #left_nav .subsection, #left_nav .branch li").hover(function(){$(this).addClass('hover')}, function(){$(this).removeClass('hover')});
		$("#left_nav .section .pm").click(function(){
			if($(this).parent().hasClass('expand'))
				$(this).parent().removeClass('expand')
			else
				$(this).parent().addClass('expand')
				
			return false;
		});
		$('#left_nav .section.active .sub-sect li ul li.active:first-child').addClass('no-top-b');
		$('#left_nav .section.active .sub-sect li ul li.active:last-child').addClass('no-bot-b');
	});

	</script>
	<div class="left_block">
		<div class="head">
			<a href="<? echo $HTTP; ?>"><? escape($site_name); ?></a> <span class="link">-</span> 
			<a href="<? echo $HTTP; ?>admin/"><? CDeprecatedTemplate::loc_string('admin_panel'); ?></a>
		</div>
		<? foreach ($navi as $value): ?>
			<? if($value['is_act']): ?>
				<div class="section expand active <? if(!$value['children'] || empty($value['children'])): ?>empty<? endif; ?>">
			<? else: ?>
				<div class="section">
			<? endif; ?>
				<a href="<? echo $value['link']; ?>" class="sect-link"><? echo $value['name']; ?></a>
				<? if(!empty($value['children'])): ?>
					<a href="javascript:void(0);" class="pm">&nbsp;</a>
					<ul class="sub-sect">
						<? foreach ($value['children'] as $s_child): ?>
							<li class="<? if($s_child['is_act']): ?>active <? if(!empty($s_child['children'])): ?>expand<? endif; ?><? endif; ?>"><a href="<? echo $s_child['link']; ?>" class="sect-link"><? echo $s_child['name']; ?></a>
								<? if(!empty($s_child['children'])): ?>
									<a href="javascript:void(0);" class="pm">&nbsp;</a>
									<ul>
										<? foreach ($s_child['children'] as $gs_child): ?>
											<li class="<? if($gs_child['is_act']): ?>active<? endif; ?>"><a href="<? echo $gs_child['link']; ?>" class="sect-link"><? echo $gs_child['name']; ?></a></li>
										<? endforeach; ?>
									</ul>
								<? endif; ?>
							</li>
						<? endforeach; ?>
					</ul>
				<? endif; ?>
			</div>
		<? endforeach; ?>
	</div>
<? else: ?>

<? endif; ?>