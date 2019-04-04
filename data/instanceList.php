<?php

$instances = array(
	array(
		'className' => '\\VendorTeam\ApiBundle\Entity\AuthenticationKey',
		'exporterName' => 'AppBundle\Export\data\AuthKeyDataExport'
	),
	array(
		'className' => 'AppBundle\Entity\Device',
		'exporterName' => 'AppBundle\Export\data\DeviceDataExport'
	),
	array(
		'className' => 'AppBundle\Entity\Directory',
		'exporterName' => 'AppBundle\Export\data\DirectoryDataExport'
	),
	array(
		'className' => 'AppBundle\Entity\DirFile',
		'exporterName' => 'AppBundle\Export\data\DirFileDataExport'
	),
	array(
		'className' => '\\VendorTeam\MailerBundle\Entity\Email',
		'exporterName' => 'AppBundle\Export\data\EmailDataExport'
	),
	array(
		'className' => '\\VendorTeam\MailerBundle\Entity\Recipient',
		'exporterName' => 'AppBundle\Export\data\RecipientDataExport'
	),
	array(
		'className' => '\\VendorTeam\MailerBundle\Entity\Log',
		'exporterName' => 'AppBundle\Export\data\EmailLogDataExport'
	),
	array(
		'className' => '\\VendorTeam\StorageBundle\Entity\File',
		'exporterName' => 'AppBundle\Export\data\FileDataExport'
	),
	array(
		'className' => 'AppBundle\Entity\User',
		'exporterName' => 'AppBundle\Export\data\UserDataExport'
	),
);