<?php
return array(
	'xyb' => array(
		'master' => array(
			array(
				'host' => 'localhost',
				'user' => 'root',
				'passwd' => '123456',
				'charset' => 'utf8'
				)
			),
		'slave'  => array(
			array(
				'host' => 'localhost',
				'user' => 'root',
				'passwd' => '123456',
				'charset' => 'utf8',
				'weight' => 10
				),
			array(
				'host' => 'localhost',
				'user' => 'root',
				'passwd' => '123456',
				'charset' => 'utf8',
				'weight' => 20
				)
			)
		)
	);