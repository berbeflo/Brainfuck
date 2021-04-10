# Brainfuck
## Requirements
- PHP: 7.4+
- Composer: 1.10+

## Install
- Download
- Run `composer install --no-dev` or `composer install` if you want to run CI tools
- Could also be used as dependency if needed

## Brainfuck syntax
- `+` add 1 to the current register
- `-` substract 1 from the current register
- `>` move to the next register
- `<` move to the prev register
- `.` write current register value to output
- `,` read current register value from output
- `[` go into code if current value is not 0
- `]` go to matching opening bracket if current value is not 0

## Usage
- Write the brainfuck code
Be creative or google for examples
```php
$code = '...';
```
- Create the config
```php
$config = new berbeflo\Brainfuck\Config();
```
- Create the interpreter, prepare and run it
```php
$interpreter = new berbeflo\Brainfuck\Interpreter($code, $config);
$interpreter->prepare();
$interpreter->execute();
```

## Config options
- `setInputObject` define the object that provides the input values; has to implement `berbeflo\Brainfuck\Definition\Input`
- `setOutputObject` define the object that provides the output values; has to implement `berbeflo\Brainfuck\Definition\Output`
- `setMinRegisterValue` the default min value is `0`
- `setMaxRegisterValue` the default max value is `255`
- `setMinPointerValue` the minimum register address; default is `0`
- `setMaxPointerValue` the maximum register address; default is `255`
- `setMaximumIterations` prevent endless loops; default is `255`
- `setWrapOnRegisterOverflow` defaults to `false`
- `setWrapOnPointerOverflow` defaults to `false`
- `setAllowUnknownTokens` defaults to `true`
