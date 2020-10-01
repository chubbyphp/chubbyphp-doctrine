<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Laminas\Config\Doctrine\Unit\ServiceFactory\ODM\MongoDB\Tools\Console\Command;

use Chubbyphp\Laminas\Config\Doctrine\ODM\MongoDB\Tools\Console\Command\DocumentManagerCommand;
use Chubbyphp\Laminas\Config\Doctrine\ServiceFactory\ODM\MongoDB\Tools\Console\Command\GeneratePersistentCollectionsCommandFactory;
use Chubbyphp\Mock\MockByCallsTrait;
use Doctrine\ODM\MongoDB\Tools\Console\Command\GeneratePersistentCollectionsCommand;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Chubbyphp\Laminas\Config\Doctrine\ServiceFactory\ODM\MongoDB\Tools\Console\Command\GeneratePersistentCollectionsCommandFactory
 *
 * @internal
 */
final class GeneratePersistentCollectionsCommandFactoryTest extends TestCase
{
    use MockByCallsTrait;

    public function testInvoke(): void
    {
        /** @var ContainerInterface $container */
        $container = $this->getMockByCalls(ContainerInterface::class);

        $factory = new GeneratePersistentCollectionsCommandFactory();

        $entityManagerCommand = $factory($container);

        self::assertInstanceOf(DocumentManagerCommand::class, $entityManagerCommand);

        $commandReflectionProperty = new \ReflectionProperty($entityManagerCommand, 'command');
        $commandReflectionProperty->setAccessible(true);

        self::assertInstanceOf(
            GeneratePersistentCollectionsCommand::class,
            $commandReflectionProperty->getValue($entityManagerCommand)
        );
    }
}
