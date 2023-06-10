<?php


namespace App\Utils\Exception;


use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class FKConstraintViolationException extends ForeignKeyConstraintViolationException
{
    public function __construct() {
        $message = 'Self generated exception';
        $driverException = new MysqliException('Self generated exception.'); //or whatever driver you use if you have another db
        parent::__construct($message, $driverException);
    }
}