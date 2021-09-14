<?php

namespace supercrafter333\KnockFFA\Utils;

/**
 *
 */
class GameOptions
{
    /**
     * Worlds to play
     * @var array
     */
    public static array $worlds = [];

    /**
     * The height, where the players will spawn.
     * @var int
     */
    public static int $spawnHeight = 64; # The height, where the players will spawn | Will be checked like: >= $spawnHeight

    /**
     * The height, where the players will get there items to play.
     * @var int
     */
    public static int $giveItemsHeight = 55; # The height, where the players will get there items to play (for example: KnockFFA-Stick)

    /**
     * The height, where the players will die.
     * @var int
     */
    public static int $deathHeight = 10; # The height, where the players will die | Will be checked like: <= $deathHeight

    /**
     * Setup the game options.
     * @param array $worlds
     * @param int $spawnHeight
     * @param int $giveItemsHeight
     * @param int $deathHeight
     */
    public static function setOptions(array $worlds, int $spawnHeight = 64, int $giveItemsHeight = 55, int $deathHeight = 10)
    {
        self::$worlds = $worlds;
        self::$spawnHeight = $spawnHeight;
        self::$giveItemsHeight = $giveItemsHeight;
        self::$deathHeight = $deathHeight;
    }
}