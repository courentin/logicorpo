<?php
namespace LogiCorpoBundle\Listeners;
 
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Doctrine\ORM\EntityManager;


class AuthenticationListener implements EventSubscriberInterface
{
	protected $em;
	protected $auth;

	public function __construct(AuthorizationChecker $auth, EntityManager $em) {
		$this->em = $em;
		$this->auth = $auth;
	}

	/**
	 * getSubscribedEvents
	 *
	 */
	public static function getSubscribedEvents()
    {
        return array(
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        );
    }
 
	/**
	 * onAuthenticationFailure
	 * methode l'orsq
	 */
	public function onAuthenticationFailure(AuthenticationFailureEvent $event)
	{
		// executes on failed login
	}
 
	/**
	 * onAuthenticationSuccess
	 *
	 * @author 	Joe Sexton <joe@webtipblog.com>
	 * @param 	InteractiveLoginEvent $event
	 */
	public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
    	//$this->auth->isGranted('IS_AUTHENTICATED_FULLY');
    	

    	/*
    	

	    	if(!$user->getLastLog()) {
	    		dump('first log');
	    	}
	    	$user->setLastLog(new \DateTime());
	    	$em->flush();
    	*/
    }
}