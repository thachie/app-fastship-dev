<?php

return [
	'font_path' => public_path('fonts/'),
    'font_data' => [
        'THSarabunNew' => [
            'R'  => 'THSarabunNew.ttf',    // regular font
            'B'  => 'THSarabunNew Bold.ttf',       // optional: bold font
            'I'  => 'THSarabunNew Italic.ttf',     // optional: italic font
            'BI' => 'THSarabunNew BoldItalic.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,    
            'useKashida' => 75, 
        ]
        // ...add as many as you want.
    ],

    'bangla' => [
            'R'  => 'THSarabunNew.ttf',    // regular font
            'B'  => 'THSarabunNew Bold.ttf',       // optional: bold font
            'I'  => 'THSarabunNew Italic.ttf',     // optional: italic font
            'BI' => 'THSarabunNew BoldItalic.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,   
            'useKashida' => 75, 
        ]

    /*'bangla' => [
            'R'  => 'SolaimanLipi.ttf',    // regular font
            'B'  => 'SolaimanLipi.ttf',       // optional: bold font
            'I'  => 'SolaimanLipi.ttf',     // optional: italic font
            'BI' => 'SolaimanLipi.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,   
            'useKashida' => 75, 
        ]*/
];