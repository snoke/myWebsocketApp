<?php
namespace App\Api\JwtSubscriberApi\EventListener;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class JWTCreatedListener extends AbstractController {

/**
 * @var RequestStack
 */
private $requestStack;

/**
 * @param RequestStack $requestStack
 */
public function __construct(RequestStack $requestStack)
{
    $this->requestStack = $requestStack;
}

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload       = $event->getData();
        $payload['id'] = $this->getUser()->getId();

        $event->setData($payload);
    }
}