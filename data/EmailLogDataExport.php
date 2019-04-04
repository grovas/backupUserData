<?php

namespace AppBundle\Export\data;


use AppBundle\Export\ExportDataAbstract;
use VendorTeam\MailerBundle\Entity\Log;

class EmailLogDataExport extends ExportDataAbstract
{
	/**
	 * @param Log $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Identyfikator'] = $object->getId();
		$data['Identyfikator email'] = $object->getEmail()->getId();
		$data['Identyfikator odbiorcy'] = $object->getRecipient()->getId();
		$data['Wiadomość'] = $object->getMessage();
		$data['Kod'] = $object->getCode();
		$data['Data utworzenia'] = null !== $object->getCreateTimestamp()
			? date('Y:m:d H:i', $object->getCreateTimestamp()) : '';

		return $data;
	}
}