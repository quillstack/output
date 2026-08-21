<?php

declare(strict_types=1);

namespace Quillstack\Output;

/**
 * Turns HTML-like tags into ANSI colour codes: `<red>text</red>` comes out coloured.
 *
 * Tags nobody knows are left exactly as they were, so text which merely looks like markup
 * survives untouched.
 */
class Colors
{
    /**
     * Ends any colour started before it.
     */
    public const RESET = "\e[0m";

    /**
     * The tag each colour is written with, and the ANSI code it stands for.
     */
    public const CODES = [
        'black' => '0;30',
        'dark-grey' => '1;30',
        'red' => '0;31',
        'light-red' => '1;31',
        'green' => '0;32',
        'light-green' => '1;32',
        'brown' => '0;33',
        'yellow' => '1;33',
        'blue' => '0;34',
        'light-blue' => '1;34',
        'purple' => '0;35',
        'light-purple' => '1;35',
        'cyan' => '0;36',
        'light-cyan' => '1;36',
        'light-grey' => '0;37',
        'white' => '1;37',
    ];

    /**
     * @var array<string, string>
     */
    private array $codes;

    /**
     * @param array<string, string> $codes tags of your own, e.g. `['brand' => '1;35']`
     */
    public function __construct(array $codes = [])
    {
        $this->codes = $codes + self::CODES;
    }

    /**
     * The tags this instance understands.
     *
     * @return array<string, string>
     */
    public function getCodes(): array
    {
        return $this->codes;
    }

    /**
     * Replaces every known tag with its code. A colour left open is closed at the end, so
     * the terminal is never left painted.
     */
    public function decorate(string $text): string
    {
        $opened = false;
        $decorated = $this->replaceTags($text, function (string $tag, bool $closing) use (&$opened): string {
            if ($closing) {
                return self::RESET;
            }

            $opened = true;

            return "\e[{$this->codes[$tag]}m";
        });

        if ($opened && !str_ends_with($decorated, self::RESET)) {
            $decorated .= self::RESET;
        }

        return $decorated;
    }

    /**
     * Removes every known tag without colouring anything, for output which is not a
     * terminal and would only be littered with escape codes.
     */
    public function strip(string $text): string
    {
        return $this->replaceTags($text, static fn (): string => '');
    }

    /**
     * Walks the known tags of the text, closing ones included, and hands each to $replace.
     */
    private function replaceTags(string $text, callable $replace): string
    {
        $tags = implode('|', array_map('preg_quote', array_keys($this->codes)));

        return (string) preg_replace_callback(
            "#</?({$tags})>#i",
            static fn (array $match): string => $replace(strtolower($match[1]), $match[0][1] === '/'),
            $text
        );
    }
}
