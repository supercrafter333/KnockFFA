<?php

namespace supercrafter333\KnockFFA\Items;

use pocketmine\block\Block;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemUseResult;
use pocketmine\item\Totem;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use supercrafter333\KnockFFA\Forms\StatsForms;

class StatsItem extends Totem
{

    /**
     * @param string $customName
     */
    public function __construct(string $customName)
    {
        self::setCustomName($customName);
        parent::__construct(new ItemIdentifier(ItemIds::TOTEM, 0), "TotemItem");
    }

    /**
     * @param Player $player
     * @param Vector3 $directionVector
     * @return ItemUseResult
     */
    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult
    {
        if (!$player->hasItemCooldown($this)) {
            $this->onUse($player);
        }
        return parent::onClickAir($player, $directionVector);
    }

    /**
     * @param Player $player
     * @param Block $blockReplace
     * @param Block $blockClicked
     * @param int $face
     * @param Vector3 $clickVector
     * @return ItemUseResult
     */
    public function onInteractBlock(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): ItemUseResult
    {
        if (!$player->hasItemCooldown($this)) {
            $this->onUse($player);
        }
        return parent::onInteractBlock($player, $blockReplace, $blockClicked, $face, $clickVector); // TODO: Change the autogenerated stub
    }

    /**
     * @param Player $player
     */
    public function onUse(Player $player) #INFORMATION: this function is inspired by @jibixyt
    {
        $player->resetItemCooldown($this);
        $statsForms = new StatsForms();
        $statsForms->openPlayerInput($player);
    }
}