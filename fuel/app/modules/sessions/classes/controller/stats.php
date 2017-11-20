<?php

namespace Sessions;

class Controller_Stats extends \Controller_Core_Theme {
	
	public function action_index() {	
		$this->push_js('sessions-stats');
		
		$data['next_cook'] = Controller_Stats_Api::_fetch_stats()['next_cook']['name'];
		$data['checksum'] = Controller_Stats_Api::_fetch_stats()['checksum'];
		
		$this->title = __('session.stats.title');
		$this->content = \View::forge('stats', $data);
	}
}