# Taming Procedural Functions in PHP

Have you ever wished for control over the behaviour of procedural functions when writing test code? The techniques demonstrated by the code in this repository will help you seize control of your classes under test.

## The Monitoring Library

The included library defines a `Monitor` interface, instances of which can be `run()`. The client might, for example, be a scheduler that is responsible for running various monitoring jobs, and is outside the scope of these examples.

Also included are various implementations demonstrating how we might create a disk space monitor that logs warnings when disk space on a particular volume is low. The basic implementation, `SimpleDiskMonitor` is not easily tested due to its direct dependency on the procedural function `disk_free_space()`. Each of the other implementations demonstrate a different way to control the behaviour of `disk_free_space()` in a testing scenario.

* `FunctionWrappingDiskMonitor` wraps the procedural function in a protected member function.
* `WrapperDelegatingDiskMonitor` wraps the procedural function in a static function defined in a utility class.
* `VariableFunctionInvokingDiskMonitor` replaces the invocation of the procedural function by name with an invocation by variable.
* `CallbackInvokingDiskMonitor` replaces the invocation of the procedural function by name with an invocation using `call_user_func()`.

Each of these implementations allow test cases to exercise the monitor's logic by controlling the result of the function that returns free disk space.

## Requirements
* [PHP 5.3+](http://php.net)
* [Composer](http://getcomposer.org)

## Installation
1. Clone the repository.
2. From the root directory of the cloned repository, run `composer install`.

## Executing the Tests
1. Install the repository by following the installation instructions.
1. From the installed directory, run `vendor/bin/phpunit`.