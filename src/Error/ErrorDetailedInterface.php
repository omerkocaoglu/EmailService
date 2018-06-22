<?php


namespace OmerKocaoglu\EmailService\Error;


interface ErrorDetailedInterface
{

    /**
     * @param int $status_code
     * @param string $message
     * @return ErrorDetailModel
     */
    public function createErrorDetail($status_code, $message);
}
