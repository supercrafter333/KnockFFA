<?php

namespace supercrafter333\KnockFFA\Items;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\Bow;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;

class KnockBow extends Bow
{

    public function __construct(string $customName)
    {
        self::setUnbreakable(true);
        self::setCustomName($customName);
        self::addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::KNOCKBACK), 1));
        parent::__construct(new ItemIdentifier(ItemIds::BOW, 0), "BowItem");
    }
}