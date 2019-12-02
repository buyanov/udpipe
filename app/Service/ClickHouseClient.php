<?php
namespace App\Service;

use ClickHouseDB\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ClickHouseClient
{
    /**
     * @var $params ParameterBag
     */

    private $client;

    public function __construct(ParameterBagInterface $params)
    {
        $this->client = new Client($params->all());
    }

    public function getClient()
    {
        return $this->client;
    }

}
