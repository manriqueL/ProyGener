<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ReCaptcha\ReCaptcha;

class UsuarioAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $encoder;
    private $request;
    private $container;
    private $session;
    private $context;
    private $recaptcha;
    private $recaptchaSiteKey;

    public function __construct(UserPasswordEncoderInterface $encoder, ContainerInterface $container, SessionInterface $session, ReCaptcha $recaptcha, $recaptchaSiteKey = null)
    {
        $this->encoder = $encoder;
        $this->container = $container;
        $this->session = $session;
        $this->recaptcha = $recaptcha;
        $this->recaptchaSiteKey = $recaptchaSiteKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        // valido el recaptcha si se definio el recaptchaSiteKey en los archivos de configuración de los parametros de entorno (.env, .env.local)
        if ($this->recaptchaSiteKey) {

          // verifico que el ReCaptcha viene en el parametro del request
          if($this->request->request->has('g-recaptcha-response')) {

              // verifico que el ReCaptcha sea válido
              $resp = $this->recaptcha->verify($this->request->request->get('g-recaptcha-response'), $this->request->getClientIp());
              if (!$resp->isSuccess()){
                  throw new CustomUserMessageAuthenticationException("No se pudo verificar si es un robot. Debe seleccionar el ReCaptcha");
              }

          } else {
              throw new CustomUserMessageAuthenticationException("No se pudo verificar si es un robot. Debe seleccionar el ReCaptcha");
          }
        }

        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        } catch (UsernameNotFoundException $e) {
            // CAUTION: this message will be returned to the client
            // (so don't put any un-trusted messages / error strings here)
            throw new CustomUserMessageAuthenticationException('Datos de acceso incorrectos');
        }

        $claveEncriptada = $token->getCredentials();
        $passwordValid = $this->encoder->isPasswordValid($user, $claveEncriptada);

        if ($passwordValid) {

            $upt = new UsernamePasswordToken(
               $user,
               $user->getPassword(),
               $providerKey,
               $user->getRoles()
           );


             return $upt;
        }

        // CAUTION: this message will be returned to the client
        // (so don't put any un-trusted messages / error strings here)
        throw new CustomUserMessageAuthenticationException('Datos de acceso incorrectos');
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        $this->request = $request;
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}
