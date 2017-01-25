<?php

namespace Recca0120\Cart;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Storage
{
    /**
     * $name.
     *
     * @var string
     */
    protected $name;

    /**
     * $session.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface|\Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * __construct.
     *
     * @param string $name
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface|\Illuminate\Contracts\Session\Session $session
     */
    public function __construct($name = 'default', $session = null)
    {
        $this->name = $name;
        $this->session = is_null($session) === true ? new Session() : $session;
    }

    /**
     * hash.
     *
     * @return string
     */
    protected function hash()
    {
        return hash('sha256', __NAMESPACE__.$this->name);
    }

    /**
     * store.
     *
     * @param  mix $value
     *
     * @return static
     */
    public function store($value)
    {
        call_user_func_array([
            $this->session,
            method_exists($this->session, 'put') ? 'put' : 'set'
        ], [$this->hash(), $value]);

        return $this;
    }

    /**
     * restore.
     *
     * @return bool
     */
    public function restore()
    {
        return $this->session->get($this->hash(), new Collection);
    }
}
