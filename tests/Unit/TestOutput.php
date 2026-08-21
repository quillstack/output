<?php

declare(strict_types=1);

namespace Quillstack\Output\Tests\Unit;

use Quillstack\Output\Colors;
use Quillstack\Output\Output;
use Quillstack\UnitTests\AssertEqual;

class TestOutput
{
    public function __construct(private AssertEqual $assertEqual)
    {
        //
    }

    private function captured(callable $write): string
    {
        ob_start();
        $write();

        return ob_get_clean();
    }

    public function writeSendsTheColouredText()
    {
        $output = new Output();

        $this->assertEqual->equal(
            "\e[0;31mMessage\e[0m",
            $this->captured(static fn () => $output->write('<red>Message</red>'))
        );
    }

    public function writelnEndsTheLine()
    {
        $output = new Output();

        $this->assertEqual->equal(
            "\e[0;32mMessage\e[0m" . PHP_EOL,
            $this->captured(static fn () => $output->writeln('<green>Message</green>'))
        );
    }

    public function anUndecoratedOutputLeavesTheEscapeCodesOut()
    {
        $output = new Output(decorated: false);

        $this->assertEqual->equal(
            'Message' . PHP_EOL,
            $this->captured(static fn () => $output->writeln('<red>Message</red>'))
        );
    }

    public function formatReturnsWhatWouldBeWritten()
    {
        $this->assertEqual->equal(
            "\e[1;33mMessage\e[0m",
            (new Output())->format('<yellow>Message</yellow>')
        );
    }

    public function ownColoursArePassedThrough()
    {
        $output = new Output(new Colors(['brand' => '1;35']));

        $this->assertEqual->equal(
            "\e[1;35mQuillstack\e[0m",
            $output->format('<brand>Quillstack</brand>')
        );
    }

    public function theHelpersWriteToo()
    {
        $this->assertEqual->equal(
            "\e[0;34mMessage\e[0m",
            $this->captured(static fn () => write('<blue>Message</blue>'))
        );
        $this->assertEqual->equal(
            "\e[0;36mMessage\e[0m" . PHP_EOL,
            $this->captured(static fn () => writeln('<cyan>Message</cyan>'))
        );
    }
}
