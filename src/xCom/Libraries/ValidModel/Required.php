<?php declare(strict_types=1);

namespace xCom\Libraries\ValidModel;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Required
{
    const REQUIRED = 'required';
    const LENGTH = 'len';
    const ISO_DATE = 'iso_date';
    const PHONE = 'phone';
    const MAIL = 'mail';
    const POSITIVE_NUMBER = 'positive_number';

    public function __construct(
        public string $validator = '',
        public string|int|bool $value = '',
    ) {

    }

}