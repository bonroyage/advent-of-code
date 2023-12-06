# [Advent of Code](https://adventofcode.com/)

## Editions

- [2023](editions/2023/README.md)
- [2022](editions/2022)
- [2021](editions/2021)

## Solving a puzzle

```bash
php application solve {year} {day?}
```

## New puzzle

1. Copy `_template.php` to the edition folder and name it with the day number
2. Add a `.txt` file with the day as its name for the actual puzzle input
3. Add a `-sample.txt` file with the day as its prefix for the sample input to use in tests
4. Add the day to the test file with the sample answers provided by the question.

## Testing

```bash
php application test
```

## Code style
```bash
composer pint:fix
```
