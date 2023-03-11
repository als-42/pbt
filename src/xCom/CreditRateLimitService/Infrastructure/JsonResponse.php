<?php

namespace xCom\CreditRateLimitService\Infrastructure;


/**
 * todo consistent response messages
 * https://stackoverflow.com/questions/12806386/is-there-any-standard-for-json-api-response-format
 * Yes there are a couple of standards (albeit some liberties on the definition of standard) that have emerged:

    JSON API - JSON API covers creating and updating resources as well, not just responses.
    JSend - Simple and probably what you are already doing.
    OData JSON Protocol - Very complicated.
    HAL - Like OData but aiming to be HATEOAS like.
    There are also JSON API description formats:

    Swagger
    JSON Schema (used by swagger but you could use it stand alone)
    WADL in JSON
    RAML
    HAL because HATEOAS in theory is self describing.
 */
class JsonResponse extends \Laminas\Diactoros\Response\JsonResponse
{
    /* im not found const */
    public const CREATED = 201;
    public  const BAD_REQUEST = 400;
    public  const UNPROCESSABLE_CONTENT = 422;
    public const INTERNAL_SERVER_ERROR = 500;

    public array $data = [
        // useful or useless ?
        // I'm thinks is duplicate http header (de facto place for statuses)
        // but some devs wants this version (on fronted), for example while debug
        'http_status_code' => 0,
        'http_status_message' => '',
        // consistent containers for responses
        'errors' => [],
        // also second position: show both always vs solo: error | response
        'response' => [], // or 'success'
    ];
    public function __construct(mixed $data, int $status, array $headers = [])
    {
        if (is_array($data) or is_object($data))
            $this->data['response'] = $data;

        if (is_string($data) || is_numeric($data))
            $this->data['response'][] = $data;

        if ($status < 200 or $status > 299){
            $this->data['errors'] = $this->data['response'];
            $this->data['response'] = [];
        }

        $this->setReasonPhraseForStatus($status);

        parent::__construct($this->data, $status, $headers);
    }

    public function setReasonPhraseForStatus(int $status): void
    {
        // hack for reason phrase, phrase available on immutable obj
        $this->data['http_status_code'] = $status;
        $this->data['http_status_message']
            = (new parent([], $status))->getReasonPhrase();
    }
}