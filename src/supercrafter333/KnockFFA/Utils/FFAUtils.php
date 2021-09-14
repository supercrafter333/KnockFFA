<?php

namespace supercrafter333\KnockFFA\Utils;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use supercrafter333\KnockFFA\KnockFFA;

/**
 *
 */
class FFAUtils
{

    /**
     * @return Config
     */
    public function getStatsConfig(): Config
    {
        if (!file_exists(KnockFFA::getInstance()->getDataFolder() . "stats/KnockFFAStats.yml")) {
            @mkdir(KnockFFA::getInstance()->getDataFolder() . "stats/");
        }
        return new Config(KnockFFA::getInstance()->getDataFolder() . "stats/KnockFFAStats.yml", Config::YAML);
    }

    /**
     * @param string $playername
     * @return bool
     */
    public function isPlayerInitialized(string $playername): bool{
        return KnockFFA::getInstance()->getUtils()->getStatsConfig()->exists($playername);
    }

    /**
     * @param Player $player
     */
    public function initializedCheck(Player $player)
    {
        if (!KnockFFA::getInstance()->getUtils()->isPlayerInitialized($player->getName())) {
            $player->initialize();
        }
    }
}