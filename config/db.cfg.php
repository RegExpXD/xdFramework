<?php
return array(
	'xyb' => array(
		'master' => array(
			array(
				'host' => 'localhost',
				'port' => '3306',
				'user' => 'root',
				'db'   => 'test',
				'passwd' => 'root',
				'charset' => 'utf8'
				)
			),
		'slave'  => array(
			array(
				'host' => 'localhost',
				'port' => '3306',
				'user' => 'root',
				'db'   => 'slave',
				'passwd' => 'root',
				'charset' => 'utf8',
				'weight' => 10
				),
			array(
				'host' => 'localhost',
				'port' => '3306',
				'user' => 'root',
				'db'   => 'slave',
				'passwd' => 'root',
				'charset' => 'utf8',
				'weight' => 20
				)
			)
		)
	);