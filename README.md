# Quillstack Output

[![Tests](https://github.com/quillstack/output/actions/workflows/tests.yml/badge.svg)](https://github.com/quillstack/output/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/quillstack/output.svg)](https://packagist.org/packages/quillstack/output)
[![Downloads](https://img.shields.io/packagist/dt/quillstack/output.svg)](https://packagist.org/packages/quillstack/output)
[![PHP Version](https://img.shields.io/packagist/php-v/quillstack/output)](https://packagist.org/packages/quillstack/output)
[![StyleCI](https://github.styleci.io/repos/394802660/shield?branch=main)](https://github.styleci.io/repos/394802660?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/quillstack/output/badge)](https://www.codefactor.io/repository/github/quillstack/output)
[![Quality Gate](https://sonarcloud.io/api/project_badges/measure?project=quillstack_output&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=quillstack_output)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=quillstack_output&metric=coverage)](https://sonarcloud.io/summary/new_code?id=quillstack_output)
[![Maintainability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_output&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_output)
[![Reliability](https://sonarcloud.io/api/project_badges/measure?project=quillstack_output&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_output)
[![Security](https://sonarcloud.io/api/project_badges/measure?project=quillstack_output&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=quillstack_output)
[![License](https://img.shields.io/packagist/l/quillstack/output)](https://github.com/quillstack/output/blob/main/LICENSE)

The library responsible for console output in CLI commands, colouring text with
HTML-like tags.

## Why this exists

A command-line tool needs two things from its output: colour where a person is watching, and no
colour where the output is being piped into a file or read by another program. Getting the
second one wrong fills logs with escape codes.

This does both, in one class with three methods, and it is what
[quillstack/cli](https://github.com/quillstack/cli) writes through. It exists separately from
the console because writing coloured text is not the same job as parsing arguments, and plenty
of scripts want the first without the second.

## Requirements

- PHP 8.1 or newer

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

### Colours

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

### Output which is not a terminal

Escape codes only make sense on a terminal. Piped somewhere else they are noise, so an
undecorated output drops them and writes the text alone:

```php
$output = new Output(decorated: stream_isatty(STDOUT));
```

## Benchmark

Measured with [quillstack/benchmark](https://github.com/quillstack/benchmark) on a thousand lines
of tagged text turned into escape codes. Runs are interleaved and unconcurrent, each figure is
the median of five, and PHP is 8.5.7.

| | Version |
| --- | --- |
| quillstack/output | 0.6.0 |
| symfony/console | v7.4.17 |

| | Per line | Relative | Installed |
| --- | --- | --- | --- |
| **quillstack/output** | **1.92 µs** | — | 76 kB |
| symfony/console | 6.19 µs | 3.2× | 952 kB |

`league/climate` is not in the table: it writes as it formats rather than handing back a string,
so there is nothing to measure the same way. It is 704 kB.

**Symfony's formatter is doing considerably more.** It parses nested tags, supports 256-colour
and true-colour output, href links, custom styles registered by name, and it wraps text to the
terminal width. This matches a fixed set of tags and replaces them.

At two microseconds a line, a command printing a thousand lines spends two milliseconds here and
six on Symfony's — and if you are printing a thousand lines to a terminal, the terminal is the
slow part.

## Tests

```shell
composer test
```

Coverage needs phpdbg:

```shell
composer test:coverage
```

## The rest of Quillstack

This is one component of [Quillstack](https://github.com/quillstack), a PHP framework which is
as simple to use as it is strict about what it does.

- [quillstack/cli](https://github.com/quillstack/cli) — which writes through this
- [quillstack/logger](https://github.com/quillstack/logger) — whose console handler colours by level
- [quillstack/benchmark](https://github.com/quillstack/benchmark) — which prints its results with it

## License

MIT. See [LICENSE](LICENSE).
