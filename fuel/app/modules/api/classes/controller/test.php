<?php

namespace Api;

class Controller_Test extends \Controller {
	
	public function action_index($page=null) {
		$config = array(
			'pagination_url' => 'http://localhost:8000/api/test/',
			'total_items'    => 10,
			'per_page'       => 5,
			'uri_segment'    => 3,
			// or if you prefer pagination by query string
			//'uri_segment'    => 'page',
		);

		// Create a pagination instance named 'mypagination'
		$pagination = \Pagination::forge('mypagination', $config);

		$data['data'] = \DB::select('id', 'date')
									->from('sessions')
									->limit($pagination->per_page)
									->offset($pagination->offset)
									->execute()
									->as_array();

		// we pass the object, it will be rendered when echo'd in the view
		$data['pagination'] = $pagination;

		// return the view
		return \View::forge('index', $data);
	}
}
