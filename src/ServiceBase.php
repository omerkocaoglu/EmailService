<?php

namespace OmerKocaoglu\EmailService;

use OmerKocaoglu\EmailService\Error\ErrorDetailedInterface;
use OmerKocaoglu\EmailService\Error\ErrorDetailModel;

class ServiceBase implements ErrorDetailedInterface
{
    /**
     * @param int $status_code
     * @param string $message
     * @return ErrorDetailModel
     */
    public function createErrorDetail($status_code, $message)
    {
        $model = new ErrorDetailModel();
        $model->status_code = $status_code;
        $model->message = $message;
        return $model;
    }
}
