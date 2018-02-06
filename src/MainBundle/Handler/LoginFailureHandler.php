<?php
namespace MainBundle\Handler;


/**
 * Description of LoginFailureHandler
 *
 * @author etudiant
 */
class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{

    /* @var \Symfony\Component\Routing\Router */
    protected $router;

    /* @var \Symfony\Component\Security\Core\SecurityContext */
    protected $security;

    /**
     * @param Router $router
     * @param SecurityContext $security
     */
    public function __construct(Router $router, SecurityContext $security) {
        $this->router = $router;
        $this->security = $security;
    }

    /**
     * This is called when an interactive authentication attempt fails. This is
     * called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response The response to return, never null
     */
    public function onAuthenticationFailure(Request $request, \Exception $exception)
    {
      
           $array = array( 'success' => false, 'message' => $exception->getMessage() );
            // set authentication exception to session
            $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);

           return $this->render('MainBundle:Default:erreur.html.twig', $array);
        
    }
}