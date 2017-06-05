<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @package App\Facades
 *
 * @version    1.0.0
 * @author     Pribumi Technology
 * @license    MIT
 * @copyright  (c) 2015 - 2016, Pribumi Technology
 * @link       http://pribumitech.com
 */
class Kuliah extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'kuliah.facade';
    }
}
