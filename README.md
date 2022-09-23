# StellarWP Container Contract

Service Container interface for use in StellarWP libraries.

Note that this is not a Container implementation of its own. It is merely abstractions that describe the components of a Dependency Injection Container.

## Installation

It's recommended that you install this contract as a project dependency via [Composer](https://getcomposer.org/):

```bash
composer require stellarwp/container-contract
```

_Note: If you typically use a tool like [Strauss](https://github.com/BrianHenryIE/strauss), you should **not** customize the namespace of this library in order to have proper compatibility with StellarWP libraries._