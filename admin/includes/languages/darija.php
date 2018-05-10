<?php

	function lang($phrase) {

		static $lang = array(

			// Navbar Links

			'HOME_ADMIN' 	=> 'Home',
			'CATEGORIES' 	=> 'Categories',
			'ITEMS' 		=> 'Items',
			'MEMBERS' 		=> 'Members',
			'CONTACTUS' 	=> 'contactUS',
			'GATEGORIES' 	=> 'Categories',
			'COMMENTS'		=> 'Comments',
			'STATISTICS' 	=> 'Statistics',
			'LOGS' 			=> 'Logs'
		);

		return $lang[$phrase];

	}
