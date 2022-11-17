<?php

namespace Your\Namespace;

use StellarWP\ContainerContract\ContainerInterface;

// If you are including PHP-DI container using Strauss (recommended), then:
use Your\Namespace\DI\Container as PHPDIContainer;

// If you are including the PHP-DI container directly, then you'd want to do:
//use DI\Container as PHPDIContainer;

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
	 *
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
