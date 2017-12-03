<?php

namespace Fuel\Migrations;

/**
 * Create menu items table.
 */
class Create_MenuItems
{
	/**
	 * Array of all the current menu items to insert into db.
	 * @var array
	 */
	public $menu_items = [
		['text' => 'main', 'root' => true, 'public' => false],
		['text' => 'session.title', 'href' => '/sessions', 'icon' => 'fa fa-cutlery', 'parent_id' => 1],
		['text' => 'product.title', 'href' => '/products', 'icon' => 'fa fa-shopping-basket', 'parent_id' => 1],
		['text' => 'receipt.title', 'href' => '/receipts', 'icon' => 'fa fa-money', 'parent_id' => 1],
		['text' => 'user.wall.title', 'href' => '/wall', 'icon' => 'fa fa-id-card', 'parent_id' => 1],
		['text' => 'session.stats.title', 'href' => '/sessions/stats', 'icon' => 'fa fa-area-chart', 'parent_id' => 1],		
		
		['text' => 'main-public', 'root' => true, 'public' => true],
	];
	
	
	public function up() {
		\DBUtil::create_table('menu_items', [
			'id' => ['constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true],
			'text' => ['constraint' => 100, 'type' => 'varchar'],
			'title' => ['constraint' => 100, 'type' => 'varchar', 'null' => true],
			'icon' => ['constraint' => 100, 'type' => 'varchar', 'null' => true],
			'href' => ['constraint' => 255, 'type' => 'varchar', 'default' => '#'],
			'target' => ['constraint' => 20, 'type' => 'varchar', 'default' => '_self'],
			'parent_id' => ['constraint' => 11, 'type' => 'int', 'unsigned' => true, 'null' => true],
			'public' => ['type' => 'boolean', 'default' => false],
			'root' => ['type' => 'boolean', 'default' => false],
		], ['id']);
		
		// Create FK	
		\DBUtil::add_foreign_key('menu_items', [
			'constraint' => 'fk_menu_parent',
			'key' => 'parent_id',
			'reference' => [
				'table' => 'menu_items',
				'column' => 'id',
			],
			'on_update' => 'CASCADE',
			'on_delete' => 'CASCADE'
		]);
		
		// Insert the default menu items
		foreach($this->menu_items as $item) {
			\DB::insert('menu_items')->set($item)->execute();
		}
	}

	public function down() {
		\DBUtil::drop_foreign_key('menu_items', 'fk_menu_parent');
		\DBUtil::drop_table('menu_items');
	}
}
