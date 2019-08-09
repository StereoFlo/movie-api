<?php

namespace Application\Exception;

use Exception;

/**
 * Class ModelNotFoundException
 * @package Application\Exception
 */
class ModelNotFoundException extends Exception
{
    protected $code = 404;
}
