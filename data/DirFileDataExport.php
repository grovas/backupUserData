<?php

namespace AppBundle\Export\data;


use AppBundle\Entity\DirFile;
use AppBundle\Export\ExportDataAbstract;

class DirFileDataExport extends ExportDataAbstract
{

	/**
	 * @param DirFile $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Identyfikator'] = $object->getId();
		$data['Identyfikator katalogu'] = $object->getDir()->getId()->toString();
		$data['Identyfikator'] = $object->getFile()->getId();
		$data['Oznaczony do usunięcia'] = 1 == $object->getMarkToRemove() ? 'tak' : 'nie';
		$data['Data utworzenia'] = null !== $object->getCreateDate() ? $object->getCreateDate()->format('Y:m:d H:i') : '';
		$data['Data modyfikacji'] = null !== $object->getUpdateDate() ? $object->getUpdateDate()->format('Y:m:d H:i') : '';

		return $data;
	}
}