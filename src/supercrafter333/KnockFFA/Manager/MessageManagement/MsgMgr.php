<?php

namespace supercrafter333\KnockFFA\Manager\MessageManagement;

use pocketmine\utils\Config;
use supercrafter333\KnockFFA\KnockFFA;
use supercrafter333\KnockFFA\Manager\MessageManagement\Languages;

/**
 *
 */
class MsgMgr
{

    /**
     * Language Data (PocketMine-MP Config)
     * @var Config $langData
     */
    public $langData;

    /**
     * Get a message.
     * [auto-replace: {line} to \n]
     *
     * @param string $message
     * @return string
     */
    public static function getMsg(string $message): string
    {
        $lang = Languages::getLanguageData();
        if (!$lang->exists($message)) {
            if (!Languages::getDefaultLanguageData()->exists($message)) return "ERROR! Message not found!";
            return str_replace("{line}", "\n", self::getPrefix() . Languages::getDefaultLanguageData()->get($message));
        }
        return str_replace("{line}", "\n", self::getPrefix() . $lang->get($message));
    }

    /**
     * Get a message without auto-replace.
     * @param string $message
     * @return string
     */
    public static function getMsgWithNoExtras(string $message): string
    {
        $lang = Languages::getLanguageData();
        if (!$lang->exists($message)) {
            if (!Languages::getDefaultLanguageData()->exists($message)) return "ERROR! Message not found!";
            return Languages::getDefaultLanguageData()->get($message);
        }
        return $lang->get($message);
    }

    /**
     * @return string
     */
    public static function getPrefix(): string
    {
        return KnockFFA::$prefix;
    }

    /**
     * @return string
     */
    public static function getNoPermMsg(): string
    {
        return self::getMsg("no-perm");
    }

    /**
     * @return string
     */
    public static function getOnlyIG(): string
    {
        return self::getMsg("only-In-Game");
    }
}