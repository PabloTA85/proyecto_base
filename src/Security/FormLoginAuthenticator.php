<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormLoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator) {}

    // Comprueba si el usuario y la contraseña son válidos
    public function authenticate(Request $request): Passport
    {
        // Obtener el nombre de usuario.
        $username = $request->request->get('username');  

        // Añadir el nombre de usuario a la sesión para que esté disponible en cualquier página protegida.
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);

        // Devuelve una Passport(clase en Symfony para almacenar la informacion necesaria para autenticar a un usuario) 
        return new Passport(
            new UserBadge($username),
            // Obtener la contraseña del formulario.
            new PasswordCredentials($request->request->get('password')),  
            [
                // Eliminamos el token CSRF
                new RememberMeBadge(),
            ]
        );
    }

    // Comprueba si el formulario de login está habilitado
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Si el usuario estaba intentando acceder a una página protegida antes de hacer login
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Redirige a la página principal (index) después del login exitoso
        return new RedirectResponse($this->urlGenerator->generate('admin'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
