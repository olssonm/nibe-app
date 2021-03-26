<?php

namespace App\Services\Nibe\Actions;

/**
 *
 */
trait ManagesSystems
{
    /**
     * Get the systems
     *
     * @return stdObj
     */
    public function getSystems()
    {
        return $this->get('systems');
    }
}
