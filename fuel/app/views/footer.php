<hr/>
<footer>
	<p class="pull-left"><span class="fa fa-bank"></span> <?=__('product_name')?> 
		<small><?=__('dev')?> Melcher © 2016-<?=date('Y')?></small>
	</p>	
	<span class="pull-right">
		<a href="https://github.com/MelcherSt/HTweb" target="_blank"><i class="fa fa-github"></i> <?=__('github')?> </a> | 
		<?=__('fuel')?> |
		<strong><?=\FUEL::$env.' / '.\Utils::current_branch() . ' / ' . \Utils::get_short_head()?></strong>
	</span>
</footer>