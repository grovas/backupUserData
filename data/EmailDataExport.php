<?php

namespace AppBundle\Export\data;


use AppBundle\Export\ExportDataAbstract;
use VendorTeam\MailerBundle\Entity\Email;

class EmailDataExport extends ExportDataAbstract
{

	/**
	 * @param Email $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Identyfikator'] = $object->getId();
		$data['Temat'] = $object->getSubject();
		$data['Treść (html)'] = $object->getHtmlContent();
		$data['Treść (text)'] = $object->getPlainContent();
		$data['Został wysłany'] = 1 == $object->getStatus() ? 'tak' : 'nie';
		$data['Konfiguracja dostawcy poczty'] = $object->getMailerName();
		$data['Data utworzenia'] = null !== $object->getCreateTimestamp()
			? date('Y:m:d H:i', $object->getCreateTimestamp()) : '';
		$data['Data wysyłki'] = null !== $object->getShipTimestamp()
			? date('Y:m:d H:i', $object->getShipTimestamp()) : '';

		return $data;
	}
}