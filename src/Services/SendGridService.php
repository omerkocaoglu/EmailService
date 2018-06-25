<?php

namespace OmerKocaoglu\EmailService\Services;

use OmerKocaoglu\EmailService\Error\HttpStatusCode;
use OmerKocaoglu\EmailService\ServiceBase;
use SendGrid\Mail\Mail;
use SendGrid\Response;

class SendGridService extends ServiceBase
{
    /** @var Mail */
    private $email = null;
    /** @var string */
    private $api_key = null;
    /** @var string */
    private $from = null;
    /** @var string */
    private $to = null;

    private function getProviderInstance()
    {
        if ($this->email === null) {
            $this->email = new Mail();
            return $this->email;
        }

        return $this->email;
    }

    /**
     * @param string $api_key
     * @return SendGridService
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    /**
     * @param string $email_address
     * @param null $name
     * @return SendGridService
     */
    public function setFrom($email_address, $name = null)
    {
        $this->from = $email_address;
        $this->getProviderInstance()->setFrom($this->from, $name);
        return $this;
    }

    /**
     * @param string $email_address
     * @param null $name
     * @return SendGridService
     */
    public function addTo($email_address, $name = null)
    {
        $this->to = $email_address;
        $this->getProviderInstance()->addTo($this->to, $name);
        return $this;
    }

    /**
     * @param string $subject
     * @return SendGridService
     */
    public function setSubject($subject)
    {
        $this->getProviderInstance()->setSubject($subject);
        return $this;
    }

    /**
     * @param string $content_type
     * @param null $content
     * @return SendGridService
     */
    public function addContent($content_type, $content = null)
    {
        $this->getProviderInstance()->addContent($content_type, $content);
        return $this;
    }

    public function send()
    {
        if ($this->email === null) {
            return $this->createErrorDetail(
                HttpStatusCode::INTERNAL_SERVER_ERROR,
                'invalid email instance'
            );
        }

        if ($this->from === null) {
            return $this->createErrorDetail(
                HttpStatusCode::UNPROCESSABLE_ENTITY,
                sprintf(
                    'invalid source (from) email address %s',
                    $this->from
                )
            );
        }

        if ($this->to === null) {
            return $this->createErrorDetail(
                HttpStatusCode::UNPROCESSABLE_ENTITY,
                sprintf(
                    'invalid destination (to) email address %s',
                    $this->to
                )
            );
        }

        if ($this->api_key === null) {
            return $this->createErrorDetail(
                HttpStatusCode::UNPROCESSABLE_ENTITY,
                sprintf(
                    'invalid api key %s',
                    $this->api_key
                )
            );
        }

        $send_grid = new \SendGrid($this->api_key);
        try {
            /** @var Response $response */
            $response = $send_grid->send($this->email);
            if ($response->statusCode() >= 400) {
                return $this->createErrorDetail(
                    HttpStatusCode::INTERNAL_SERVER_ERROR,
                    $response->body()
                );
            }

            return $response;
        } catch (\Exception $exception) {
            return $this->createErrorDetail(
                HttpStatusCode::INTERNAL_SERVER_ERROR,
                $exception->getMessage()
            );
        }
    }
}
