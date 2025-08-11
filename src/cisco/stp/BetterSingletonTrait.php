<?php

namespace cisco\stp;

use pocketmine\utils\SingletonTrait;

/**
 * {@link SingletonTrait}
 */
trait BetterSingletonTrait {

    private static self $instance;

    
    public static function getInstance(): self {
        return self::$instance ??= self::make();
    }

    protected static function make(): self    {
        return new self();
    }

    protected static function setInstance(self $instance): void {
        self::$instance = $instance;
    }
}