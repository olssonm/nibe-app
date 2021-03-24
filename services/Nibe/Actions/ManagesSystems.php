<?php

namespace Services\Nibe\Actions;

/**
 *
 */
trait ManagesSystems
{
    public function getSystems()
    {
        return $this->get('systems');
    }
}
