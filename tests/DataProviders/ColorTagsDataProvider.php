<?php

declare(strict_types=1);

namespace Quillstack\Output\Tests\DataProviders;

use Quillstack\Output\Colors;
use Quillstack\UnitTests\DataProviderInterface;

/**
 * The cases the colouring has to get right, written once and run against every colour.
 */
class ColorTagsDataProvider implements DataProviderInterface
{
    public function provides(): array
    {
        $cases = [];

        foreach (Colors::CODES as $tag => $code) {
            $open = "\e[{$code}m";
            $reset = Colors::RESET;

            $cases[] = ["<{$tag}>Message</{$tag}>", "{$open}Message{$reset}"];
            $cases[] = ["<{$tag}></{$tag}>", "{$open}{$reset}"];
            $cases[] = ["<{$tag}>Message", "{$open}Message{$reset}"];
            $cases[] = ["Message</{$tag}>", "Message{$reset}"];
            $cases[] = ["<{$tag}><{$tag}>Message</{$tag}>", "{$open}{$open}Message{$reset}"];
            $cases[] = ["Message</{$tag}></{$tag}>", "Message{$reset}{$reset}"];
            $cases[] = ["Message<{$tag}>", "Message{$open}{$reset}"];
        }

        return $cases;
    }
}
