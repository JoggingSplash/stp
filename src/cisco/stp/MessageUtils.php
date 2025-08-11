<?php

namespace cisco\stp;

use Closure;
use pocketmine\math\Vector3;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;
use pocketmine\world\World;

class MessageUtils {

    /**
     * @param string $class
     * @param string $method
     * @param array $argc
     * @return array
     */
    public static function silentMethod(string $class, string $method, array $argc): array    {
        return array_map([$class, $method], $argc);
    }

	/**
	 * @param array $lore
	 * @return array
	 */
	static public function colorizeArray(array $lore): array {
		return self::silentMethod(TextFormat::class, "colorize", $lore);
	}

	/**
	 * @param string            $message
	 * @param string|Permission $permission
	 *
	 * @return void
	 */
	static public function callToStaff(string $message, string|Permission $permission = DefaultPermissions::ROOT_OPERATOR): void     {
		$message = TextFormat::colorize($message);

		foreach (Server::getInstance()->getOnlineplayers() as $player) {
			if($player->hasPermission($permission)) {
				$player->sendMessage($message);
			}
		}
	}

}