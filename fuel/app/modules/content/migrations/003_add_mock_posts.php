<?php

namespace Fuel\Migrations;	

class Add_Mock_Posts
{
	
	public function up() {
		$t_def =\Content\Model_Template::forge(array(
			'name' => 'default',
			'description' => 'Just the default HTWeb style',
			'base_template' => '',
			'content_template' => 'templates/content/default',
		));
		$t_def->save();
		
		$p1 = \Content\Model_Post::forge(array(
			'title' => 'Welcome',
			'body' => 'This is your dashboard. Here you\'ll find all information about Het Tribunaal Web you\'ll need.',
			'user_id' => 1,
			'image' => '',
			'public' => false,
			'featured' => true,
			'category' => 'dash',
			'template' => 1,
		));
		$p1->save();
	}
	
	public function down() {
		
	}
}