<li class="nav-item <?= $active ? 'active' : '' ?> <?= $children ? 'dropdown' : '' ?>">
	
	<?php if (!$children) : ?>
	<a class="nav-link" href="<?= $menu_item->href ?>"
		target="<?= $menu_item->target ?>"
		title="<?= $menu_item->title ?>"	
	>
	<?php else: ?>
	<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
	<?php endif; ?>
		<?= $icon ?>
		<?= __($menu_item->text) ?>
		<?= $children ? '<span class="caret"></span></a>' . $dropdownwrapper_view : '' ?>
	</a>
</li>