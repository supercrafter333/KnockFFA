<?php

namespace supercrafter333\KnockFFA\Events\FFAPlayer;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use supercrafter333\KnockFFA\Utils\FFAPlayer;

/**
 *
 */
class FFAPlayerDeathEvent extends Event implements Cancellable
{
    use CancellableTrait;

    /**
     * @var FFAPlayer
     */
    private FFAPlayer $FFAPlayer;

    /**
     * @var bool
     */
    private bool $wasHittedBefore = false;

    /**
     * @param FFAPlayer $FFAPlayer
     * @param bool $wasHittedBefore
     */
    public function __construct(FFAPlayer $FFAPlayer, bool $wasHittedBefore = false)
    {
        $this->FFAPlayer = $FFAPlayer;
        $this->wasHittedBefore = $wasHittedBefore;
    }

    /**
     * @return FFAPlayer
     */
    public function getFFAPlayer(): FFAPlayer
    {
        return $this->FFAPlayer;
    }

    /**
     * @return bool
     */
    public function wasHittedBefore(): bool
    {
        return $this->wasHittedBefore;
    }
}