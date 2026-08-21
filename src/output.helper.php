<?php

declare(strict_types=1);

use Quillstack\Output\Output;

if (!function_exists('write')) {
    function write(string $text): void
    {
        (new Output())->write($text);
    }
}

if (!function_exists('writeln')) {
    function writeln(string $text): void
    {
        (new Output())->writeln($text);
    }
}
