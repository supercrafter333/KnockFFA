<?php

namespace supercrafter333\KnockFFA\Tasks;

use pocketmine\scheduler\Task;
use supercrafter333\KnockFFA\Manager\WorldManagement\WorldManager;
use supercrafter333\KnockFFA\Manager\MessageManagement\MessageList;
use supercrafter333\KnockFFA\Manager\MessageManagement\MsgMgr;

/**
 *
 */
class WorldChangeTask extends Task
{

    /**
     * @var int
     */
    private $defaultSeconds;

    /**
     * @var int
     */
    private $seconds;

    /**
     * @param int $seconds
     */
    public function __construct(int $seconds)
    {
        # Minutes => Seconds
        # 5 => 300
        # 10 => 600
        # 15 => 900
        # 30 => 1800
        # 60 => 3600
        $this->seconds = $seconds;
        $this->defaultSeconds = $seconds;
    }

    /**
     * Run function
     */
    public function onRun(): void
    {
        $this->seconds--;
        if ($this->seconds == 30) {
            $this->sendWorldMsg(str_replace("{seconds}", (string)$this->seconds, MsgMgr::getMsg(MessageList::MSG_MAP_CHANGE_COUNTD)));
        }
        if ($this->seconds == 15) {
            $this->sendWorldMsg(str_replace("{seconds}", (string)$this->seconds, MsgMgr::getMsg(MessageList::MSG_MAP_CHANGE_COUNTD)));
        }
        if ($this->seconds < 10) {
            $this->sendWorldMsg(str_replace("{seconds}", (string)$this->seconds, MsgMgr::getMsg(MessageList::MSG_MAP_CHANGE_COUNTD)));
        }
        if ($this->seconds <= 0) {
            $this->sendWorldMsg(str_replace("{seconds}", (string)$this->seconds, MsgMgr::getMsg(MessageList::MSG_MAP_CHANGE)));
            $this->seconds = $this->defaultSeconds;
        }
    }

    /**
     * @param int $seconds
     */
    public function setSeconds(int $seconds): void
    {
        $this->seconds = $seconds;
    }

    /**
     * @param string $message
     */
    private function sendWorldMsg(string $message): void
    {
        $selectedWorld = WorldManager::getSelectedWorld();
        foreach ($selectedWorld->getPlayers() as $player) {
            $player->sendMessage($message);
        }
    }
}