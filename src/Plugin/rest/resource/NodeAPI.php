<?php

namespace Drupal\ax_tech_test\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "node_api",
 *   label = @Translation("Node api"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/nodeValue/{nid}"
 *   }
 * )
 */
class NodeAPI extends ResourceBase {

    /**
     * A current user instance.
     *
     * @var \Drupal\Core\Session\AccountProxyInterface
     */
    protected $currentUser;

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
        $instance->logger = $container->get('logger.factory')->get('ax_tech_test');
        $instance->currentUser = $container->get('current_user');
        return $instance;
    }

    /**
     * Responds to GET requests.
     *
     * @param string $payload
     *
     * @return \Drupal\rest\ResourceResponse
     *   The HTTP response object.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *   Throws exception expected.
     */
    public function get($nid) {

        // You must to implement the logic of your REST Resource here.
        // Use current user after pass authentication to validate access.
        if (!$this->currentUser->hasPermission('access content')) {
            throw new AccessDeniedHttpException();
        }
        $config = \Drupal::service('config.factory')->getEditable('ax_tech_test.siteapikey');
        $defaultValue = $config->get('siteapikey');
        if(empty($defaultValue)){
            throw new AccessDeniedHttpException();
        }
        $return['msg'] = "Node Not found.";
        $statusCode = 400;
        if($nid){
            // Load node
            $node = Node::load($nid);
            if(is_object($node)){
                $return['msg'] = "Node found.";
                $return[$node->id()] = $node->getTitle();
                $statusCode=200;

            }
        }
        return new JsonResponse($return, $statusCode, [], FALSE);

    }


}
