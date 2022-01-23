<?php

namespace kubokat\ApiWrapper;

use kubokat\ApiWrapper\TransportInterface;
use kubokat\ApiWrapper\ValidatorInterface;

class Api
{
    private $transport;
    private $validator;

    public function __construct(TransportInterface $transport,ValidatorInterface $validator)
    {
        $this->transport = $transport;
        $this->validator = $validator;
    }

    public function createUser(array $params = [])
    {
        $params = $this->validator
            ->validate($params, ['legal' ,'nameLocal', 'birthday', 'identity', 'emails', 'phones', 'addressLocal'])
            ->clear();

        return $this->transport->request('clientCreate', ['client' => $params]);
    }

    public function createDomain($clientId, $params = [])
    {
        $params = $this->validator
            ->validate($params, ['domain'])
            ->clear();

        $params['noCheck'] = 1;
        $params['clientId'] = $clientId;

        return $this->transport->request('domainCreate', $params);
    }

    public function getDomain($id)
    {
        return $this->transport->request('domainInfo', ['id' => $id]);
    }

    public function changeDNS($clientId, $domainId, $params)
    {

        $params['id'] = $domainId;
        $params['clientId'] = $clientId;
        $params['domain']['delegated'] = true;

        return $this->transport->request('domainUpdate', $params);
    }

}