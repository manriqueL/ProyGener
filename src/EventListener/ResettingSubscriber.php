<?php

namespace App\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseNullableUserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use ReCaptcha\ReCaptcha;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/*
 * Esta clase tiene la responsabilidad de validar que el usuario solicitante
 * de recuperar la contraseña existe y que el ReCaptcha es válido
 */
class ResettingSubscriber implements EventSubscriberInterface
{
    private $router;
    private $recaptcha;

    public function __construct(UrlGeneratorInterface $router, ReCaptcha $recaptcha)
    {
        $this->router = $router;
        $this->recaptcha = $recaptcha;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::RESETTING_SEND_EMAIL_INITIALIZE => 'onResettingResetInitialize',
        );
    }

    // Este método se ejecuta cuando ocurre el evento RESETTING_SEND_EMAIL_INITIALIZE,
    // es decir cuando se envía el formulario para solicitar restablecer la clave de un usuario
    public function onResettingResetInitialize(GetResponseNullableUserEvent $event)
    {
      $request = $event->getRequest();
      $user = $event->getUser();

      // verifico que el ReCaptcha viene en el parametro del request
      if($request->request->has('g-recaptcha-response')) {

          // verifico que el ReCaptcha sea válido
          $resp = $this->recaptcha->verify($request->request->get('g-recaptcha-response'),$request->getClientIp());
          if (!$resp->isSuccess()){

            $this->redirectError($request, $event,"No se pudo verificar si es un robot. Debe seleccionar el ReCaptcha");
          } else {

            // verifico que el usuario indicado para recuperar la clave exista
            if (!$user) $this->redirectError($request, $event,"Error! No existe el usuario ingresado");
          }

      } else {
        $this->redirectError($request, $event,"No se pudo verificar si es un robot. Debe seleccionar el ReCaptcha");
      }
    }

    private function redirectError($request, $event, $mensaje = "Error! Se cancelo el proceso para restablecer la clave")
    {
      $request->getSession()->getFlashBag()->add(
        'danger',
        $mensaje
      );

      $url = $this->router->generate('fos_user_resetting_request');
      $response = new RedirectResponse($url);
      $event->setResponse($response);
    }
}
