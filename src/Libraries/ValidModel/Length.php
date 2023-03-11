<?php declare(strict_types=1);

namespace XCom\Libraries\ValidModel;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Length
{
    public function __construct(
        private readonly int $length = 0,
    ) {

    }

    public function getLength(): int
    {
        return $this->length;
    }
}