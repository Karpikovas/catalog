<?php


namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Response;

class CORSListener 
{
  private $params;

  private $answeredForOptions = false;

  public function __construct(ParameterBagInterface $params)
  {
    $this->params = $params;
  }

  // example from https://gist.github.com/robinvdvleuten/784bae822b58d7a6cae9421b198ba846

  protected function isAllowedHost($host)
  {
    return true;

//        $allowedHosts = ($this->params->get('kernel.environment') === 'dev')
//            ? ['http://localhost:8080']
//            : $this->container->getParameter('allowed_frontend_hosts');
//
//        return in_array($host, $allowedHosts);
  }

  protected function setResponseHeaders($response, $host)
  {
    $response->headers->set('Access-Control-Allow-Origin', $host);
    $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
    $response->headers->set('Access-Control-Allow-Credentials', 'true');
    $response->headers->set('Access-Control-Max-Age', 3600);
    $response->headers->set('Vary', 'Origin');
  }

  public function onKernelRequest(GetResponseEvent $event)
  {
    if (
        $event->getRequestType() === HttpKernelInterface::MASTER_REQUEST
        && 'OPTIONS' === $event->getRequest()->getMethod()
    ) {
      $request = $event->getRequest();
      $host = $request->headers->get('origin');

      if ($this->isAllowedHost($host)) {
        $response = new Response();
        $this->setResponseHeaders($response, $host);
        $event->setResponse($response);
      }

      $this->answeredForOptions = true;

      return;
    }
  }

  public function onKernelResponse(FilterResponseEvent $event)
  {
    $request = $event->getRequest();
    $host = $request->headers->get('origin');

    if (
        $this->isAllowedHost($host)
        && !$this->answeredForOptions
    ) {
      $response = $event->getResponse();
      $this->setResponseHeaders($response, $host);
      $event->setResponse($response);
    }
  }

}
