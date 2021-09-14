<?php

namespace supercrafter333\KnockFFA\Tasks;

use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\scheduler\Task;
use supercrafter333\KnockFFA\KnockFFA;

/**
 *
 */
class PlayerGiveArrowTask extends Task
{

    /**
     * onRun function.
     */
    public function onRun(): void
    {
        $server = KnockFFA::getInstance()->getServer();
        foreach ($server->getOnlinePlayers() as $onlinePlayer) {
            if (!$onlinePlayer->getInventory()->contains(ItemFactory::getInstance()->get(ItemIds::ARROW, 0, 16)) && $onlinePlayer->getInventory()->contains(ItemFactory::getInstance()->get(ItemIds::STICK))) {
                $arrowCount = $onlinePlayer->getInventory()->getHotbarSlotItem(8)->getCount();
                $onlinePlayer->getInventory()->setItem(8, ItemFactory::getInstance()->get(ItemIds::ARROW, 0, ($arrowCount + 1)));
            }
        }
    }
}