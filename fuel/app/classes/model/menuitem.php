<?php

/**
 * A year group
 */
class Model_MenuItem extends \Orm\Model {

	protected static $_properties = array(
		'text',
		'title',
		'icon',
		'href',
		'target',
		'parent_id',
		'public',
		'root'
	);
	protected static $_has_many = array(
		'children' => array(
			'key_from' => 'id',
			'model_to' => 'Model_MenuItem',
			'key_to' => 'parent_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		)
	);

	public static function validate($factory) {
		$val = Validation::forge($factory);
		$val->add_field('text', 'Tekst', 'required|max_length[100]');
		$val->add_field('title', 'Titel', 'max_length[100]');
		$val->add_field('icon', 'Icoon', 'max_length[100]');
		$val->add_field('href', 'Link', 'max_length[255]');
		$val->add_field('target', 'Doel', 'max_length[20]');
		return $val;
	}

	/**
	 * Get the menu root with the given name.
	 * @param string $menu_root_name
	 * @return \Model_MenuItem
	 */
	public static function find_root(string $menu_root_name) {
		$query = \Model_MenuItem::query()
			->where('root', true)
			->where('text', $menu_root_name);
		
		// Deny guest access to private menu roots
		if (\Context_Base::_is_guest()) {
			$query->where('public', true);
		}
				
		return $query->get_one();
	}

	/**
	 * Get the menu item that best matches the URIs.
	 * @param string $uri
	 * @return \Model_MenuItem
	 */
	public static function find_match(string $uri): ?Model_MenuItem {
		$result = \DB::query('select * from menu_items where ' . \DB::escape($uri) . ' like concat(href, \'%\') order by length(href) desc limit 1;')
					->execute();
		return empty($result[0]) ? null : Model_MenuItem::forge($result[0]);
	}

	/**
	 * Get ids of all items above the given item (and the item itself).
	 * @param Model_MenuItem $menu_item
	 * @return array Model_MenuItem
	 */
	public static function find_item_chain(Model_MenuItem $menu_item) : array {
		$results = \DB::query('
			SELECT id		
			FROM (
				SELECT
					@r AS _id,
					(SELECT @r := parent_id FROM menu_items WHERE id = _id) AS parent_id,
					@l := @l + 1 AS lvl
				FROM
					(SELECT @r := ' . $menu_item->id . ', @l := 0) vars,
					menu_items m
				WHERE @r <> 0) T1
			JOIN menu_items T2
			ON T1._id = T2.id
			ORDER BY T1.lvl DESC; ')
				->execute()->as_array('id');
		return array_keys($results);
	}

}
