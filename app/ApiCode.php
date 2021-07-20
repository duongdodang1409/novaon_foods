<?php
namespace App;
class ApiCode
{
    public const SUCCESS = 200;
    public const SOMETHING_WENT_WRONG = 250;
    public const MISSING_VALIDATE_FAILED = 400;
    public const UN_AUTHENTICATED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const INVALID_CREDENTIALS = 422;
    public const INTERNAL_SERVER_ERROR = 500;
}
