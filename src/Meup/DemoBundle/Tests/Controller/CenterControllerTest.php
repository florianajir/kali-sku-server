<?php
/**
 * This file is part of the 1001 Pharmacies Symfony REST edition
 *
 * (c) 1001pharmacies <http://github.com/1001pharmacies/symfony-bifrost-edition>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Meup\DemoBundle\Tests\Controller;

use Bazinga\Bundle\RestExtraBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\BrowserKit\Client;

class CenterControllerTest extends WebTestCase
{
    public function setUp()
    {
        $cacheDir = $this->getClient()->getContainer()->getParameter('kernel.cache_dir');
        if (file_exists($cacheDir . '/sf_centers_data')) {
            unlink($cacheDir . '/sf_centers_data');
        }
    }

    public function testGetCenters()
    {
        $client = $this->getClient(true);

        // head request
        $client->request('HEAD', '/api-demo/centers.json');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());

        // empty list
        $client->request('GET', '/api-demo/centers.json');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $this->assertEquals('{"page":1,"limit":5,"pages":0,"total":0,"_links":{"self":{"href":"\/api-demo\/centers?page=1&perPage=5"},"first":{"href":"\/api-demo\/centers?page=1&perPage=5"},"last":{"href":"\/api-demo\/centers?page=0&perPage=5"}},"_embedded":{"items":[]}}', $response->getContent());

        // list
        $this->createCenter($client, 'my center for list');

        $client->request('GET', '/api-demo/centers.json');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $contentWithoutSecret = preg_replace('/"secret":"[^"]*"/', '"secret":"XXX"', $response->getContent());
        $this->assertEquals('{"page":1,"limit":5,"pages":1,"total":1,"_links":{"self":{"href":"\/api-demo\/centers?page=1&perPage=5"},"first":{"href":"\/api-demo\/centers?page=1&perPage=5"},"last":{"href":"\/api-demo\/centers?page=1&perPage=5"}},"_embedded":{"items":[{"secret":"XXX","name":"my center for list","version":"1.1","_links":{"self":{"href":"http:\/\/localhost\/api-demo\/centers\/0"}}}]}}', $contentWithoutSecret);
    }

    public function testGetCenter()
    {
        $client = $this->getClient(true);

        $client->request('GET', '/api-demo/centers/0.json');
        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
        $this->assertEquals('{"code":404,"message":"Center does not exist."}', $response->getContent());

        $this->createCenter($client, 'my center for get');

        $client->request('GET', '/api-demo/centers/0.json');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $contentWithoutSecret = preg_replace('/"secret":"[^"]*"/', '"secret":"XXX"', $response->getContent());
        $this->assertEquals('{"secret":"XXX","name":"my center for get","version":"1.1","_links":{"self":{"href":"http:\/\/localhost\/api-demo\/centers\/0"}}}', $contentWithoutSecret);

        $client->request('GET', '/api-demo/centers/0', array(), array(), array('HTTP_ACCEPT' => 'application/json'));
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $contentWithoutSecret = preg_replace('/"secret":"[^"]*"/', '"secret":"XXX"', $response->getContent());
        $this->assertEquals('{"secret":"XXX","name":"my center for get","version":"1.1","_links":{"self":{"href":"http:\/\/localhost\/api-demo\/centers\/0"}}}', $contentWithoutSecret);
    }

    public function testGetCenterVersioned()
    {
        $client = $this->getClient(true);

        $client->request('GET', '/api-demo/centers/0.json');
        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
        $this->assertEquals('{"code":404,"message":"Center does not exist."}', $response->getContent());

        $this->createCenter($client, 'my center for get');

        $client->request('GET', '/api-demo/centers/0', array(), array(), array('HTTP_ACCEPT' => 'application/json;version=1.0'));
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $contentWithoutSecret = preg_replace('/"secret":"[^"]*"/', '"secret":"XXX"', $response->getContent());
        $this->assertEquals('{"secret":"XXX","name":"my center for get","version":"1","_links":{"self":{"href":"http:\/\/localhost\/api-demo\/centers\/0"}}}', $contentWithoutSecret);

        $client->request('GET', '/api-demo/centers/0', array(), array(), array('HTTP_ACCEPT' => 'application/json;version=1.1'));
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $contentWithoutSecret = preg_replace('/"secret":"[^"]*"/', '"secret":"XXX"', $response->getContent());
        $this->assertEquals('{"secret":"XXX","name":"my center for get","version":"1.1","_links":{"self":{"href":"http:\/\/localhost\/api-demo\/centers\/0"}}}', $contentWithoutSecret);
    }

    public function testNewCenter()
    {
        $client = $this->getClient(true);

        $client->request('GET', '/api-demo/centers/new.json');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $this->assertEquals('{"children":{"name":{}}}', $response->getContent());
    }

    public function testPostCenter()
    {
        $client = $this->getClient(true);

        $this->createCenter($client, 'my center for post');

        $response = $client->getResponse();

        $this->assertJsonResponse($response, Codes::HTTP_CREATED);
        $this->assertEquals($response->headers->get('location'), 'http://localhost/api-demo/centers/0');
    }

    public function testEditCenter()
    {
        $client = $this->getClient(true);

        $client->request('GET', '/api-demo/centers/0/edit.json');
        $response = $client->getResponse();

        $this->assertEquals(404, $response->getStatusCode(), $response->getContent());
        $this->assertEquals('{"code":404,"message":"Center does not exist."}', $response->getContent());

        $this->createCenter($client, 'my center for post');

        $client->request('GET', '/api-demo/centers/0/edit.json');
        $response = $client->getResponse();

        $this->assertJsonResponse($response);
        $this->assertEquals('{"children":{"name":{}}}', $response->getContent());
    }

    public function testPutShouldModifyACenter()
    {
        $client = $this->getClient(true);

        $client->request('PUT', '/api-demo/centers/0.json', array(
            'center' => array(
                'name' => ''
            )
        ));
        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode(), $response->getContent());
        $this->assertEquals('{"code":400,"message":"Validation Failed","errors":{"children":{"name":{"errors":["This value should not be blank."]}}}}', $response->getContent());

        $this->createCenter($client, 'my center for post');

        $client->request('PUT', '/api-demo/centers/0.json', array(
            'center' => array(
                'name' => 'my center for put'
            )
        ));
        $response = $client->getResponse();

        $this->assertEquals(
            Codes::HTTP_NO_CONTENT, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertEquals($response->headers->get('location'), 'http://localhost/api-demo/centers/0');
    }

    public function testPutShouldCreateACenter()
    {
        $client = $this->getClient(true);

        $client->request('PUT', '/api-demo/centers/0.json', array(
            'center' => array(
                'name' => ''
            )
        ));
        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode(), $response->getContent());
        $this->assertEquals('{"code":400,"message":"Validation Failed","errors":{"children":{"name":{"errors":["This value should not be blank."]}}}}', $response->getContent());

        $client->request('PUT', '/api-demo/centers/0.json', array(
            'center' => array(
                'name' => 'my center for put'
            )
        ));
        $response = $client->getResponse();

        $this->assertJsonResponse($response, Codes::HTTP_CREATED);
        $this->assertEquals($response->headers->get('location'), 'http://localhost/api-demo/centers/0');
    }

    public function testRemoveCenter()
    {
        $client = $this->getClient(true);

        $client->request('GET', '/api-demo/centers/0/remove.json');
        $response = $client->getResponse();

        $this->assertEquals(
            Codes::HTTP_NO_CONTENT, $response->getStatusCode(),
            $response->getContent()
        );

        $this->createCenter($client, 'my center for get');

        $client->request('GET', '/api-demo/centers/0/remove.json');
        $response = $client->getResponse();

        $this->assertEquals(
            Codes::HTTP_NO_CONTENT, $response->getStatusCode(),
            $response->getContent()
        );

        $this->assertTrue($response->headers->contains('location', 'http://localhost/api-demo/centers'));
    }

    public function testDeleteCenter()
    {
        $client = $this->getClient(true);

        $client->request('DELETE', '/api-demo/centers/0.json');
        $response = $client->getResponse();

        $this->assertEquals(
            Codes::HTTP_NO_CONTENT, $response->getStatusCode(),
            $response->getContent()
        );

        $this->createCenter($client, 'my center for get');

        $client->request('DELETE', '/api-demo/centers/0.json');
        $response = $client->getResponse();

        $this->assertEquals(
            Codes::HTTP_NO_CONTENT, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue($response->headers->contains('location', 'http://localhost/api-demo/centers'));
    }

    protected function createCenter(Client $client, $name)
    {
        $client->request('POST', '/api-demo/centers.json', array(
            'center' => array(
                'name' => $name
            )
        ));
        $response = $client->getResponse();
        $this->assertJsonResponse($response, Codes::HTTP_CREATED);
    }

    private function getClient($authenticated = false)
    {
        $params = array();
        if ($authenticated) {
            $params = array_merge($params, array(
                    'PHP_AUTH_USER' => 'restapi',
                    'PHP_AUTH_PW'   => 'secretpw',
                ));
        }

        return static::createClient(array(), $params);
    }
}
