<?php

declare(strict_types=1);

namespace Quillstack\Output\Tests\DataProviders;

use Quillstack\UnitTests\DataProviderInterface;

/**
 * Text which looks like markup but names no colour has to come out exactly as it went in.
 */
class UnknownTagsDataProvider implements DataProviderInterface
{
    public function provides(): array
    {
        return [
            ['<unknown>Message</unknown>'],
            ['<unknown></unknown>'],
            ['<unknown>Message'],
            ['Message</unknown>'],
            ['Message'],
            ['<unknown><unknown>Message</unknown>'],
            ['Message</unknown></unknown>'],
            ['Message<unknown>'],
            ['<b>bold html</b>'],
            ['2 < 3 and 4 > 1'],
            [''],
        ];
    }
}
