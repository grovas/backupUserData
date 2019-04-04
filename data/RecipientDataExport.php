<?php

namespace AppBundle\Export\data;


use AppBundle\Export\ExportDataAbstract;
use VendorTeam\MailerBundle\Entity\Recipient;

class RecipientDataExport extends ExportDataAbstract
{
	/**
	 * @param Recipient $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Identyfikator'] = $object->getId();
		$data['Identyfikator email'] = $object->getEmail()->getId();
		$data['Nazwa'] = $object->getName();
		$data['Został wysłany'] = 1 == $object->getStatus() ? 'tak' : 'nie';
		$data['Data utworzenia'] = null !== $object->getCreateTimestamp()
			? date('Y:m:d H:i', $object->getCreateTimestamp()) : '';
		$data['Data wysyłki'] = null !== $object->getShipTimestamp()
			? date('Y:m:d H:i', $object->getShipTimestamp()) : '';

		return $data;
	}
}