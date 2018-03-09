<li class="<?= $active ? 'active' : '' ?> <?= $children ? 'dropdown' : '' ?>">
	
	<?php if (!$children) : ?>
	<a href="<?= $menu_item->href ?>"
		target="<?= $menu_item->target ?>"
		title="<?= $menu_item->title ?>"	
	>
	<?php else: ?>
	<a class="dropdown-toggle" data-toggle="dropdown" href="#">
	<?php endif; ?>
		<?= $icon ?>
		<span class="hidden-sm"><?= __($menu_item->text) ?></span>
		<?= $children ? '<span class="caret"></span></a>' . $dropdownwrapper_view : '' ?>
	</a>
</li>