<?php

namespace AppBundle\Export\data;


use AppBundle\Entity\User;
use AppBundle\Export\ExportDataAbstract;

class UserDataExport extends ExportDataAbstract
{
	/**
	 * @param User $object
	 *
	 * @return array
	 */
	public function toArray($object)
	{
		$data = array();

		$data['Identyfikator'] = $object->getId();
		$data['Email'] = $object->getEmail();
		$data['Zgody'] = null !== $object->getAgreements() ? $object->getAgreements() : '';
		$data['Zmiana hasła'] = true == $object->getChangePassword() ? 'tak' : 'nie';
		$data['Konto aktywne'] = 1 == $object->getActive() ? 'tak' : 'nie';
		$data['Uprawnienia'] = $object->getRoles();
		$data['Data utworzenia'] = null !== $object->getCreateDate() ? $object->getCreateDate()->format('Y:m:d H:i') : '';
		$data['Data modyfikacji'] = null !== $object->getUpdateDate() ? $object->getUpdateDate()->format('Y:m:d H:i') : '';

		return $data;
	}
}