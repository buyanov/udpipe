<?php declare(strict_types=1);

namespace App\DependencyInjection;

use App\DependencyInjection\CompilerPass\CollectCommandsToApplicationCompilerPass;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\HttpKernel\Log\Logger;

final class Kernel extends BaseKernel
{

    protected $name = "svLoggerKernel";

    public function __construct($app_env = 'local', $debug = true)
    {

        // debug: require to invalidate container on service files change
        parent::__construct($app_env, $debug);
    }
    /**
     * In more complex app, add bundles here
     */
    public function registerBundles()
    {
        return [];
    }

    /**
     * Load all services
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/../../config/services.yml');
    }

    protected function build(ContainerBuilder $containerBuilder): void
    {
        $containerBuilder
            ->addCompilerPass(new CollectCommandsToApplicationCompilerPass);

        $containerBuilder->setAlias(LoggerInterface::class, 'logger')
            ->setPublic(false);

        if ($containerBuilder->has('logger')) {
            return;
        }

        $containerBuilder->register('logger', Logger::class)
            ->setPublic(false);

    }

}
