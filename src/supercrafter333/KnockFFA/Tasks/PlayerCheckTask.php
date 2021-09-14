<?php

namespace supercrafter333\KnockFFA\Tasks;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\scheduler\Task;
use supercrafter333\KnockFFA\KnockFFA;
use supercrafter333\KnockFFA\Utils\FFAPlayer;
use supercrafter333\KnockFFA\Utils\GameOptions;

/**
 *
 */
class PlayerCheckTask extends Task
{

    /**
     * Run function
     */
    public function onRun(): void
    {
        $pl = KnockFFA::getInstance();
        $server = $pl->getServer();
        foreach ($server->getOnlinePlayers() as $onlinePlayer) {
            if ($onlinePlayer instanceof FFAPlayer) {
                $y = $onlinePlayer->getPosition()->getY();
                //$pl->getUtils()->initializedCheck($onlinePlayer);
                $onlinePlayer->extinguish();
                if ($y <= GameOptions::$giveItemsHeight && $y > GameOptions::$deathHeight && !$onlinePlayer->getInventory()->contains(ItemFactory::getInstance()->get(ItemIds::STICK))) { #INFORMATION: this check is inspired by @jibixyt
                    $onlinePlayer->giveFFAItems();
                }
                if ($y <= GameOptions::$deathHeight) {
                    $onlinePlayer->fallAndDeath();
                }
            }
        }
    }
}