<?php

namespace AppBundle\Export;

use AppBundle\Dao\DaoDirectory;
use AppBundle\Entity\DirFile;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Translation\TranslatorInterface;
use ZipArchive;


class CollectExportData
{
	private $manager;
	private $serializer;
	private $storageExport;
	private $storageItems;
	private $daoDir;
	private $translator;
	private $baseUrl;
	private $container;

	public function __construct(
		EntityManagerInterface $manager,
		Serializer $serializer,
		StorageInterface $storageExport,
		StorageInterface $storageItems,
		DaoDirectory $daoDir,
		TranslatorInterface $translator,
		ContainerInterface $container,
		$baseUrl = ''
	)
	{
		$this->manager = $manager;
		$this->serializer = $serializer;
		$this->storageExport = $storageExport;
		$this->storageItems = $storageItems;
		$this->daoDir = $daoDir;
		$this->translator = $translator;
		$this->container = $container;
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @return mixed
	 */
	public function getInstances()
	{
		require_once __DIR__ . '/data/instanceList.php';

		return $instances;
	}

	/**
	 * @param $email
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function generateDumpData($email)
	{
		$instances = $this->getInstances();

		$dirFiles = array();

		foreach ($instances as $instance) {
			$part = new $instance['exporterName'](
				$this->manager,
				$this->serializer,
				$this->storageExport,
				$this->daoDir,
				$this->translator
			);

			$dirFiles[] = $part->getData($email, $instance['className'], $instance['exporterName']);
		}

		return $this->prepareZip($dirFiles, $email);
	}

	/**
	 * @param $email
	 *
	 * @return DirFile[]
	 */
	public function getUserDirFiles($email)
	{
		$owner = $this->findUser($email);

		return $this->manager
			->getRepository(DirFile::class)
			->findBy(array('owner' => $owner));
	}

	/**
	 * @param $dirFilesExport
	 *
	 * @param $email
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function prepareZip($dirFilesExport, $email)
	{
		$time = new DateTime();
		$time = $time->format('Ymdhi');

		$storageExportPath = $this->storageExport->getDirectoryPath();
		$storageExportAlias = $this->getAlias($storageExportPath);

		$dirFilesItems = $this->getUserDirFiles($email);

		$zip = new ZipArchive();
		$zipRawName = $this->generateName('zip');
		$zipName = $email . '-' . $time;
		$zipName = $this->replaceToUnderscore($zipName) . '.zip';

		$zipUrl = $this->baseUrl . '/uploads/' . $storageExportAlias . '/' . $zipRawName;
		$zipFullPath = $storageExportPath . '/' . $zipRawName;

		$zip->open($zipFullPath, ZipArchive::CREATE);

		/** @var DirFile $dirFile */
		foreach ($dirFilesExport as $dirFileExport) {
			$beforeCut = $this->storageExport->generatePath($dirFileExport->getFile());
			$zip->addFile($beforeCut, $dirFileExport->getFile()->getOriginalName() . '.csv');
		}

		$zip->open($zipFullPath, ZipArchive::CREATE);

		foreach ($dirFilesItems as $dirFileItem) {
			$beforeCut = $this->storageItems->generatePath($dirFileItem->getFile());
			$zip->addFile(
				$beforeCut,
				$dirFileItem->getFile()->getOriginalName()
				. '.'
				. $dirFileItem->getFile()->getOriginalExtension()
			);
		}
		$zip->close();
		ob_clean();

		return array(
			'zipUrl' => $zipUrl,
			'zipName' => $zipName
		);
	}

	/**
	 * @param null|string $extension
	 *
	 * @return string
	 * @throws \Exception
	 */
	private function generateName($extension = null)
	{
		$name = md5(\random_bytes(64));

		if (null !== $extension) {
			$name .= '.';
			$name .= $extension;
		}

		return $name;
	}

	/**
	 * @param $storagePath
	 *
	 * @return bool|string
	 */
	public function getAlias($storagePath)
	{
		return substr($storagePath, -32);
	}

	/**
	 * @param $string
	 *
	 * @return mixed
	 */
	public function replaceToUnderscore($string)
	{
		return str_replace(array('.', '-', '+'), '_', $string);
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
}