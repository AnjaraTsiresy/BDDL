<?php

namespace Formation\VocabulaireBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;

class FormationVocabulaireBundle extends Bundle
{
	public function boot()
    {
        $this->container->
            get('doctrine.orm.entity_manager')->
            getEventManager()->
            addEventSubscriber(new MysqlSessionInit('utf8', 'utf8_unicode_ci'));
    }
}
