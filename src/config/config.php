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
	'customer' => 'Just',
	
	'translation' => false,

	/*
	|--------------------------------------------------------------------------
	| Menu structure
	|--------------------------------------------------------------------------
	|
	| The structure of the main menu
	|
	*/
	'menu-prefix' => '',
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
	),
	'super-admin-menu' => array()
);
