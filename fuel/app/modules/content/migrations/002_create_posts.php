<?php

namespace Fuel\Migrations;	

class Create_Posts
{
	
	public function up()
	{
		\DBUtil::create_table('posts', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'title' => array('constraint' => 50, 'type' => 'varchar'),
			'body' => array('type' => 'text'),
			'author' => array('constraint' => 11, 'type' => 'int'),			// Auth users migration uses signed id's
			'image' => array('constraint' => 255, 'type' => 'varchar'),
			'public' => array('type' => 'boolean'),
			'featured' => array('type' => 'boolean'),
			'template' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'category' => array('constraint' => 50, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
	
		\DBUtil::add_foreign_key('posts', array(
				'constraint' => 'fk_author_ps',
				'key' => 'author',
				'reference' => array(
					'table' => 'users',
					'column' => 'id',
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'CASCADE'
		));
		
		\DBUtil::add_foreign_key('posts', array(
				'constraint' => 'fk_template_ps',
				'key' => 'template',
				'reference' => array(
					'table' => 'templates',
					'column' => 'id',
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'RESTRICT'
		));
	}

	public function down()
	{
		\DBUtil::drop_foreign_key('templates', 'fk_author_ps');
		\DBUtil::drop_foreign_key('templates', 'fk_template_ps');
		\DBUtil::drop_table('templates');
		
	}
}