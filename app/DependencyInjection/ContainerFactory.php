<?php declare(strict_types=1);

namespace App\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ContainerFactory
{
    public function create(): ContainerInterface
    {
        $kernel = new Kernel(getenv('APP_ENV'), (bool) getenv('APP_DEBUG'));
        $kernel->boot();
        // this is require to keep CLI verbosity independent on AppKernel dev/prod mode
        putenv('SHELL_VERBOSITY=0');
        return $kernel->getContainer();
    }
}