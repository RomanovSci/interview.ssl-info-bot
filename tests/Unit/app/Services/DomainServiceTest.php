<?php

namespace Tests\app\Services;

use App\Services\DomainService;
use GuzzleHttp\Client;
use Tests\TestCase;

class DomainServiceTest extends TestCase
{
    public function testConstruct()
    {
        /** @var Client $client */
        $client = $this->getMockBuilder(Client::class)
            ->setMethods()
            ->getMock();

        $domainServiceMock = new DomainServiceMock($client);
        $this->assertInstanceOf(Client::class, $domainServiceMock->client);
    }

    public function testGetClient()
    {
        /** @var Client $client */
        $client = $this->getMockBuilder(Client::class)
            ->setMethods()
            ->getMock();

        $domainServiceMock = new DomainServiceMock($client);
        $this->assertInstanceOf(Client::class, $domainServiceMock->getClient());
    }

    public function testValidate()
    {
        $domain = 'test.com';

        /** @var Client $client */
        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['request'])
            ->getMock();

        $client->expects($this->once())
            ->method('request')
            ->willReturn(new class {
                public function getStatusCode()
                {
                    return 200;
                }
            });

        /** @var DomainServiceMock $domainServiceMock */
        $domainServiceMock = $this->getMockBuilder(DomainServiceMock::class)
            ->disableOriginalConstructor()
            ->setMethods(['getClient'])
            ->getMock();

        $domainServiceMock->expects($this->once())
            ->method('getClient')
            ->willReturn($client);

        $this->assertSame(true, $domainServiceMock->validate($domain));
    }

    public function testGetCertificateInfo()
    {
        $domain = 'test.com';

        /** @var DomainServiceMock $domainServiceMock */
        $domainServiceMock = $this->getMockBuilder(DomainServiceMock::class)
            ->disableOriginalConstructor()
            ->setMethods()
            ->getMock();

        $this->assertInternalType('array', $domainServiceMock->getCertificateInfo($domain));
    }
}

class DomainServiceMock extends DomainService
{
    public $client;

    public function getClient(): Client
    {
        return parent::getClient();
    }
}