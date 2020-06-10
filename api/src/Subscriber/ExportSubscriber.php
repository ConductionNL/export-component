<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Export;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ExportSubscriber implements EventSubscriberInterface
{
    private $params;
    private $em;
    private $serializer;

    public function __construct(ParameterBagInterface $params, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->params = $params;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['export', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function export(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $route = $event->getRequest()->attributes->get('_route');

        if (!$result instanceof Template || $route != 'api_templates_render_template_item' || $method != 'POST') {
            return;
        }

        $request = new Request();

        /*@todo onderstaande verhaal moet uiteraard wel worden gedocumenteerd in redoc */
        $query = $request->query->all();
        $body = json_decode($request->getContent(), true); /*@todo hier zouden we eigenlijk ook xml moeten ondersteunen */

        $variables = array_merge($query, $body);

        $template = $this->templating->createTemplate($result->getContent());
        $reponse = $template->render($variables);

        $reponse = new Response(
            $json,
            Response::HTTP_OK,
            ['content-type' => $result->getContentType()]
        );

        $event->setResponse($response);
    }
}
