<?php

declare(strict_types=1);

namespace Ux2Dev\Speedy\Resources;

use Ux2Dev\Speedy\Dto\Request\Clients\ContractInfoRequest;
use Ux2Dev\Speedy\Dto\Request\Clients\CreateContactRequest;
use Ux2Dev\Speedy\Dto\Request\Clients\GetClientRequest;
use Ux2Dev\Speedy\Dto\Request\Clients\GetContactByExternalIdRequest;
use Ux2Dev\Speedy\Dto\Request\Clients\GetContractClientsRequest;
use Ux2Dev\Speedy\Dto\Request\Clients\GetOwnClientIdRequest;
use Ux2Dev\Speedy\Dto\Response\Clients\ContractInfo;
use Ux2Dev\Speedy\Dto\Response\Clients\CreateContactResponse;
use Ux2Dev\Speedy\Dto\Response\Clients\GetClientResponse;
use Ux2Dev\Speedy\Dto\Response\Clients\GetContactByExternalIdResponse;
use Ux2Dev\Speedy\Dto\Response\Clients\GetContractClientsResponse;
use Ux2Dev\Speedy\Dto\Response\Clients\GetOwnClientIdResponse;
use Ux2Dev\Speedy\Resources\Resource;

final class Clients extends Resource
{
    public function getOwnClientId(GetOwnClientIdRequest $request, ?string $language = null, ?int $clientSystemId = null): GetOwnClientIdResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/client', $body, GetOwnClientIdResponse::class);
    }

    public function getClient(GetClientRequest $request, ?string $language = null, ?int $clientSystemId = null): GetClientResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/client/getById', $body, GetClientResponse::class);
    }

    public function getContractClients(GetContractClientsRequest $request, ?string $language = null, ?int $clientSystemId = null): GetContractClientsResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/client/contract', $body, GetContractClientsResponse::class);
    }

    public function createContact(CreateContactRequest $request, ?string $language = null, ?int $clientSystemId = null): CreateContactResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/client/contact', $body, CreateContactResponse::class);
    }

    public function getContactByExternalId(GetContactByExternalIdRequest $request, ?string $language = null, ?int $clientSystemId = null): GetContactByExternalIdResponse
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/client/contact/getByExternalId', $body, GetContactByExternalIdResponse::class);
    }

    public function contractInfo(ContractInfoRequest $request, ?string $language = null, ?int $clientSystemId = null): ContractInfo
    {
        $body = $request->toArray();
        if ($language !== null) $body['language'] = $language;
        if ($clientSystemId !== null) $body['clientSystemId'] = $clientSystemId;

        return $this->transport->postJson('/client/contractInfo', $body, ContractInfo::class);
    }
}