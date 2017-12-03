<?php
/**
 * Utility class for menu creation. Default values will render the theme's main menu with the default navbar partials.
 * @author Melcher
 */
class MenuFactory {

	/**
	 * Defines the maximum menu depth to render.
	 * @var int
	 */
	private $max_depth = 1;

	/**
	 * Parent (root) of all menu items are to be processed.
	 * @var \Model_MenuItem 
	 */
	private $menu_root_item;
	
	/**
	 * Currently active item id's.
	 * @var array List of integer.
	 */
	private $active_menu_items = [];
	
	/**
	 * Path to menu item view.
	 * @var string
	 */
	public $menuitem_viewname = 'partials/navbar/menuitem';
	
	/**
	 * Path to dropdown wrapper view.
	 * @var string
	 */
	public $dropwrapper_viewname = 'partials/navbar/dropwrapper';
	
	/**
	 * Path to sub menu item view.
	 * @var string
	 */
	public $submenuitem_viewname = 'partials/navbar/menuitem';
	
	/**
	 * 
	 * @param string $menu_root_name Name of the root menu item to use to render the menu.
	 * @param int $max_depth Maximal depth of menu item children nesting. Only render menu items up to this depth. Default is 1.
	 * @return \MenuFactory this
	 */
	public static function forge(string $menu_root_name, int $max_depth = 1) : \MenuFactory {
		return new \MenuFactory($menu_root_name, $max_depth);
	}
	
	public function __construct(string $menu_root_name, int $max_depth = 1) {
		$this->max_depth = $max_depth;
		$menu_root_item = \Model_MenuItem::find_root($menu_root_name);
		if (empty($menu_root_item)) {
			throw new Exception('Menu root item with name ' . $menu_root_name . ' does not exist!');
		}
		$this->menu_root_item = $menu_root_item;
	}

	/**
	 * Render a single menu item (and possibly its children) and return the HTML.
	 * @param Model_MenuItem $menu_item The model of the menu item to render.
	 * @param int $depth The depth at which the item exists in the hierarchy.
	 * @param bool $render_children Whether to render the menu item's children.
	 * @return string HTML
	 */
	private function render_item(Model_MenuItem $menu_item, int $depth = 0, 
			bool $render_children = false): string {
		if ($depth > $this->max_depth) { return ''; } // Max depth reached
		
		$active = in_array($menu_item->id, $this->active_menu_items);	
		$children = $render_children && !empty($menu_item->children);
		$icon = $menu_item->icon ? html_tag('span', ['class' => $menu_item->icon]).' ' : '';
		
		$theme = \Theme::instance();
		
		// Only render dropdown if necessary
		if ($children) {
			$dropdownwrapper_view = $theme->view($this->dropwrapper_viewname, 
			['menu_item' => $menu_item, 
				'children' => $this->render_items($menu_item->children, $depth + 1), 
				'active' => $active], false);		
		} else {
			$dropdownwrapper_view = '';
		}
		
		// Mod(2) operator since we only want to render dropdowns at uneven depths	
		$menuitem_view = $theme->view(($depth % 2 == 0) ? $this->menuitem_viewname : $this->submenuitem_viewname,
			['menu_item' => $menu_item,	'icon' => $icon, 'children' => $children, 
				'active' => $active, 'dropdownwrapper_view' => $dropdownwrapper_view], false
		);
		
		// Combine and return
		return $menuitem_view;
	}
	
	/**
	 * Render all given items and return the generated HTML.
	 * @param array $menu_items Array of menu item models to render.
	 * @param int $depth The depth at which the items exists in the hierarchy.
	 * @return string HTML
	 */
	private function render_items(array $menu_items, int $depth = 0, 
			bool $render_children = true): string {
		$result = [];
		
		foreach ($menu_items as $menu_item) {
			if ($menu_item instanceof \Model_MenuItem) {
				$result[] = $this->render_item($menu_item, $depth, $render_children);
			}
		}
		return implode('', $result);
	}

	/**
	 * Calculate the current activity state for menu items.
	 */
	private function update_active_items() {
		$uri = '/'.implode('/', \Uri::segments());
		$match = \Model_MenuItem::find_match($uri);
		$this->active_menu_items = empty($match) ? [] : \Model_MenuItem::find_item_chain($match);
	}
	
	/**
	 * Renders menu to HTML for use in the view starting with the root menu item. <br> 
	 * Before rendering activity state for menu items is recalculated.
	 */
	public function render(): string {
		$this->update_active_items();
		return $this->render_items($this->menu_root_item->children);
	}

	public function __toString(): string {
		return $this->render();
	}

}
