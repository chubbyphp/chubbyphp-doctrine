<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Laminas\Config\Doctrine\Unit\ServiceFactory\ODM\MongoDB;

use Chubbyphp\Laminas\Config\Doctrine\ServiceFactory\ODM\MongoDB\DocumentManagerFactory;
use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\EventManager;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\ClassMetadataFactory;
use Doctrine\ODM\MongoDB\Repository\RepositoryFactory;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;
use ProxyManager\Factory\LazyLoadingGhostFactory;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Laminas\Config\Doctrine\ServiceFactory\ODM\MongoDB\DocumentManagerFactory
 *
 * @internal
 */
final class DocumentManagerFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var Client $client */
        $client = $this->getMockByCalls(Client::class, [
            Call::create('getTypeMap')->with()->willReturn(DocumentManager::CLIENT_TYPEMAP),
        ]);

        /** @var Cache $cache */
        $cache = $this->getMockByCalls(Cache::class);

        /** @var LazyLoadingGhostFactory $lazyLoadingGhostFactory */
        $lazyLoadingGhostFactory = $this->getMockByCalls(LazyLoadingGhostFactory::class);

        /** @var RepositoryFactory $repositoryFactory */
        $repositoryFactory = $this->getMockByCalls(RepositoryFactory::class);

        /** @var Configuration $configuration */
        $configuration = $this->getMockByCalls(Configuration::class, [
            Call::create('getClassMetadataFactoryName')->with()->willReturn(ClassMetadataFactory::class),
            Call::create('getMetadataCacheImpl')->with()->willReturn($cache),
            Call::create('getHydratorDir')->with()->willReturn('/tmp/doctrine/orm/hydrators'),
            Call::create('getHydratorNamespace')->with()->willReturn('DoctrineMongoDBODMHydrator'),
            Call::create('getAutoGenerateHydratorClasses')->with()->willReturn(Configuration::AUTOGENERATE_ALWAYS),
            Call::create('buildGhostObjectFactory')->with()->willReturn($lazyLoadingGhostFactory),
            Call::create('getRepositoryFactory')->with()->willReturn($repositoryFactory),
        ]);

        /** @var EventManager $eventManager */
        $eventManager = $this->getMockByCalls(EventManager::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('has')->with(Client::class)->willReturn(true),
            Call::create('get')->with(Client::class)->willReturn($client),
            Call::create('has')->with(Configuration::class)->willReturn(true),
            Call::create('get')->with(Configuration::class)->willReturn($configuration),
            Call::create('has')->with(EventManager::class)->willReturn(true),
            Call::create('get')->with(EventManager::class)->willReturn($eventManager),
        ]);

        $factory = new DocumentManagerFactory();

        $service = $factory($container);

        self::assertInstanceOf(DocumentManager::class, $service);
    }

    public function testCallStatic(): void
    {
        /** @var Client $client */
        $client = $this->getMockByCalls(Client::class, [
            Call::create('getTypeMap')->with()->willReturn(DocumentManager::CLIENT_TYPEMAP),
        ]);

        /** @var Cache $cache */
        $cache = $this->getMockByCalls(Cache::class);

        /** @var LazyLoadingGhostFactory $lazyLoadingGhostFactory */
        $lazyLoadingGhostFactory = $this->getMockByCalls(LazyLoadingGhostFactory::class);

        /** @var RepositoryFactory $repositoryFactory */
        $repositoryFactory = $this->getMockByCalls(RepositoryFactory::class);

        /** @var Configuration $configuration */
        $configuration = $this->getMockByCalls(Configuration::class, [
            Call::create('getClassMetadataFactoryName')->with()->willReturn(ClassMetadataFactory::class),
            Call::create('getMetadataCacheImpl')->with()->willReturn($cache),
            Call::create('getHydratorDir')->with()->willReturn('/tmp/doctrine/orm/hydrators'),
            Call::create('getHydratorNamespace')->with()->willReturn('DoctrineMongoDBODMHydrator'),
            Call::create('getAutoGenerateHydratorClasses')->with()->willReturn(Configuration::AUTOGENERATE_ALWAYS),
            Call::create('buildGhostObjectFactory')->with()->willReturn($lazyLoadingGhostFactory),
            Call::create('getRepositoryFactory')->with()->willReturn($repositoryFactory),
        ]);

        /** @var EventManager $eventManager */
        $eventManager = $this->getMockByCalls(EventManager::class);

        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class, [
            Call::create('has')->with(Client::class.'default')->willReturn(true),
            Call::create('get')->with(Client::class.'default')->willReturn($client),
            Call::create('has')->with(Configuration::class.'default')->willReturn(true),
            Call::create('get')->with(Configuration::class.'default')->willReturn($configuration),
            Call::create('has')->with(EventManager::class.'default')->willReturn(true),
            Call::create('get')->with(EventManager::class.'default')->willReturn($eventManager),
        ]);

        $factory = [DocumentManagerFactory::class, 'default'];

        $service = $factory($container);

        self::assertInstanceOf(DocumentManager::class, $service);
    }
}
