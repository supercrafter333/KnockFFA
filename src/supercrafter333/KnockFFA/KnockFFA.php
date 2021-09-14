<?php

namespace supercrafter333\KnockFFA;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use supercrafter333\KnockFFA\Manager\MessageManagement\MsgMgr;
use supercrafter333\KnockFFA\Tasks\PlayerCheckTask;
use supercrafter333\KnockFFA\Tasks\PlayerGiveArrowTask;
use supercrafter333\KnockFFA\Tasks\WorldChangeTask;
use supercrafter333\KnockFFA\Utils\FFAData;
use supercrafter333\KnockFFA\Utils\FFAPlayer;
use supercrafter333\KnockFFA\Utils\FFAUtils;
use supercrafter333\KnockFFA\Utils\GameOptions;
use supercrafter333\KnockFFA\Manager\MessageManagement\Languages;
use supercrafter333\KnockFFA\Manager\WorldManagement\WorldManager;

/**
 *
 */
class KnockFFA extends PluginBase
{

    /**
     * Get the version of KnockFFA. (As string)
     */
    public const VERSION = "1.0.0";

    /**
     * Get the prefix
     * @var string $prefix
     */
    public static $prefix;

    /**
     * Get the plugin base of KnockFFA (KnockFFA Class)
     * @var self $instance
     */
    protected static $instance;

    /**
     * On plugin loading. (That's before enabling)
     */
    public function onLoad(): void
    {
        self::$instance = $this;
        @mkdir($this->getDataFolder() . "stats/");
        @mkdir($this->getDataFolder() . "languages/");
    }

    /**
     * On plugin enabling.
     */
    public function onEnable(): void
    {
        //TODO: create command for skip map change
        $this->versionCheck(true); #--> UPDATE CONFIG DATAs
        $server = $this->getServer();
        $server->getPluginManager()->registerEvents(new EventListener(), $this);
        self::$prefix = MsgMgr::getMsgWithNoExtras("prefix");
        $config = $this->getConfig();
        # array $worlds[], int $spawnHeight = 64, int $giveItemsHeight = 55, int $deathHeight = 10
        GameOptions::setOptions($config->get("worlds", []), (int)$config->get("spawn-height"), (int)$config->get("give-items-height"), (int)$config->get("death-height"));
        $this->getScheduler()->scheduleRepeatingTask(new PlayerCheckTask(), 2);
        $this->getScheduler()->scheduleRepeatingTask(new WorldChangeTask((int)$config->get("world-change-after")), 20);
        $this->getScheduler()->scheduleRepeatingTask(new PlayerGiveArrowTask(), (int)$config->get("give-new-arrows-after")*20);
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        return self::$instance;
    }

    /**
     * @return string
     */
    public function getFile2(): string
    {
        return $this->getFile();
    }

    /**
     * Check the version of theSpawn.
     *
     * @param bool $update
     */
    private function versionCheck(bool $update = true)
    {
        $version = self::VERSION;
        if (!$this->getConfig()->exists("version") || $this->getConfig()->get("version") !== $version) {
            if ($update == true) {
                $this->getLogger()->debug("OUTDATED CONFIG.YML!! You config.yml is outdated! Your config.yml will automatically updated!");
                if (file_exists($this->getDataFolder() . "oldConfig.yml")) {
                    unlink($this->getDataFolder() . "oldConfig.yml");
                }
                rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "oldConfig.yml");
                $this->saveResource("config.yml");
                $this->getLogger()->debug("config.yml Updated for version: §b$version");
                $this->getLogger()->notice("INFORMATION: Your old config.yml can be found under `oldConfig.yml`");
            } else {
                $this->getLogger()->warning("Your config.yml is outdated but that's not so bad.");
            }
        }
        if (Languages::getLanguage() == Languages::LANG_CUSTOM && (!Languages::getCustomLanguageData()->exists("version") || Languages::getCustomLanguageData()->get("version") !== $version)) {
            if ($update == true) {
                $this->getLogger()->debug("OUTDATED " . Languages::LANG_CUSTOM . ".yml!! Your " . Languages::LANG_CUSTOM . ".yml is outdated! Your " . Languages::LANG_CUSTOM . ".yml will automatically updated!");
                if (file_exists($this->getDataFolder() . "languages/" . Languages::LANG_CUSTOM . "Old.yml")) {
                    unlink($this->getDataFolder() . "languages/" . Languages::LANG_CUSTOM . "Old.yml");
                }
                rename($this->getDataFolder() . "languages/" . Languages::LANG_CUSTOM . ".yml", $this->getDataFolder() . "languages/" . Languages::LANG_CUSTOM . "Old.yml");
                $this->saveResource("languages/" . Languages::LANG_CUSTOM . ".yml");
                $this->getLogger()->debug(Languages::LANG_CUSTOM . ".yml Updated for version: §b$version");
                $this->getLogger()->notice("INFORMATION: Your old " . Languages::LANG_CUSTOM . ".yml can be found under " . Languages::LANG_CUSTOM . "`Old.yml`");
            } else {
                $this->getLogger()->warning("Your " . Languages::LANG_CUSTOM . ".yml is outdated but that's not so bad.");
            }
        }
    }

    /**
     * @return FFAUtils
     */
    public function getUtils(): FFAUtils
    {
        return new FFAUtils();
    }

    /**
     * @param string $playerName
     * @return FFAData
     */
    public function getFFAData(string $playerName): FFAData
    {
        return new FFAData($playerName);
    }

    /**
     * @param Player $player
     */
    public function fixPlayer(Player $player)
    {
        if (!$player instanceof FFAPlayer) return;
        if ($player->getWorld()->getDisplayName() !== WorldManager::getSelectedWorld()->getDisplayName()) {
            $player->doWorldChange();
        }
    }
}