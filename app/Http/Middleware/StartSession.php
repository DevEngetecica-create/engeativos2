<?php

namespace Illuminate\Session\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Response;

class StartSession
{
    /**
     * The session manager.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $manager;

    /**
     * Create a new session middleware.
     *
     * @param  \Illuminate\Contracts\Session\Session  $manager
     * @return void
     */
    public function __construct(Session $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->manager->start();

        $request->setLaravelSession(
            $session = $this->startSession($request)
        );

        $response = $next($request);

        if (! $request->session()->isStarted()) {
            $session->save();
        }

        return $response;
    }

    /**
     * Start the session for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Session\Session
     */
    protected function startSession($request)
    {
        $session = $this->getSession($request);

        $session->start();

        return $session;
    }

    /**
     * Get the session implementation from the manager.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Session\Session
     */
    protected function getSession($request)
    {
        return tap($this->manager->driver(), function ($session) use ($request) {
            $session->setId($request->cookies->get($session->getName()));

            $session->start();
        });
    }
}
