# StellarWP Container Contract

Service Container interface for use in StellarWP libraries.

Note that this is not a Container implementation of its own. It is merely abstractions that describe the components of a Dependency Injection Container.

## Installation

It's recommended that you install this contract as a project dependency via [Composer](https://getcomposer.org/):

```bash
composer require stellarwp/container-contract
```

_Note: If you typically use a tool like [Strauss](https://github.com/BrianHenryIE/strauss), you should **not** customize the namespace of this library in order to have proper compatibility with StellarWP libraries._

## Usage

Simply `use` the `ContainerInterface` and implement it or use it as a type hint.

```php
use StellarWP\ContainerContract\ContainerInterface;

class Container implements ContainerInterface {
	public function bind( string $id, $implementation ) {}
	public function get( string $id ) {}
	public function has( string $id ) {}
	public function singleton( string $id, $implementation ) {}
}
```

## Example extensions of other containers

### [DI52](https://github.com/lucatume/di52)

```php

use lucatume\DI52\Container as DI52Container;
use StellarWP\ContainerContract\ContainerInterface;

class Container implements ContainerInterface {
	protected $container;

	/**
	 * Container constructor.
	 */
	public function __construct() {
		$this->container = new DI52Container();
	}

	/**
	 * @inheritDoc
	 */
	public function bind( string $id, $implementation = null ) {
		return $this->container->bind( $id, $implementation );
	}

	/**
	 * @inheritDoc
	 */
	public function get( string $id ) {
		return $this->container->get( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function has( string $id ) {
		return $this->container->has( $id );
	}

	/**
	 * @inheritDoc
	 */
	public function singleton( string $id, $implementation = null ) {
		return $this->container->singleton( $id, $implementation );
	}

	/**
	 * Defer all other calls to the container object.
	 */
	public function __call( $name, $args ) {
		return $this->container->{$name}( ...$args );
	}
}
```