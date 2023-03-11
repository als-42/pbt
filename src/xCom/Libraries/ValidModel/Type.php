<?php

namespace xCom\Libraries\ValidModel;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Type
{
    const Email = 'email';


    public function __construct(
        private readonly string $type = ''
    ) {
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}