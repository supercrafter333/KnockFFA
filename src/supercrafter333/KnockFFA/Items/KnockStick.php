<?php

namespace supercrafter333\KnockFFA\Items;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\KnockbackEnchantment;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\Stick;

class KnockStick extends Stick
{

    public function __construct(string $customName)
    {
        parent::__construct(new ItemIdentifier(ItemIds::STICK, 0), "StickItem");
        self::setCustomName($customName);
        self::addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::KNOCKBACK), 1));
    }
}