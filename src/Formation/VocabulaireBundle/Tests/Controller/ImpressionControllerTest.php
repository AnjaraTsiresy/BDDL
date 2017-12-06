<?php

namespace Formation\VocabulaireBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImpressionControllerTest extends WebTestCase
{
    public function testImpressiontablematiere()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/impressionTableMatiere');
    }

    public function testImpressioncorpsglossaire()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/impressionCorpsGlossaire');
    }

}
