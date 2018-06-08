<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Personnage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class GameControllerTest extends WebTestCase
{

    private $client;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

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

        $this->em=self::bootKernel()->getContainer()->get('doctrine')->getManager();

        $crawler = $this->client->followRedirect();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/game/prepare');

        $responseHeaders = $crawler->getUri();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Choisir un personnage', $crawler->filter('#title_page')->text());
    }

    public function testChooseCharacter()
    {
        $crawler = $this->client->request('GET', '/game/prepare');

        $character = $this->em->getRepository(Personnage::class)->findBy(['user' => 1]);/* recherche persos de l'user by id*/
        $this->assertTrue(!($character==null), $character);

    }

    public function testChooseBOT()
    {
        $crawler = $this->client->request('GET', '/game/prepare');

        $character = $this->em->getRepository(Personnage::class)->findBy(['user' => null]);/* recherche persos de l'user by id*/
        $this->assertTrue(!($character==null), $character);

    }

    public function testFight()
    {
        $crawler = $this->client->request('POST', '/game/play', array("character"=>1,"opponent"=>8));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('LA BAGARRE', $crawler->filter('.container h1')->text());

    }


}
