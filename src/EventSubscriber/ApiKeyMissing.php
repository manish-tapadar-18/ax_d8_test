<?php

namespace Drupal\ax_tech_test\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ApiKeyMissing.
 */
class ApiKeyMissing implements EventSubscriberInterface {

    /**
     * Constructs a new ApiKeyMissing object.
     */
    public function __construct() {

    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
        $events[KernelEvents::REQUEST][] = array('checkApiKeyMissing');
        return $events;
    }

    /**
     * This method is called whenever the KernelEvents::REQUEST event is
     * dispatched.
     *
     */
    public function checkApiKeyMissing() {
        $config = \Drupal::service('config.factory')->getEditable('ax_tech_test.siteapikey');
        $defaultValue = $config->get('siteapikey');
        if(empty($defaultValue)){
            \Drupal::messenger()->addError('Site Api Key is missing');
        }

    }

}
