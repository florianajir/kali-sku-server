<?php
/**
 * This file is part of kali-server
 *
 * (c) Florian Ajir <https://github.com/florianajir/kali-server>
 */
namespace Meup\Bundle\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SkuControllerTest
 *
 * @author Florian Ajir <florianajir@gmail.com>
 */
class SkuControllerTest extends WebTestCase
{
    /**
     * @var \Meup\Bundle\BifrostBundle\Entity\Client
     */
    private $client;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $clientManager = $this->getClientManager();
        $this->client = $clientManager->createClient();
        $this->client->setAllowedGrantTypes(array('client_credentials'));
        $this->client->setName('test');
        $clientManager->updateClient($this->client);
        parent::setUp();
    }

    public function tearDown()
    {
        $clientManager = $this->getClientManager();
//        $clientManager->deleteClient($this->client);
        parent::tearDown();
    }

    /**
     * @return \Meup\Bundle\BifrostBundle\Doctrine\ClientManager
     */
    private function getClientManager()
    {
        return static::$kernel->getContainer()->get('meup_api.manager.client_manager');
    }

    public function testGetSkuWithoutAuthentification()
    {
        $client = static::createClient();
        $client->request('GET', '/api/132456789');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testGetSku()
    {
        $token = $this->authenticateAndReturnAccessToken();
        $client = static::createClient();
        $client->request(
            'GET',
            '/api/132456789',
            array(),
            array(),
            array('HTTP_Authorization' => 'Bearer '.$token)
        );
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    private function authenticateAndReturnAccessToken()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            sprintf(
                '/oauth/v2/token?client_id=%s_%s&client_secret=%s&grant_type=client_credentials',
                $this->client->getId(),
                $this->client->getRandomId(),
                $this->client->getSecret()
            )
        );
        $response = json_decode($client->getResponse()->getContent(), true);

        return $response['access_token'];
    }
}
