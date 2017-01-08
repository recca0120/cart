<?php

namespace Recca0120\Cart;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Storage
{
    protected $name;

    protected $session;

    public function __construct($name = 'default', SessionInterface $session = null)
    {
        $this->name = $name;
        $this->session = is_null($session) === true ? new Session($session) : $session;
    }

    protected function hash()
    {
        return hash('sha256', __NAMESPACE__.$this->name);
    }

    public function store($value)
    {
        $this->session->set($this->hash(), $value);

        return $this;
    }

    public function restore()
    {
        return $this->session->get($this->hash(), new Collection);
    }
}
