<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Customer
	|--------------------------------------------------------------------------
	|
	| The name of the customer.
	|
	*/
	'customer' => 'JUST',
	'customer-image' => 'http://wearejust.com/apple-touch-icon-precomposed.png',

	/*
	|--------------------------------------------------------------------------
	| Menu structure
	|--------------------------------------------------------------------------
	|
	| The structure of the main menu
	|
	*/
	'menu' => array(
        array(
            'title' => 'Pagina\'s',
            'url' => 'pages',
            'children' => array(

            )
        ),
        array(
            'title' => 'CMS-gebruikers',
            'url' => '#',
            'children' => array(
                array(
                    'title' => 'Gebruikers',
                    'url' => 'users',
                ),
                array(
                    'title' => 'Groepen',
                    'url' => 'groups',
                ),
            ),
		),
	)
);