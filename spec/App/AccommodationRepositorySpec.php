<?php

namespace spec\App;

use App\AccommodationRepository;
use PhpSpec\ObjectBehavior;

class AccommodationRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AccommodationRepository::class);
    }
}
