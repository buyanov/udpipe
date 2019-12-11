<?php declare(strict_types=1);
use App\DependencyInjection\ContainerFactory;

// Build DI container
$containerFactory = new ContainerFactory();

return $containerFactory->create();