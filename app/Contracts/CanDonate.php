<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CanDonate {
    /**
     * Get the name of the crowdfunding's donator
     * @return string
    */
    public function getName(): string;

    /**
     * Get all the donations this donator has contributed
     *
     * @return MorphMany
    */
    public function donations(): MorphMany;
}
