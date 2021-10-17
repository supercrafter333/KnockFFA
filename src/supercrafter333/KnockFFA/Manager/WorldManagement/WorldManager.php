<?php

namespace supercrafter333\KnockFFA\Manager\WorldManagement;

use pocketmine\utils\AssumptionFailedError;
use pocketmine\world\World;
use supercrafter333\KnockFFA\Events\World\FFAWorldChangeEvent;
use supercrafter333\KnockFFA\KnockFFA;
use supercrafter333\KnockFFA\Manager\MessageManagement\MsgMgr;
use supercrafter333\KnockFFA\Utils\FFAPlayer;
use supercrafter333\KnockFFA\Manager\MessageManagement\MessageList;

/**
 *
 */
class WorldManager
{

    /**
     * @var World
     */
    public static World $selectedMap;

    /**
     * @param World|null $newWorld
     * @return World
     */
    public static function doWorldChange(World $newWorld = null): World
    {
        if ($newWorld !== null) {
            $ev = new FFAWorldChangeEvent(self::getSelectedWorld(), $newWorld);
            $ev->call();
            if ($ev->isCancelled()) throw new AssumptionFailedError("[KnockFFA] -> Can't change the world because the 'FFAWorldChangeEvent' is cancelled!");
            self::$selectedMap = $ev->getNewWorld();
        }
        $realWorlds = [];
        foreach (KnockFFA::getInstance()->getConfig()->get("world", []) as $worldName) {
            $world = self::worldCheck($worldName);
            if ($world instanceof World) {
                $realWorlds[] = $world;
            }
        }
        if (!is_array($realWorlds) || count($realWorlds, COUNT_RECURSIVE) <= 0) {
            throw new AssumptionFailedError("[KnockFFA] -> Can't get a real world from config.yml");
        }
        $preNewWorld = $realWorlds[array_rand($realWorlds)];
        $ev = new FFAWorldChangeEvent(self::getSelectedWorld(), $preNewWorld);
        $ev->call();
        if ($ev->isCancelled()) throw new AssumptionFailedError("[KnockFFA] -> Can't change the world because the 'FFAWorldChangeEvent' is cancelled!");
        $newWorld = $ev->getNewWorld();
        self::$selectedMap = $newWorld;
        foreach ($newWorld->getPlayers() as $worldPlayer) {
            if ($worldPlayer instanceof FFAPlayer) {
                $worldPlayer->doWorldChange();
                $worldPlayer->sendMessage(str_replace("{map_name}", $newWorld->getDisplayName(), MsgMgr::getMsg(MessageList::MSG_MAP_CHANGE_DONE)));
            }
        }
        return $newWorld;
    }

    /**
     * @param string $worldName
     * @return World|null
     */
    public static function worldCheck(string $worldName): ?World
    {
        $knockFFA = KnockFFA::getInstance();
        $worldMgr = $knockFFA->getServer()->getWorldManager();
        if (!$worldMgr->isWorldGenerated($worldName)) return null;
        if (!$worldMgr->isWorldLoaded($worldName)) $worldMgr->loadWorld($worldName);
        return $worldMgr->getWorldByName($worldName);
    }

    public static function getSelectedWorld(): World
    {
        if (!isset(self::$selectedMap) || !self::$selectedMap instanceof World) {
            self::doWorldChange2();
        }
        return self::$selectedMap;
    }

    public static function sendWorldMessage(string $message)
    {
        $selectedWorld = self::getSelectedWorld();
        foreach ($selectedWorld->getPlayers() as $player) {
            $player->sendMessage($message);
        }
    }

    /**
     * @param World|null $newWorld
     * @return World
     */
    private static function doWorldChange2(World $newWorld = null): World
    {
        if ($newWorld !== null) self::$selectedMap = $newWorld;
        $realWorlds = [];
        foreach (KnockFFA::getInstance()->getConfig()->get("worlds", []) as $worldName) {
            $world = self::worldCheck($worldName);
            if ($world instanceof World) {
                $realWorlds[] = $world;
            }
        }
        if (!is_array($realWorlds) || count($realWorlds, COUNT_RECURSIVE) <= 0) {
            throw new AssumptionFailedError("[KnockFFA] -> Can't get a real world from config.yml");
        }
        $newWorld = $realWorlds[array_rand($realWorlds)];
        self::$selectedMap = $newWorld;
        return $newWorld;
    }
}