<?php
function lang($phrase){
	static $lang=array(
	
	'HOME'			=>'Home',
	'CATEGORY'		=>'Category',
	'CATEGORIES'	=>'Catrgories',
	'ITEMS'			=>'Items',
	'MEMBERS'		=>'Members',
	'COMMENTS'		=>'comments',
	'STATISTICS'	=>'Statistics',
	'LOGS'			=>'Logs',
	'EDIT'			=>'Edit',
	'LOGOUT'		=>'Logout',
	
	'SETTING'		=>'Setting',
	'LOGIN'  		=>'Login',
	'DASHBOARD'		=>'Dashboard',
	
	
	'ADD-MEMBER'				=>'Add Member',
	''				=>'',
	''				=>'',
	''				=>'',
	
	
	);
	
	return $lang[$phrase];
	
	}

?>