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
}