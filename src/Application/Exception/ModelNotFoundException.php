<?php

namespace Application\Exception;

use Exception;

class ModelNotFoundException extends Exception
{
    protected $code = 404;
}
