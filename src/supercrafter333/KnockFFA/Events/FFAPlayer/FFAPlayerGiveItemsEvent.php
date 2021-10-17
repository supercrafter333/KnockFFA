<?php

namespace supercrafter333\KnockFFA\Events\FFAPlayer;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\item\Item;
use supercrafter333\KnockFFA\Utils\FFAPlayer;

/**
 *
 */
class FFAPlayerGiveItemsEvent extends Event implements Cancellable
{
    use CancellableTrait;

    /**
     * @var FFAPlayer
     */
    private FFAPlayer $FFAPlayer;

    /**
     * @var Item[]
     */
    private array $items;

    /**
     * @param FFAPlayer $FFAPlayer
     * @param Item[] $items
     */
    public function __construct(FFAPlayer $FFAPlayer, array $items)
    {
        $this->FFAPlayer = $FFAPlayer;
        $this->items = $items;
    }

    /**
     * @return FFAPlayer
     */
    public function getFFAPlayer(): FFAPlayer
    {
        return $this->FFAPlayer;
    }

    /**
     * @param Item[] $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}