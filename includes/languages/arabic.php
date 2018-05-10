<?php

function lang($phrase) {

    static $lang = array(

        // Navbar Links

        'BRAND' 	    => 'سريع',
        'HOME_ADMIN' 	=> 'موطن',
        'ABOUT' 	    => 'حول',
        'FAQ' 	        => 'أسئلة',
        'CATEGORIES' 	=> 'فئة',
        'ITEMS' 		=> 'بند',
        'MEMBERS' 		=> 'أعضاء',
        'CONTACTUS' 	=> 'اتصل بنا',
        'COMMENTS'		=> 'تعلبق',
        'STATISTICS' 	=> 'Statistics',
        'LOGS' 			=> 'Logs'
    );

    return $lang[$phrase];

}
