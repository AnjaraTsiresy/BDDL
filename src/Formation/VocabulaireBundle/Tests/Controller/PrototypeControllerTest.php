<?php

namespace Formation\VocabulaireBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PrototypeControllerTest extends WebTestCase
{
    public function testModifprototype()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifPrototype');
    }

}
