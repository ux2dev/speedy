<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Ux2Dev\Speedy\Config\SpeedyConfig;
use Ux2Dev\Speedy\Http\SpeedyTransport;

/**
 * Framework-agnostic entry point for the Speedy SDK. Instantiate once per
 * account with a PSR-18 client + PSR-17 factories, then dispatch requests
 * via the resource accessors ($speedy->shipment(), etc.).
 */
final class Speedy
{
    public readonly SpeedyTransport $transport;

    // <generated:properties>
    // </generated:properties>

    public function __construct(
        SpeedyConfig $config,
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
    ) {
        $this->transport = new SpeedyTransport($config, $httpClient, $requestFactory, $streamFactory);
    }

    // <generated:accessors>
    // </generated:accessors>
}
