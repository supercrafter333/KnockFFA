<?php

namespace supercrafter333\KnockFFA\Events\FFAPlayer;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use supercrafter333\KnockFFA\Utils\FFAPlayer;

/**
 *
 */
class FFAPlayerPreDeathEvent extends Event implements Cancellable
{
    use CancellableTrait;

    /**
     * @var FFAPlayer
     */
    private FFAPlayer $FFAPlayer;

    /**
     * @param FFAPlayer $FFAPlayer
     */
    public function __construct(FFAPlayer $FFAPlayer)
    {
        $this->FFAPlayer = $FFAPlayer;
    }

    /**
     * @return FFAPlayer
     */
    public function getFFAPlayer(): FFAPlayer
    {
        return $this->FFAPlayer;
    }
}