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
	public function bind( string $id, $implementation = null, array $afterBuildMethods = null ) {
		return $this->container->bind( $id, $implementation, $afterBuildMethods );
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
	public function singleton( string $id, $implementation = null, array $afterBuildMethods = null ) {
		return $this->container->singleton( $id, $implementation, $afterBuildMethods );
	}

	/**
	 * Defer all other calls to the container object.
	 */
	public function __call( $name, $args ) {
		return $this->container->{$name}( ...$args );
	}
}
```

### [PHP-DI](https://github.com/PHP-DI/PHP-DI)

```php

use DI\Container as PHPDIContainer;
use StellarWP\ContainerContract\ContainerInterface;

class Container implements ContainerInterface {
	protected $container;
	protected $singletons = [];

	/**
	 * Container constructor.
	 */
	public function __construct() {
		$this->container = new PHPDIContainer();
	}

	/**
	 * @inheritDoc
	 */
	public function bind( string $id, $implementation = null ) {
		return $this->container->set( $id, $implementation );
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
	 * Build an entry of the container by its name.
	 *
	 * This method behave like get() except resolves the entry again every time.
	 * For example if the entry is a class then a new instance will be created each time.
	 *
	 * This method makes the container behave like a factory.
	 *
	 * @template T
	 * @param string|class-string<T> $name       Entry name or a class name.
	 * @param array                  $parameters Optional parameters to use to build the entry. Use this to force
	 *                                           specific parameters to specific values. Parameters not defined in this
	 *                                           array will be resolved using the container.
	 *
	 * @return mixed|T
	 * @throws InvalidArgumentException The name parameter must be of type string.
	 * @throws DependencyException Error while resolving the entry.
	 * @throws NotFoundException No entry found for the given name.
	 */
	public function make( string $id, array $parameters = [] ) {
		if ( ! empty( $this->singletons[ $id ] ) ) {
			return $this->container->get( $id );
		}

		return $this->container->make( $id, $parameters );
	}

	/**
	 * @inheritDoc
	 */
	public function singleton( string $id, $implementation = null ) {
		$this->singletons[ $id ] = true;
		return $this->container->set( $id, $implementation );
	}

	/**
	 * Defer all other calls to the container object.
	 */
	public function __call( $name, $args ) {
		return $this->container->{$name}( ...$args );
	}
}
```