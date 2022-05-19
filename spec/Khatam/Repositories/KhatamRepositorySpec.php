<?php

namespace spec\Khatam\Repositories;

use \Khatam\Repositories\KhatamRepository;
use PhpSpec\ObjectBehavior;

global $wpdb;

class KhatamRepositorySpec extends ObjectBehavior
{
    public function let(\wpdb $wpdb)
    {
        $this->beConstructedWith($wpdb);
    }
    public function it_is_initializable()
    {
        $this->shouldHaveType(KhatamRepository::class);
    }
}