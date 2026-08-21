<?php

declare(strict_types=1);

namespace Quillstack\Output;

class Output implements OutputInterface
{
    private Colors $colors;

    /**
     * @param bool $decorated whether colours are written; pass `stream_isatty(STDOUT)` to
     *                        leave escape codes out when the output is piped somewhere
     */
    public function __construct(?Colors $colors = null, private bool $decorated = true)
    {
        $this->colors = $colors ?? new Colors();
    }

    /**
     * {@inheritDoc}
     */
    public function format(string $text): string
    {
        return $this->decorated
            ? $this->colors->decorate($text)
            : $this->colors->strip($text);
    }

    /**
     * {@inheritDoc}
     */
    public function write(string $text): void
    {
        echo $this->format($text);
    }

    /**
     * {@inheritDoc}
     */
    public function writeln(string $text): void
    {
        echo $this->format($text), PHP_EOL;
    }
}
