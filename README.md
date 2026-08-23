# Quillstack Output

[![Tests](https://github.com/quillstack/output/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/output/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/output.svg)](https://packagist.org/packages/quillstack/output)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/output.svg)](https://packagist.org/packages/quillstack/output)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/output)](https://packagist.org/packages/quillstack/output)
[![StyleCI](https://github.styleci.io/repos/394802660/shield?branch=main)](https://github.styleci.io/repos/394802660?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/output/badge)](https://www.codefactor.io/repository/github/quillstack/output)
[![License](https://img.shields.io/packagist/l/quillstack/output)](https://github.com/quillstack/output/blob/main/LICENSE)

The library responsible for console output in CLI commands, colouring text with
HTML-like tags.

## Installation

```shell
composer require quillstack/output
```

## Usage

```php
use Quillstack\Output\Output;

$output = new Output();

$output->writeln('Everything <green>works</green>');
$output->writeln('Something <red>went wrong</red>');
```

Or through the helpers, which are there as soon as the package is installed:

```php
writeln('Everything <green>works</green>');
write('No line ending here');
```

`format()` returns the text instead of writing it:

```php
$line = $output->format('<yellow>Careful</yellow>');
```

## Colours

A colour is opened with `<name>` and closed with `</name>`. A colour left open is closed
at the end of the text, so the terminal is never left painted. Tags naming no colour are
left exactly as they were, so text which merely looks like markup survives untouched.

| Tag | Tag |
|---|---|
| `<black>` | `<dark-grey>` |
| `<red>` | `<light-red>` |
| `<green>` | `<light-green>` |
| `<brown>` | `<yellow>` |
| `<blue>` | `<light-blue>` |
| `<purple>` | `<light-purple>` |
| `<cyan>` | `<light-cyan>` |
| `<light-grey>` | `<white>` |

Colours of your own go to the constructor, written as ANSI codes:

```php
use Quillstack\Output\Colors;
use Quillstack\Output\Output;

$output = new Output(new Colors(['brand' => '1;35']));

$output->writeln('<brand>Quillstack</brand>');
```

## Output which is not a terminal

Escape codes only make sense on a terminal. Piped somewhere else they are noise, so an
undecorated output drops them and writes the text alone:

```php
$output = new Output(decorated: stream_isatty(STDOUT));
```

## Tests

```shell
composer test
```

Coverage needs phpdbg:

```shell
composer test:coverage
```

## License

MIT. See [LICENSE](LICENSE).
