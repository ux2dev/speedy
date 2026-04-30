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
    private ?\Ux2Dev\Speedy\Resources\Calculate $calculate = null;
    private ?\Ux2Dev\Speedy\Resources\Location $location = null;
    private ?\Ux2Dev\Speedy\Resources\PrintService $print = null;
    private ?\Ux2Dev\Speedy\Resources\Services $services = null;
    private ?\Ux2Dev\Speedy\Resources\Shipment $shipment = null;
    private ?\Ux2Dev\Speedy\Resources\Track $track = null;
    private ?\Ux2Dev\Speedy\Resources\Validation $validation = null;
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
    public function calculate(): \Ux2Dev\Speedy\Resources\Calculate
    {
        return $this->calculate ??= new \Ux2Dev\Speedy\Resources\Calculate($this->transport);
    }

    public function location(): \Ux2Dev\Speedy\Resources\Location
    {
        return $this->location ??= new \Ux2Dev\Speedy\Resources\Location($this->transport);
    }

    public function print(): \Ux2Dev\Speedy\Resources\PrintService
    {
        return $this->print ??= new \Ux2Dev\Speedy\Resources\PrintService($this->transport);
    }

    public function services(): \Ux2Dev\Speedy\Resources\Services
    {
        return $this->services ??= new \Ux2Dev\Speedy\Resources\Services($this->transport);
    }

    public function shipment(): \Ux2Dev\Speedy\Resources\Shipment
    {
        return $this->shipment ??= new \Ux2Dev\Speedy\Resources\Shipment($this->transport);
    }

    public function track(): \Ux2Dev\Speedy\Resources\Track
    {
        return $this->track ??= new \Ux2Dev\Speedy\Resources\Track($this->transport);
    }

    public function validation(): \Ux2Dev\Speedy\Resources\Validation
    {
        return $this->validation ??= new \Ux2Dev\Speedy\Resources\Validation($this->transport);
    }
    // </generated:accessors>
}
