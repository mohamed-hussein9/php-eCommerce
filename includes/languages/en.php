<?php
function lang($phrase)
{
	static $lang = array(

		'HOME'			=> 'Home',
		'CATEGORY'		=> 'Category',
		'CATEGORIES'	=> 'Catrgories',
		'ITEMS'			=> 'Items',
		'MEMBERS'		=> 'Members',
		'COMMENTS'		=> 'comments',
		'STATISTICS'	=> 'Statistics',
		'LOGS'			=> 'Logs',
		'EDIT'			=> 'Edit',
		'LOGOUT'		=> 'Logout',
		'SETTING'		=> 'Setting',
		'LOGIN'  		=> 'Login',
		'DASHBOARD'		=> 'Dashboard',
		'ADD-MEMBER'	=> 'Add Member',
		'PROFILE'		=> 'profile',
		'ADD-ITEM'		=> 'Add Item',
		'SIGN-UP'		=> 'Sign Up',
		'PROFILE'		=> 'Profile',
		'LOGIN'			=> 'Login',
		'ADD-NEW-ITEM'	=> 'Add New Item',


	);

	return $lang[$phrase];
}
