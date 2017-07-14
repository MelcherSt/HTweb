<?php
namespace Sessions;

class Presenter_Overview extends \Presenter {
	
	public function view() {
		$this->set('sessions', Model_Session::get_by_user(\Auth::get_user()->id, true));
	}
	
	public function admin() {
		$this->set('sessions', Model_Session::query()->where('settled', 0)->get());
		$this->set('hide_colors', true);
		$this->set('is_admin', true);
	}
}
