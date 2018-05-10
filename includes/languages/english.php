<?php

	function lang($phrase) {

		static $lang = array(

			// Navbar Links

			'BRAND' 	    => 'Quick',
			'HOME_ADMIN' 	=> 'Home',
			'ABOUT' 	    => 'About',
			'FAQ' 	        => 'Faq',
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
