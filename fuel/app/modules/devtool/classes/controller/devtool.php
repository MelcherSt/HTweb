<?php

namespace DevTool;

class Controller_DevTool extends \Controller {
	
	/**
	 * Get the name of the current branch
	 */
	function action_branch() { 
		try {
			$gitFile = file('../.git/HEAD', FILE_USE_INCLUDE_PATH);
			$branchName = explode("/", $gitFile[0], 3)[2];	
		} catch (Exception $ex) {
			$branchName = 'unknown branch';
		}
		
		return \Response::forge($branchName);
	}
	
	function action_hash() { 
		try {
			$gitFile = file('../.git/ORIG_HEAD', FILE_USE_INCLUDE_PATH);
			$commitHash = $gitFile[0];
		} catch (Exception $ex) {
			$commitHash = 'unknown commit hash';
		}
		
		return \Response::forge($commitHash);
	}
	
	function action_lang() {
		\Config::set('language', 'nl');
		
		// Pre-load all localization files
		\Lang::load('template'); 
		\Lang::load('session', 'session');
		\Lang::load('user', 'user');
		\Lang::load('actions', 'actions');	
		\Lang::load('alert', 'alert');
		\Lang::load('content', 'content');
		\Lang::load('dashboard', 'dashboard');
		\Lang::load('stats', 'stats'); 
		
		\Response::redirect('/');
	}
}