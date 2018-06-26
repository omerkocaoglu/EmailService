<?php

namespace OmerKocaoglu\EmailService\Model\SendGrid;

use Fabs\Serialize\SerializableObject;

class SendGridResponseModel extends SerializableObject
{
    /** @var int */
    public $status_code = 0;
    /** @var array */
    public $body = [];
}
