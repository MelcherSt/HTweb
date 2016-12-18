<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo Asset::css('bootstrap.min.css');


foreach($data as $item) {
	echo $item['id'];
	echo '<br>';
}
	
// fetch a previously forged instance, and render it
echo Pagination::instance('mypagination')->render();
