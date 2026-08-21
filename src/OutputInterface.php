<?php

declare(strict_types=1);

namespace Quillstack\Output;

interface OutputInterface
{
    /**
     * Writes the text, colours included.
     */
    public function write(string $text): void;

    /**
     * Writes the text and ends the line.
     */
    public function writeln(string $text): void;

    /**
     * The text as it would be written.
     */
    public function format(string $text): string;
}
