<?php

namespace AppBundle\Export;


use AppBundle\Dao\DaoDirectory;
use AppBundle\Entity\DirFile;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\TranslatorInterface;

abstract class ExportDataAbstract implements ExportDataInterface
{
	private $manager;
	private $serializer;
	private $storage;
	private $daoDir;
	private $translator;

	public function __construct(
		EntityManagerInterface $manager,
		Serializer $serializer,
		StorageInterface $storage,
		DaoDirectory $daoDir,
		TranslatorInterface $translator
	)
	{
		$this->manager = $manager;
		$this->serializer = $serializer;
		$this->storage = $storage;
		$this->daoDir = $daoDir;
		$this->translator = $translator;
	}

	/**
	 * @param array $data
	 * @param User  $user
	 *
	 * @param       $entityClass
	 *
	 * @return User|mixed|Response|\Symfony\Component\Security\Core\User\UserInterface
	 * @throws \Exception
	 */
	public function exportToCsv(array $data, User $user, $entityClass)
	{
		$result = $this->prepareData($data);

		$export = $this->serializer->encode($result, 'csv');

		// This is made for MS Excel program on Windows to proper read end of line
		$export = $this->replaceEndChar($export);

		$fileName = $this->getFileNameWithExtension($entityClass);

		/** @var File $uploadFile */
		$uploadFile = $this->storage->create($export, 'csv');

		$dirFile = new DirFile();

		if ($this->daoDir->getUser() instanceof Response) {
			return $this->daoDir->getUser();
		} else {
			$dirFile->setOwner($this->daoDir->getUser());
		}

		$uploadFile->setOriginalName($fileName['name']);
		$uploadFile->setOriginalExtension($fileName['extension']);
		$dirFile->setFile($uploadFile);

		$this->manager->persist($dirFile);
		$this->manager->flush();

		return $dirFile;
	}

	/**
	 * @param $email
	 *
	 * @return object[]|JsonResponse
	 */
	public function findUser($email)
	{
		$user = $this->manager->getRepository('\\AppBundle\Entity\User')
			->findOneBy(array(
				'email' => $email
			));

		if (null === $user) {
			return new JsonResponse(
				$this->translator->trans(
					'user.not_found', array(), 'validators'
				),
				400
			);
		}

		return $user;
	}

	/**
	 * @param $email
	 * @param $entity
	 * @param $entityClass
	 * @return User|object[]|JsonResponse|Response|\Symfony\Component\Security\Core\User\UserInterface
	 * @throws \Exception
	 */
	public function getData($email, $entity, $entityClass)
	{
		/** @var User $user */
		$user = $this->findUser($email);

		if ($user instanceof JsonResponse) {
			return $user;
		}

		return $this->getDataFromEntity($entity, $user, $entityClass);
	}

	/**
	 * @param $export
	 *
	 * @return mixed
	 */
	public function replaceEndChar($export)
	{
		return str_replace('\n', '\r\n', $export);
	}

	/**
	 * @param      $entity
	 * @param User $user
	 * @param      $entityClass
	 * @return User|Response|\Symfony\Component\Security\Core\User\UserInterface
	 * @throws \Exception
	 */
	public function getDataFromEntity($entity, User $user, $entityClass)
	{
		$mailerEmail = '\\VendorTeam\MailerBundle\Entity\Email';
		$mailerRecipient = '\\VendorTeam\MailerBundle\Entity\Recipient';
		$mailerLog = '\\VendorTeam\MailerBundle\Entity\Log';
		$fileEntity= '\\VendorTeam\StorageBundle\Entity\File';

		if ('AppBundle\Entity\User' === $entity) {
			$data = $this->manager
				->getRepository($entity)
				->findBy(array(
					'id' => $user
				));
		} elseif (in_array($entity, array($mailerEmail, $mailerRecipient, $mailerLog))) {
			$data = $this->getDataFromMailerBundle($user, $entity);
		} elseif ($fileEntity === $entity) {
			$data = $this->getDataFromFileBundle($user);
		} else {
			$data = $this->manager
				->getRepository($entity)
				->findBy(array(
					'owner' => $user
					)
				);
		}

		return $this->exportToCsv($data, $user, $entityClass);
	}

	/**
	 * @return mixed
	 */
	public function getClassName()
	{
		$path = explode('\\', __CLASS__);

		return array_pop($path);
	}

	/**
	 * @param $entityClass
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function getFileNameWithExtension($entityClass)
	{
		$time = new DateTime();
		$time = $time->format('YmdHi');

		return array(
			'name' => substr($entityClass, 22, -10) . '_' . $time,
			'extension' => 'csv'
		);
	}

	/**
	 * @param array $objects
	 *
	 * @return array
	 */
	public function prepareData(array $objects)
	{
		$data = array();

		foreach ($objects as $object) {
			$data [] = $this->toArray($object);
		}

		return $data;
	}

	/**
	 * @param $user
	 * @param $entity
	 *
	 * @return DirFile[]
	 */
	public function getDataFromMailerBundle($user, $entity)
	{
		$mailerEmail = '\\VendorTeam\MailerBundle\Entity\Email';
		$mailerLog = '\\VendorTeam\MailerBundle\Entity\Log';
		$data = array();

		$repoRecipient = $this->manager->getRepository(Recipient::class);
		$recipients = $repoRecipient->findBy(array('emailAddress' => $user->getEmail()));

		if ($mailerEmail === $entity) {
			/** @var Recipient $recipient */
			foreach ($recipients as $recipient) {
				$data [] = $recipient->getEmail();
			}
		} elseif ($mailerLog === $entity) {
			$repoLog = $this->manager->getRepository(Log::class);
			$data = $repoLog
				->createQueryBuilder('log')
				->innerJoin('log.recipient', 'recipient')
				->where('recipient.emailAddress = :email')
				->setParameter('email', $user->getEmail())
				->getQuery()
				->getResult();
		} else {
			$data = $recipients;
		}

		return $data;
	}

	/**
	 * @param $user
	 *
	 * @return array
	 */
	public function getDataFromFileBundle($user)
	{
		$dirFileRepo = $this->manager->getRepository(DirFile::class);
		$dirFiles = $dirFileRepo->findBy(array('owner' => $user));
		$data = array();

		foreach ($dirFiles as $dirFile) {
			$data [] = $dirFile->getFile();
		}

		return $data;
	}
}