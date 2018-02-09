<?php

namespace Core\Traits;

/**
 * Trait Initializer
 *
 * A singleton implementation for services
 * Immutability endures within the same instance, until initWithArgs with a new set of arguments is called
 * Allows for stateful services
 * @package Traits
 */
trait Initializer {

    protected static $_instance = null;

    private static $_context = null;

    /**
     * Singleton constructor.
     * @param array $args
     */
    private function __construct(array $args) {
        if (method_exists($this, '_init')) {
            call_user_func_array([$this, '_init'], $args);
        }
    }

    /**
     * @param array $args
     * @return object
     */
    public static function initWithArgs(array $args = []) {
        $isNewArguments = is_array($args) && serialize($args) !== self::$_context;
        if (self::$_instance === null || $isNewArguments) {
            self::$_context = serialize($args);
            return self::$_instance = new self($args);
        } else {
            return self::$_instance;
        }
    }

    /**
     * @return array
     */
    public static function getContext() {
        return unserialize(self::$_context);
    }
}