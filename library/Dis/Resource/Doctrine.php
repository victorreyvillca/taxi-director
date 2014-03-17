<?php
/**
 * Doctrine resource for DIST 3
 *
 * @package Dis
 * @subpackage Resource
 * @category Dist
 * @author Victor Villca <victor.villca@people-trust.com>
 * @copyright Copyright (c) 2013 Gisof A/S
 * @license Proprietary
 */

class Dis_Resource_Doctrine extends Zend_Application_Resource_ResourceAbstract {

	/**
	 * Configuration options for Doctrine.
	 * @var \Doctrine\ORM\Configuration()
	 */
	protected $_config;

	/**
	 * (non-PHPdoc)
	 * @see Zend_Application_Resource_Resource::init()
	 * @return Doctrine\ORM\EntityManager
	 */
	public function init() {
		$options = $this->getOptions();
		$this->_config = new \Doctrine\ORM\Configuration();

		$this->_initClassLoader();

		// Metadata driver
		$this->_initMetadataDriver();

		// Sets up caches
		$this->_initCache();

		// Proxy configuration
		$this->_initProxy();

		// Database connection information
		$connectionOptions = $this->_initConnection();

		// Create EntityManager
		$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $this->_config);
		Zend_Registry::set('em', $em);

		//Creates Event Manager for Entity Manager
		$evm = $em->getEventManager();

		// Table Prefix (by now the table prefix is tbl it can change some time)
		$tablePrefix = new DoctrineExtensions\TablePrefix('tbl');
		$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);

		return $em;
	}

	/**
	 *
	 * Inits class loader
	 * @return void
	 */
	protected function _initClassLoader() {
		$options = $this->getOptions();
		// Entities
		// The namespace of the classes to load.
		// $includePath The base include path to use.
		$classLoader = new \Doctrine\Common\ClassLoader(
			$options['metadata']['entitiesPathNamespace'],
			$options['metadata']['entitiesPath']
		);
		$classLoader->register();

		// Proxies
		// The namespace of the classes to load.
		// $includePath The base include path to use.
		$classLoader = new \Doctrine\Common\ClassLoader(
			$options['proxy']['namespace'],
			$options['proxy']['directory']
		);
		$classLoader->register();

		// Repositories
		$classLoader = new \Doctrine\Common\ClassLoader(
			$options['repository']['namespace'],
			$options['repository']['directory']
		);
		$classLoader -> register();

		// Extensions
		$classLoader = new \Doctrine\Common\ClassLoader(
			$options['doctrine_extensions']['namespace'],
			$options['doctrine_extensions']['directory']
		);
		$classLoader -> register();
	}

	/**
	 *
	 * Initializes metadata driver from resource options.
	 * @return void
	 */
	protected function _initMetadataDriver() {
		$options = $this->getOptions();
		$mappingPaths = $options['metadata']['mappingPaths'];
		$driver = $this->_config->newDefaultAnnotationDriver($mappingPaths);
		$this->_config->setMetadataDriverImpl($driver);
	}

	/**
	 *
	 * Initializes Doctrine cache configuration from resource options.
	 * @return void
	 */
	protected function _initCache() {
		$options = $this->getOptions();
		switch($options['cache']) {
			case 'apc':
				$cache = new \Doctrine\Common\Cache\ApcCache();
			break;

			case 'memcache':
				$cache = new \Doctrine\Common\Cache\MemcacheCache();
			break;

			case 'xcache':
				$cache = new \Doctrine\Common\Cache\XcacheCache();
			break;

			default:
				$cache = new \Doctrine\Common\Cache\ArrayCache();
		}

// 		$this->_config->setMetadataCacheImpl($cache);
// 		$this->_config->setQueryCacheImpl($cache);
	}

	/**
	 *
	 * Initializes Doctrine proxy configuration from resource options.
	 * @return void
	 */
	protected function _initProxy() {
		$options = $this->getOptions();
		$this->_config->setAutoGenerateProxyClasses(isset($options['proxy']['autoGenerateProxyClasses']) ? $options['proxy']['autoGenerateProxyClasses'] : true);
		$this->_config->setProxyDir(isset($options['proxy']['directory']) ? $options['proxy']['directory'] : APPLICATION_PATH . '/Model/Proxies');
		$this->_config->setProxyDir(isset($options['proxy']['directory']) ? $options['proxy']['directory'] : APPLICATION_PATH . '/Model/Proxies');
		$this->_config->setProxyNamespace(isset($options['proxy']['namespace']) ? $options['proxy']['namespace'] : 'Model\Proxies');
	}

	/**
	 *
	 * Initializes Doctrine connection configuration from resource options.
	 * @return void
	 */
	protected function _initConnection() {
		$options = $this->getOptions();
		return $options['connection'];
	}
}