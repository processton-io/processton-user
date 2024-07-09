<?php

namespace Processton\ProcesstonUser;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Processton\ProcesstonUser\Skeleton\SkeletonClass
 */
class ProcesstonUserFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'processton-user';
    }
}
