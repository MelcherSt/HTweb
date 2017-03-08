<?php

namespace Stats;

class Controller_Api extends \Api\Controller_Auth {
	
	const CACHE_KEY = 'stats';
	
	public function action_index() {
		return static::_request_stats()['results'];
	}
	
	public function action_cook() {
		return static::_request_stats()['next_cook'];
	}
	
	public static function _request_stats() {
		try {
			$data = \Cache::get(static::CACHE_KEY);
		} catch (\CacheNotFoundException $ex) {
			// Data expired or not available, fetch it online
			$data = static::_calculage_stats();
			// Put data in cache to prevent unneeded requests (expires in 5 hours)
			\Cache::set(static::CACHE_KEY, $data,  3600 * 5);
		}
		return $data;
	}
	
	private static function _calculage_stats() {	
		$active_users = \Model_User::get_by_state();
		
		$min_points;
		$next_cook = '';
		$result = [];
		foreach($active_users as $user) {
			$settleable_points = 0;
			$settleable_enrollments = \Sessions\Model_Enrollment_Session::get_settleable($user->id);
			foreach($settleable_enrollments as $enrollment) {
				$settleable_points += $enrollment->get_point_prediction(true);
			}

			$total_points = $user->points + $settleable_points;	
			if(empty($min_points) || $total_points < $min_points) {
				$min_points = $total_points;
				$next_cook = $user->name;
			}
			
			$result[] = [
				'name' => $user->name, 
				'points' => $total_points,
			];
		}		
		return ['results' => $result, 'next_cook' => ['name' => $next_cook, 'points' => $min_points]];
	}
}