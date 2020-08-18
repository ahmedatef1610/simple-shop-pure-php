<?php

function lang( $phrase ) {
    static $lang = [
        //Navbar Links
        'HOME_ADMIN' 	=> 'الرئيسية',
        'CATEGORIES' 	=> 'تصنيفات',
        'ITEMS' 		=> 'العناصر',
        'MEMBERS' 		=> 'أفراد',
        'COMMENTS'		=> 'تعليقات',
        'STATISTICS' 	=> 'إحصائيات',
        'LOGS' 			=> 'سجلات',
    ];
    return  $lang[$phrase];
}

?>