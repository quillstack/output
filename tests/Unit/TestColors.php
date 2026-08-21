<?php

declare(strict_types=1);

namespace Quillstack\Output\Tests\Unit;

use Quillstack\Output\Colors;
use Quillstack\Output\Tests\DataProviders\ColorTagsDataProvider;
use Quillstack\Output\Tests\DataProviders\UnknownTagsDataProvider;
use Quillstack\UnitTests\AssertEqual;
use Quillstack\UnitTests\Attributes\ProvidesDataFrom;
use Quillstack\UnitTests\Types\AssertBoolean;

class TestColors
{
    private Colors $colors;

    public function __construct(
        private AssertEqual $assertEqual,
        private AssertBoolean $assertBoolean
    ) {
        $this->colors = new Colors();
    }

    #[ProvidesDataFrom(ColorTagsDataProvider::class)]
    public function knownTagsBecomeCodes(string $text, string $expected)
    {
        $this->assertEqual->equal($expected, $this->colors->decorate($text));
    }

    #[ProvidesDataFrom(UnknownTagsDataProvider::class)]
    public function unknownTagsAreLeftAlone(string $text)
    {
        $this->assertEqual->equal($text, $this->colors->decorate($text));
    }

    public function tagsAreMatchedWhateverTheirCase()
    {
        $this->assertEqual->equal(
            "\e[0;31mMessage\e[0m",
            $this->colors->decorate('<RED>Message</Red>')
        );
    }

    public function coloursCanBeMixedInOneLine()
    {
        $this->assertEqual->equal(
            "\e[0;31mred\e[0m and \e[0;34mblue\e[0m",
            $this->colors->decorate('<red>red</red> and <blue>blue</blue>')
        );
    }

    public function strippingRemovesTheTagsWithoutColouring()
    {
        $this->assertEqual->equal(
            'red and blue',
            $this->colors->strip('<red>red</red> and <blue>blue</blue>')
        );
    }

    public function strippingLeavesUnknownTagsAlone()
    {
        $this->assertEqual->equal(
            '<unknown>Message</unknown>',
            $this->colors->strip('<unknown>Message</unknown>')
        );
    }

    public function aTagOfYourOwnCanBeAdded()
    {
        $colors = new Colors(['brand' => '1;35']);

        $this->assertEqual->equal(
            "\e[1;35mQuillstack\e[0m",
            $colors->decorate('<brand>Quillstack</brand>')
        );
    }

    public function theKnownTagsAreListed()
    {
        $codes = $this->colors->getCodes();

        $this->assertEqual->equal('0;31', $codes['red']);
        $this->assertBoolean->isTrue(isset($codes['blue'], $codes['yellow'], $codes['white']));
    }
}
