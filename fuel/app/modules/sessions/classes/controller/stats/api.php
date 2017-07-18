<?php

namespace Sessions;

class Controller_Stats_Api extends \Api\Controller_Auth {
	
	const CACHE_KEY = 'stats';
	
	public function action_index() {
		return static::_fetch_stats()['results'];
	}
	
	public function action_cook() {
		return static::_fetch_stats()['next_cook'];
	}
	
	public static function _fetch_stats() {
		try {
			$data = \Cache::get(static::CACHE_KEY);
		} catch (\CacheNotFoundException $ex) {
			// Data expired or not available, fetch it online
			$data = static::_calculate_stats();
			// Put data in cache to prevent unneeded requests (expires in 5 hours)
			\Cache::set(static::CACHE_KEY, $data,  3600 * 5);
		}
		return $data;
	}
	
	private static function _calculate_stats() {	
		$active_users = \Model_User::get_by_state();
		
		$min_points;
		$next_cook = '';
		$result = [];
		foreach($active_users as $user) {
			$settleable_points = 0;
			$user_id = $user->id;

			$settleable_enrollments = \Sessions\Model_Enrollment_Session::get_settleable($user_id);
			foreach($settleable_enrollments as $enrollment) {
				$settleable_points += $enrollment->get_point_prediction(true);
			}

			$count = $user->points;
			$total_points = $count + $settleable_points;	
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