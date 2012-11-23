<?php

namespace Dxapp\Doctrine\Extension;

use \Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * Doctrine extension to add prefix on table name
 *
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @package Dx.Doctrine.Extension
 * @subpackage TablePrefix
 * @link http://labs.madayaw.com
 */

class TablePrefix
{

	protected $prefix = '';

	public function __construct($prefix)
	{
		$this->prefix = (string) $prefix;
	}

	public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
	{
		$classMetadata = $eventArgs->getClassMetadata();
		$classMetadata->setTableName($this->prefix . $classMetadata->getTableName());
		foreach ($classMetadata->getAssociationMappings() as $fieldName => $mapping)
		{
			if ($mapping['type'] == \Doctrine\ORM\Mapping\ClassMetadataInfo::MANY_TO_MANY)
			{
				if (isset($classMetadata->associationMappings[$fieldName]['joinTable']['name']))
				{
					$mappedTableName = $classMetadata->associationMappings[$fieldName]['joinTable']['name'];
					$classMetadata->associationMappings[$fieldName]['joinTable']['name'] = $this->prefix . $mappedTableName;
				}
			}
		}
	}

}