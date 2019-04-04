<?php

namespace AppBundle\Export\data;


use AppBundle\Entity\Directory;
use AppBundle\Export\ExportDataAbstract;

class DirectoryDataExport extends ExportDataAbstract
{
	/**
	 * @param Directory $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Identyfikator'] = $object->getId();
		$data['Identyfikator katalogu nadrzędnego'] = $object->getParentDir()->getId()->toString();
		$data['Nazwa'] = $object->getName();
		$data['Katalog główny'] = 1 == $object->getRootDir() ? 'tak' : 'nie';
		$data['Oznaczony do usunięcia'] = 1 == $object->getMarkToRemove() ? 'tak' : 'nie';
		$data['Data utworzenia'] = null !== $object->getCreateDate() ? $object->getCreateDate()->format('Y:m:d H:i') : '';
		$data['Data modyfikacji'] = null !== $object->getUpdateDate() ? $object->getUpdateDate()->format('Y:m:d H:i') : '';

		return $data;
	}
}