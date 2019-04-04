<?php

namespace AppBundle\Export;


use AppBundle\Entity\User;

interface ExportDataInterface
{
	/**
	 * @param array $data
	 * @param User  $user
	 * @param       $entityClass
	 * @return mixed
	 */
	public function exportToCsv(array $data, User $user, $entityClass);

	/**
	 * @return mixed
	 */
	public function getClassName();

	/**
	 * @param $email
	 * @param $entity
	 * @param $entityClass
	 * @return mixed
	 */
	public function getData($email, $entity, $entityClass);

	/**
	 * @param      $entity
	 * @param User $user
	 * @param      $entityClass
	 * @return mixed
	 */
	public function getDataFromEntity($entity, User $user, $entityClass);

	/**
	 * @param $entityClass
	 * @return mixed
	 */
	public function getFileNameWithExtension($entityClass);

	/**
	 * @param $email
	 * @return mixed
	 */
	public function findUser($email);
	/**
	 * @param array $objects
	 * @return mixed
	 */
	public function prepareData(array $objects);

	/**
	 * @param $export
	 * @return mixed
	 */
	public function replaceEndChar($export);

	/**
	 * @param $object
	 * @return mixed
	 */
	public function toArray($object);
}