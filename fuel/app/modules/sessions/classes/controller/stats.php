<?php

namespace Sessions;

class Controller_Stats extends \Controller_Core_Theme {
	
	public function action_index() {	
		$this->push_js('sessions-stats');
		
		$data['next_cook'] = json_decode(\Request::forge('sessions/stats/api/cook')->execute())->name;
		
		$this->title = __('session.stats.title');
		$this->content = \View::forge('stats', $data);
	}
}