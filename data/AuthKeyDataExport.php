<?php

namespace AppBundle\Export\data;


use AppBundle\Export\ExportDataAbstract;
use VendorTeam\ApiBundle\Model\AuthenticationKey;

class AuthKeyDataExport extends ExportDataAbstract
{
	/**
	 * @param AuthenticationKey $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Identyfikator'] = $object->getId();
		$data['Wartość'] = $object->getValue();
		$data['Aktywny'] = 1 == $object->getActive() ? 'tak' : 'nie';
		$data['Data ostatniego użycia'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['Data utworzenia'] = null !== $object->getCreateDate() ? $object->getCreateDate()->format('Y:m:d H:i') : '';
		$data['Data modyfikacji'] = null !== $object->getUpdateDate() ? $object->getUpdateDate()->format('Y:m:d H:i') : '';

		return $data;
	}
}