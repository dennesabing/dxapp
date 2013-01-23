<?php

namespace Dxapp\Doctrine\Entity;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\EventArgs;
//use Dx\Doctrine\Mapping\ExtensionMetadataFactory;

/**
 * Extension of Doctrine Event Listener
 *
 * @author Dennes B Abing <dennes.b.abing@gmail.com>
 * @package Dx.Doctrine.Entity
 * @subpackage Listeners
 * @link http://labs.madayaw.com
 */
abstract class Listeners implements EventSubscriber
{
    /**
     * Static List of cached object configurations
     * leaving it static for reasons to look into
     * other listener configuration
     *
     * @var array
     */
    protected static $configurations = array();

    /**
     * Listener name, etc: sluggable
     *
     * @var string
     */
    protected $name;

    /**
     * ExtensionMetadataFactory used to read the extension
     * metadata through the extension drivers
     *
     * @var Gedmo\Mapping\ExtensionMetadataFactory
     */
    private $extensionMetadataFactory = array();

    /**
     * List of event adapters used for this listener
     *
     * @var array
     */
    private $adapters = array();

    /**
     * Custom annotation reader
     *
     * @var object
     */
    private $annotationReader;

    /**
     * @var \Doctrine\Common\Annotations\AnnotationReader
     */
    private static $defaultAnnotationReader;

    /**
     * Constructor
     */
    public function __construct()
    {
        $parts = explode('\\', $this->getNamespace());
        $this->name = end($parts);
    }

    /**
     * Get an event adapter to handle event specific
     * methods
     *
     * @param EventArgs $args
     * @throws \Gedmo\Exception\InvalidArgumentException - if event is not recognized
     * @return \Gedmo\Mapping\Event\AdapterInterface
     */
    protected function getEventAdapter(EventArgs $args)
    {
        $class = get_class($args);
        if (preg_match('@Doctrine\\\([^\\\]+)@', $class, $m) && in_array($m[1], array('ODM', 'ORM'))) {
            if (!isset($this->adapters[$m[1]])) {
                $adapterClass = $this->getNamespace() . '\\Doctrine\\Mapping\\Event\\Adapter\\' . $m[1];
                if (!class_exists($adapterClass)) {
                    $adapterClass = 'Dx\\Doctrine\\Mapping\\Event\\Adapter\\'.$m[1];
                }
                $this->adapters[$m[1]] = new $adapterClass;
            }
            $this->adapters[$m[1]]->setEventArgs($args);
            return $this->adapters[$m[1]];
        } else {
            throw new \Dx\Exception\InvalidArgumentException('Event mapper does not support event arg class: '.$class);
        }
    }

    /**
     * Get the configuration for specific object class
     * if cache driver is present it scans it also
     *
     * @param ObjectManager $objectManager
     * @param string $class
     * @return array
     */
    public function getConfiguration(ObjectManager $objectManager, $class) {
        $config = array();
        if (isset(self::$configurations[$this->name][$class])) {
            $config = self::$configurations[$this->name][$class];
        } else {
            $factory = $objectManager->getMetadataFactory();
            $cacheDriver = $factory->getCacheDriver();
            if ($cacheDriver) {
                $cacheId = ExtensionMetadataFactory::getCacheId($class, $this->getNamespace());
                if (($cached = $cacheDriver->fetch($cacheId)) !== false) {
                    self::$configurations[$this->name][$class] = $cached;
                    $config = $cached;
                } else {
                    // re-generate metadata on cache miss
                    $this->loadMetadataForObjectClass($objectManager, $factory->getMetadataFor($class));
                    if (isset(self::$configurations[$this->name][$class])) {
                        $config = self::$configurations[$this->name][$class];
                    }
                }

                $objectClass = isset($config['useObjectClass']) ? $config['useObjectClass'] : $class;
                if ($objectClass !== $class) {
                    $this->getConfiguration($objectManager, $objectClass);
                }

            }
        }
        return $config;
    }

    /**
     * Get extended metadata mapping reader
     *
     * @param ObjectManager $objectManager
     * @return Gedmo\Mapping\ExtensionMetadataFactory
     */
    public function getExtensionMetadataFactory(ObjectManager $objectManager)
    {
        $oid = spl_object_hash($objectManager);
        if (!isset($this->extensionMetadataFactory[$oid])) {
            if (is_null($this->annotationReader)) {
                // create default annotation reader for extensions
                $this->annotationReader = $this->getDefaultAnnotationReader();
            }
            $this->extensionMetadataFactory[$oid] = new ExtensionMetadataFactory(
                $objectManager,
                $this->getNamespace(),
                $this->annotationReader
            );
        }
        return $this->extensionMetadataFactory[$oid];
    }

    /**
     * Set annotation reader class
     * since older doctrine versions do not provide an interface
     * it must provide these methods:
     *     getClassAnnotations([reflectionClass])
     *     getClassAnnotation([reflectionClass], [name])
     *     getPropertyAnnotations([reflectionProperty])
     *     getPropertyAnnotation([reflectionProperty], [name])
     *
     * @param object $reader - annotation reader class
     */
    public function setAnnotationReader($reader)
    {
        $this->annotationReader = $reader;
    }

    /**
     * Scans the objects for extended annotations
     * event subscribers must subscribe to loadClassMetadata event
     *
     * @param ObjectManager $objectManager
     * @param object $metadata
     * @return void
     */
    public function loadMetadataForObjectClass(ObjectManager $objectManager, $metadata)
    {
        $factory = $this->getExtensionMetadataFactory($objectManager);
        try {
            $config = $factory->getExtensionMetadata($metadata);
        } catch (\ReflectionException $e) {
            // entity\document generator is running
            $config = false; // will not store a cached version, to remap later
        }
        if ($config) {
            self::$configurations[$this->name][$metadata->name] = $config;
        }
    }

    /**
     * Get the namespace of extension event subscriber.
     * used for cache id of extensions also to know where
     * to find Mapping drivers and event adapters
     *
     * @return string
     */
    abstract protected function getNamespace();

    /**
     * Create default annotation reader for extensions
     *
     * @return \Doctrine\Common\Annotations\AnnotationReader
     */
    private function getDefaultAnnotationReader()
    {
        return self::$defaultAnnotationReader;
    }
}
