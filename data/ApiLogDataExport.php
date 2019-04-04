<?php

namespace AppBundle\Export\data;


use AppBundle\Export\ExportDataAbstract;
use VendorTeam\ApiBundle\Entity\Log;

class ApiLogDataExport extends ExportDataAbstract
{
	/**
	 * @param Log $object
	 *
	 * @return mixed
	 */
	public function toArray($object)
	{
		$data = array();

		$data['identifier'] = $object->getId();
		$data['request_uri'] = $object->getValue();
		$data['request_user_agent'] = 1 == $object->getActive() ? 'tak' : 'nie';
		$data['request_ip_address'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['request_headers'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['request_get_method_parameters'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['request_post_method_parameters'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['request_other_parameters'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['response_status_code'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['response_headers'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['response_content'] = null !== $object->getLastUseDate() ? $object->getLastUseDate()->format('Y:m:d H:i') : '';
		$data['create_date'] = null !== $object->getCreateDate() ? $object->getCreateDate()->format('Y:m:d H:i') : '';
		$data['update_date'] = null !== $object->getUpdateDate() ? $object->getUpdateDate()->format('Y:m:d H:i') : '';

		return $data;
	}
}