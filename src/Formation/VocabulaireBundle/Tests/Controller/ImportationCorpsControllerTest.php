<?php

namespace Formation\VocabulaireBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImportationCorpsControllerTest extends WebTestCase
{
    public function testGeneratecorps()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/generateCorps');
    }

}
