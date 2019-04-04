<?php

namespace AppBundle\Export\data;


use AppBundle\Entity\Device;
use AppBundle\Export\ExportDataAbstract;

class DeviceDataExport extends ExportDataAbstract
{

	/**
	 * @param Device $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Id'] = $object->getId();
		$data['Identyfikator'] = $object->getIdentifier();
		$data['Platforma urządzenia'] = $object->getPlatform();
		$data['Aktywne'] = 1 == $object->getActive() ? 'tak' : 'nie';
		$data['Data wygaśnięcia'] = null !== $object->getExpireDate() ? $object->getExpireDate()->format('Y:m:d H:i') : '';
		$data['Data utworzenia'] = null !== $object->getCreateDate() ? $object->getCreateDate()->format('Y:m:d H:i') : '';
		$data['Data modyfikacji'] = null !== $object->getUpdateDate() ? $object->getUpdateDate()->format('Y:m:d H:i') : '';

		return $data;
	}
}