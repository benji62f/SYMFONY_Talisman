<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class PersonnageControllerTest extends WebTestCase
{

    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $crawler      = $this->client->request('GET', '/');

        $username = "blacksoulx5@gmail.com";
        $password = '123abc123';

        $form = $crawler->filter('form[name="login_base"]')->form();
        $form->setValues(array('_username' => $username));
        $form->setValues(array('_password' => $password));
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/personnages/add');

        $responseHeaders = $crawler->getUri();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Character creation', $crawler->filter('#title_page')->text());
    }

    /**
     *  test le formulaire de création de personnage avec champs valides
     */
    public function testPersonnageAddingFormSuccess()
    {
        $crawler = $this->client->request('GET', '/personnages/add');

        $name = "noname";

        $form = $crawler->filter('form[name="personnage"]')->form();
        $form->setValues(array('personnage[name]' => $name));
        $form->setValues(array('personnage[race]' => 1));
        $form->setValues(array('personnage[strengh]' => 1));
        $form->setValues(array('personnage[dexterity]' => 1));
        $form->setValues(array('personnage[armor]' => 1));
        $form->setValues(array('personnage[life]' => 1));
        $this->client->submit($form);

        $crawler = $this->client->followRedirect();
        $responseHeaders = $crawler->getUri();

        $this->assertEquals("http://localhost/personnages/list", $responseHeaders);
        $this->assertTrue($this->isPersonnageExisting($name, 1,2,1,2,1));
    }

    private function isPersonnageExisting($name, $race, $strengh, $dexterity, $armor, $life)
    {
        $crawler = $this->client->request('GET', '/personnages/list');
        return $crawler->filter('table tr:contains("'.$name.'"):contains("'.$race.'"):contains("'.$strengh.'"):contains("'.$dexterity.'"):contains("'.$armor.'"):contains("'.$life.'")')->count() > 0;
    }
}
