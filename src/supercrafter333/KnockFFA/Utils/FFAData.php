<?php

namespace supercrafter333\KnockFFA\Utils;

use pocketmine\utils\Config;
use supercrafter333\KnockFFA\KnockFFA;

/**
 *
 */
class FFAData
{

    /**
     * @var Config
     */
    public Config $data;

    /**
     * @var string
     */
    private string $playerName;

    /**
     * @var KnockFFA
     */
    private KnockFFA $pl;

    /**
     * @param string $playerName
     */
    public function __construct(string $playerName)
    {
        $this->playerName = strtolower($playerName);
        $this->pl = KnockFFA::getInstance();
        $this->data = $this->pl->getUtils()->getStatsConfig();
    }

    /**
     * @return Config
     */
    public function getData(): Config
    {
        return $this->data;
    }

    /**
     * @return array|null
     */
    public function getPlayerData(): ?array
    {
        return $this->data->get($this->playerName, []);
    }

    /**
     * @param mixed $data
     * @param mixed $subData
     */
    public function setPlayerData(mixed $data, mixed $subData): void
    {
        $this->data->setNested($this->playerName . "." . (string)$data, (string)$subData);
        $this->data->save();
    }

    /**
     * @param int $kills
     */
    public function setTotalKills(int $kills): void
    {
        $this->setPlayerData("Kills", $kills);
    }

    /**
     * @return int
     */
    public function getTotalKills(): int
    {
        if (!isset($this->getPlayerData()["Kills"])) return 0;
        return (int)$this->getPlayerData()["Kills"];
    }

    /**
     * @return int
     */
    public function addKill(): int
    {
        $kills = $this->getTotalKills();
        $kills++;
        $this->setTotalKills($kills);
        return $kills;
    }

    /**
     * @param int $deaths
     */
    public function setTotalDeaths(int $deaths): void
    {
        $this->setPlayerData("Deaths", $deaths);
    }

    /**
     * @return int
     */
    public function getTotalDeaths(): int
    {
        if (!isset($this->getPlayerData()["Deaths"])) return 0;
        return (int)$this->getPlayerData()["Deaths"];
    }

    /**
     * @return int
     */
    public function addDeath(): int
    {
        $deaths = $this->getTotalDeaths();
        $deaths++;
        $this->setTotalDeaths($deaths);
        return $deaths;
    }

    /**
     * @return int|float
     */
    public function getKD(): float
    {
        $deaths = $this->getTotalDeaths();
        $kills = $this->getTotalKills();
        return ($deaths == 0 ? $kills : round($kills / $deaths, 2));
    }

    /**
     * @return array
     */
    public function getStats(): array
    {
        return [
            "totalKills" => $this->getTotalKills(),
            "totalDeaths" => $this->getTotalDeaths(),
            "KD" => $this->getKD()
        ];
    }
}