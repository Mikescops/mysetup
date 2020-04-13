<?php

use Cake\Core\Configure;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

class SentryOptionsContext implements EventListenerInterface
{
    public function implementedEvents()
    {
        return [
            'CakeSentry.Client.afterSetup' => 'setServerContext',
        ];
    }

    public function setServerContext(Event $event)
    {
        /** @var Client $subject */
        $subject = $event->getSubject();
        $options = $subject->getHub()->getClient()->getOptions();

        $msVersion = Cache::read('msVersion', 'HomePageCacheConfig');
        if ($msVersion === false) {
            $msVersion = rtrim(`git describe --tags --abbrev=0`);
            Cache::write('msVersion', $msVersion, 'HomePageCacheConfig');
        }

        $environment = Configure::read('debug') ? 'development' : 'production';

        $options->setEnvironment($environment);
        $options->setRelease($msVersion);
    }
}
