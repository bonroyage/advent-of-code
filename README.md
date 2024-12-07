# [Advent of Code](https://adventofcode.com/)

## Editions

- See [/editions](editions)

## Solving a puzzle

```bash
php application solve {year} {day?}
```

## New puzzle

```bash
php application make {year} {day}
```

Copy the actual puzzle input into `real.txt`. This file should not be committed 
to the repository.

Copy the sample puzzle data into `sample.txt`. This file can optionally be split
into `sample.1.txt` and `sample.2.txt` if the puzzle has different samples for
part 1 and part 2.

## Testing

This will run the code against all the sample inputs and answers.

```bash
php application test
```

## Code style
```bash
composer pint:fix
```
