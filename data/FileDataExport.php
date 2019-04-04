<?php

namespace AppBundle\Export\data;


use AppBundle\Export\ExportDataAbstract;
use VendoTeam\StorageBundle\Entity\File;

class FileDataExport extends ExportDataAbstract
{
	/**
	 * @param File $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Identyfikator'] = $object->getId();
		$data['display_name'] = $object->getDisplayName();
		$data['short_description'] = $object->getShortDescription();
		$data['long_description'] = $object->getLongDescription();
		$data['type'] = $object->getType();
		$data['storage_name'] = $object->getStorageName();
		$data['name'] = $object->getName();
		$data['extension'] = $object->getExtension();
		$data['mime_type'] = $object->getMimeType();
		$data['checksum'] = $object->getChecksum();
		$data['size'] = $object->getSize();
		$data['original_name'] = $object->getOriginalName();
		$data['original_name_alias'] = $object->getOriginalNameAlias();
		$data['original_extension'] = $object->getOriginalExtension();
		$data['original_mime_type'] = $object->getOriginalMimeType();
		$data['original_size'] = $object->getSize();
		$data['status'] = $object->getStatus();
		$data['extension'] = $object->getExtension();
		$data['Data utworzenia'] = null !== $object->getCreateDate() ? $object->getCreateDate()->format('Y:m:d H:i') : '';
		$data['Data modyfikacji'] = null !== $object->getUpdateDate() ? $object->getUpdateDate()->format('Y:m:d H:i') : '';

		return $data;
	}
}