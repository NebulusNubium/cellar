<?php

namespace App\Tests;

use App\Entity\Bottles;
use App\Entity\Rating;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class RatingTest extends TestCase
{
    public function testAverageRatingUpdatesWithUserChange(): void
    {
        $bottle = new Bottles();
        $user = new User();
        $user->setUsername('tester');
        $user->setPassword('pass');

        $rating = new Rating();
        $rating->setBottle($bottle);
        $rating->setUser($user);
        $rating->setValue(4);
        $bottle->addRating($rating);

        $this->assertSame(4.0, $bottle->getAverageRating());
        $this->assertSame(4, $bottle->getUserRating($user));

        $rating->setValue(2);
        $this->assertSame(2.0, $bottle->getAverageRating());
        $this->assertSame(2, $bottle->getUserRating($user));
    }
}

