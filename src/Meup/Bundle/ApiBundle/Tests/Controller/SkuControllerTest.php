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

    public function testGetSkuNotFound()
    {
        $client = $this->get('azerty');
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

    private function get($sku)
    {
        $token = $this->authenticateAndReturnAccessToken();
        $client = static::createClient();
        $client->request(
            'GET',
            "/api/$sku",
            array(),
            array(),
            array('HTTP_Authorization' => 'Bearer '.$token)
        );

        return $client;
    }

    public function testPostSku()
    {
        $client = $this->post(uniqid(), uniqid(), uniqid());
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('code', $result);
        $this->assertEquals(7, strlen($result['code']));
    }

    private function post($project, $type, $id)
    {
        $token = $this->authenticateAndReturnAccessToken();
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/',
            array(
                'sku' => array(
                    'project' => $project,
                    'type' => $type,
                    'id' => $id
                )
            ),
            array(),
            array('HTTP_Authorization' => 'Bearer '.$token)
        );

        return $client;
    }


    public function testEditWithoutSku()
    {
        $client = $this->edit('', uniqid(), uniqid(), uniqid());
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testEditNonExistingSku()
    {
        $client = $this->edit('nbvcxw', uniqid(), uniqid(), uniqid());
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testDeleteWithoutSku()
    {
        $client = $this->delete('');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testGetWithoutSku()
    {
        $client = $this->get('');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testDisableWithoutSku()
    {
        $client = $this->disable('');
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testDisableNonExistingSku()
    {
        $client = $this->disable('wxcvbnqsdfghjk');
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testAllStepsProcess()
    {
        $project = 'azerty';
        //allocate
        $client = $this->allocate($project);
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('code', $result);
        $code = $result['code'];
        $this->assertEquals(7, strlen($code));
        //edit
        $type = uniqid();
        $id = uniqid();
        $client = $this->edit($code, $project, $type, $id);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($code, $result['code']);
        $this->assertEquals($project, $result['project']);
        $this->assertEquals($type, $result['type']);
        $this->assertEquals($id, $result['id']);
        $this->assertTrue($result['active']);
        //get
        $client = $this->get($code);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($code, $result['code']);
        $this->assertEquals($project, $result['project']);
        $this->assertEquals($type, $result['type']);
        $this->assertEquals($id, $result['id']);
        //post existant
        $client = $this->post($project, $type, $id);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($code, $result['code']);
        //edit existant
        $client = $this->edit('1234567', $project, $type, $id);
        $this->assertEquals(Response::HTTP_CONFLICT, $client->getResponse()->getStatusCode());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals($code, $result['code']);
        //disable
        $client = $this->disable($code);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $result = json_decode($client->getResponse()->getContent(), true);
        $this->assertFalse($result['active']);
        $this->assertNotNull($result['deleted_at']);
        //get
        $client = $this->get($code);
        $this->assertEquals(Response::HTTP_GONE, $client->getResponse()->getStatusCode());
        //delete
        $client = $this->delete($code);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
        //get
        $client = $this->get($code);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
        //delete
        $client = $this->delete($code);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    private function allocate($project)
    {
        $token = $this->authenticateAndReturnAccessToken();
        $client = static::createClient();
        $client->request(
            'POST',
            "/api/$project",
            array(),
            array(),
            array('HTTP_Authorization' => 'Bearer '.$token)
        );

        return $client;
    }

    private function edit($sku, $project, $type, $id)
    {
        $token = $this->authenticateAndReturnAccessToken();
        $client = static::createClient();
        $client->request(
            'PUT',
            "/api/$sku",
            array(
                'sku' => array(
                    'project' => $project,
                    'type' => $type,
                    'id' => $id
                )
            ),
            array(),
            array('HTTP_Authorization' => 'Bearer '.$token)
        );

        return $client;
    }

    private function disable($sku)
    {
        $token = $this->authenticateAndReturnAccessToken();
        $client = static::createClient();
        $client->request(
            'PUT',
            "/api/disable/$sku",
            array(),
            array(),
            array('HTTP_Authorization' => 'Bearer '.$token)
        );

        return $client;
    }

    private function delete($sku)
    {
        $token = $this->authenticateAndReturnAccessToken();
        $client = static::createClient();
        $client->request(
            'DELETE',
            "/api/$sku",
            array(),
            array(),
            array('HTTP_Authorization' => 'Bearer '.$token)
        );

        return $client;
    }
}
