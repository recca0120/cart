<?php

namespace Recca0120\Cart;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Support\Collection;

class Storage
{
    protected $name;

    protected $session;

    public function __construct($name = 'default', SessionInterface $session = null) {
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
