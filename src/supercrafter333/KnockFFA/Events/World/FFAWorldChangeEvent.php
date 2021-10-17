<?php

namespace supercrafter333\KnockFFA\Events\World;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\world\World;

class FFAWorldChangeEvent extends Event implements Cancellable
{
    use CancellableTrait;

    private World $world;

    private World $newWorld;

    /**
     * @param World $world
     */
    public function __construct(World $world, World $newWorld)
    {
        $this->world = $world;
        $this->newWorld = $newWorld;
    }

    /**
     * @return World
     */
    public function getWorld(): World
    {
        return $this->world;
    }

    /**
     * @return World
     */
    public function getNewWorld(): World
    {
        return $this->newWorld;
    }

    /**
     * @param World $newWorld
     */
    public function setNewWorld(World $newWorld): void
    {
        $this->newWorld = $newWorld;
    }
}