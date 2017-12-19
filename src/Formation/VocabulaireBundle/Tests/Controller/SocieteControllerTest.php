<?php

namespace Formation\VocabulaireBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SocieteControllerTest extends WebTestCase
{
    public function testAdd()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'add_societe');
    }

    public function testListe()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'liste_societe');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'update_societe');
    }

}
