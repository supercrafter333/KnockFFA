<?php

namespace supercrafter333\KnockFFA\Utils;

use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\player\Player;
use pocketmine\player\PlayerInfo;
use pocketmine\Server;
use supercrafter333\KnockFFA\Items\KnockBow;
use supercrafter333\KnockFFA\Items\KnockStick;
use supercrafter333\KnockFFA\Items\StatsItem;
use supercrafter333\KnockFFA\KnockFFA;
use supercrafter333\KnockFFA\Manager\MessageManagement\MessageList;
use supercrafter333\KnockFFA\Manager\MessageManagement\MsgMgr;
use supercrafter333\KnockFFA\Manager\WorldManagement\WorldManager;

/**
 *
 */
class FFAPlayer extends Player
{

    /** @var FFAPlayer|null */
    public $lastHitter;

    /** @var int|null */
    protected $killStreak = 0;

    /**
     * @var KnockFFA
     */
    private KnockFFA $pl;

    /**
     * @var FFAUtils
     */
    private FFAUtils $ffaUtils;

    /**
     * @var FFAData
     */
    private FFAData $ffaData;

    /**
     * @param Server $server
     * @param NetworkSession $session
     * @param PlayerInfo $playerInfo
     * @param bool $authenticated
     * @param Location $spawnLocation
     * @param CompoundTag|null $namedtag
     */
    public function __construct(Server $server, NetworkSession $session, PlayerInfo $playerInfo, bool $authenticated, Location $spawnLocation, ?CompoundTag $namedtag)
    {
        parent::__construct($server, $session, $playerInfo, $authenticated, $spawnLocation, $namedtag);
        $this->pl = KnockFFA::getInstance();
        $this->ffaUtils = $this->pl->getUtils();
        $this->ffaData = new FFAData($this->getName());
    }

    /**
     * Function initialize
     */
    public function initialize(): void
    {
        $config = KnockFFA::getInstance()->getUtils()->getStatsConfig();
        $config->setNested($this->getName() . ".Kills", 0);
        $config->setNested($this->getName() . ".Deaths", 0);
        $config->save();
    }

    /**
     * @return FFAData
     */
    public function getFFAData(): FFAData
    {
        return new FFAData($this->getName());
    }

    /**
     * @param FFAPlayer $hitter
     */
    public function setLastHitter(FFAPlayer $hitter): void
    {
        $this->pl->getUtils()->initializedCheck($hitter);
        $this->lastHitter = $hitter;
    }


    /**
     * Reset the lastest Hitter.
     */
    public function resetLastHitter(): void
    {
        $this->lastHitter = null;
    }

    /**
     * @return FFAPlayer|null
     */
    public function getLastHitter(): ?FFAPlayer
    {
        return $this->lastHitter instanceof FFAPlayer && $this->lastHitter->isOnline() ? $this->lastHitter : null;
    }

    /**
     * @param int $killstreak
     */
    public function setKillstreak(int $killstreak): void
    {
        $this->killStreak = $killstreak;
    }

    /**
     * @return int
     */
    public function addOneToKillstreak(): int
    {
        $killstreak = $this->getKillstreak();
        $killstreak++;
        $this->setKillstreak($killstreak);
        return $killstreak;
    }

    /**
     * @return int
     */
    public function getKillstreak(): int
    {
        return $this->killStreak;
    }

    /**
     *
     */
    public function doWorldChange(): void
    {
        $selWorld = WorldManager::getSelectedWorld();
        $this->teleport($selWorld->getSafeSpawn());
        $this->setKillstreak(0);
        $this->extinguish();
        $this->getHungerManager()->setFood(20);
        $this->setHealth(20);
        $this->getInventory()->clearAll();
        $this->giveWaitItems();
    }

    /**
     *
     */
    public function giveFFAItems(): void
    {
        $inv = $this->getInventory();
        $inv->clearAll();
        $inv->addItem(new KnockStick(KnockFFA::getInstance()->getConfig()->get("stick-name")));
        $inv->addItem(new KnockBow(KnockFFA::getInstance()->getConfig()->get("bow-name")));
    }

    /**
     *
     */
    public function giveWaitItems(): void
    {
        $inv = $this->getInventory();
        $inv->clearAll();
        $inv->setItem(0, new StatsItem(KnockFFA::getInstance()->getConfig()->get("stats-item-name")));
        //TODO: add more Items - $inv->setItem(1, ...);
    }

    /**
     * When the player was fallen in the void or death.
     */
    public function fallAndDeath(): void
    {
        $pl = KnockFFA::getInstance();
        $death = $this->ffaData->addDeath();
        $this->setKillstreak(0);
        $this->extinguish();
        $this->getHungerManager()->setFood(20);
        $this->setHealth(20);
        $this->getInventory()->clearAll();
        $selWorld = WorldManager::getSelectedWorld();
        $this->teleport($selWorld->getSafeSpawn());
        $this->giveWaitItems();
        $lastHitter = $this->getLastHitter();
        if ($lastHitter instanceof FFAPlayer) {
            $this->sendMessage(str_replace(["{deaths}", "{killstreak}", "{killer}"], [$death, $this->killStreak, $this->getLastHitter()->getName()], MsgMgr::getMsg(MessageList::MSG_KILLED)));
            $lastHitter->sendMessage(str_replace(["{name}"], [$this->getName()], MsgMgr::getMsg(MessageList::MSG_KILL)));
            $this->resetLastHitter();
            return;
        }
        $this->sendMessage(str_replace(["{deaths}", "{killstreak}"], [$death, $this->killStreak], MsgMgr::getMsg(MessageList::MSG_DEATH)));
    }
}