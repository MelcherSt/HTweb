<?php
namespace Content;

class Model_Post extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'title',
		'body',
		'user_id',
		'image' => ['default' => ''],
		'public',
		'featured',
		'category',
		'template',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);
	
	protected static $_table_name = 'posts';
	
	protected static $_belongs_to = array(
		'author' => array(
			'key_from' => 'user_id',
			'key_to' => 'id',
			'model_to' => '\Model_User',
		),
		'template' => array(
			'key_from' => 'template',
			'key_to' => 'id',
			'model_to' => '\Content\Model_Template',
		)
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('title', 'Title', 'required|max_length[50]');
		$val->add_field('author', 'Author', 'required|max_length[50]');
		return $val;
	}
	
	/**
	 * Retrieve the latest featured post
	 * @return \Content\Model_Post
	 */
	public static function get_first_featured() {
		return Model_Post::query()
			->where('featured', true)
			->order_by('created_at', 'asc')
			->get_one();
	}
	
	/**
	 * Retrieve the latest public featured post
	 * @return \Content\Model_Post
	 */
	public static function get_first_public_featured() {
		return Model_Post::query()
			->where('featured', true)
			->where('public', true)
			->order_by('created_at', 'asc')
			->get_one();
	}
	
	/**
	 * Get the first two sentences of the body. 
	 * @return string
	 */
	public function get_excerpt() {
		$content = $this->body;
		$pos = strpos($content, '.');
		if($pos === false) {
			return $content;
		} else {
			 $offset = $pos + 1; //prepare offset
			$pos2 = stripos ($content, '.', $offset); //find second dot using offset
			return substr($content, 0, $pos2+1);
		}
	}

}
