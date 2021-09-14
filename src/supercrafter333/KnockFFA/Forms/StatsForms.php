<?php

namespace supercrafter333\KnockFFA\Forms;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use supercrafter333\KnockFFA\KnockFFA;
use supercrafter333\KnockFFA\Manager\MessageManagement\MessageList;
use supercrafter333\KnockFFA\Manager\MessageManagement\MsgMgr;
use supercrafter333\KnockFFA\Utils\FFAPlayer;

/**
 *
 */
class StatsForms
{

    /**
     * @param Player $player
     * @return CustomForm
     */
    public function openPlayerInput(Player $player): CustomForm
    {
        $form = new CustomForm(function (Player $player, array $data = null) {
            if (!isset($data["selected"]) || $data["selected"] === null || $data["selected"] == "") {
                $player->sendMessage(MsgMgr::getMsg(MessageList::MSG_FORMS_MISSING_INPUT));
                return;
            }
            $selRaw = $data["selected"];
            $selected = KnockFFA::getInstance()->getServer()->getPlayerByPrefix($selRaw);
            if ($selected instanceof Player) {
                $selectedName = $selected->getName();
                $this->openStats($player, $selectedName);
                return;
            }
            if (!KnockFFA::getInstance()->getUtils()->isPlayerInitialized($selRaw)) {
                $player->sendMessage(str_replace("{name}", $selRaw, MsgMgr::getMsg(MessageList::MSG_FORMS_PLAYER_NOT_FOUND)));
                return;
            }
            $this->openStats($player, $selRaw);
        });
        $form->setTitle(MsgMgr::getMsgWithNoExtras(MessageList::FORM_STATS_SELECT_TITLE));
        $form->addLabel(MsgMgr::getMsgWithNoExtras(MessageList::FORM_STATS_SELECT_CONTENT));
        $form->addInput(MsgMgr::getMsgWithNoExtras(MessageList::FORM_STATS_SELECT_PLAYERINPUT), "", $player->getName(), "selected");
        $form->sendToPlayer($player);
        return $form;
    }

    /**
     * @param Player $player
     * @param string $selectedName
     * @return SimpleForm
     */
    public function openStats(Player $player, string $selectedName): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null) use ($selectedName) {
            if ($data == "close" || $data == null) {
                return;
            }
        });
        $form->setTitle(MsgMgr::getMsgWithNoExtras(MessageList::FORM_STATS_STATS_TITLE));
        if ($player instanceof FFAPlayer) {
            $ffaData = $player->getFFAData();
            $content = str_replace(["{name}", "{kills}", "{deaths}", "{highestKillstreak}", "{KD}", "{line}"], [$player->getName(), $ffaData->getTotalKills(), $ffaData->getTotalDeaths(), $ffaData->getHighestKillstreak(), $ffaData->getKD(), "\n"], MsgMgr::getMsgWithNoExtras(MessageList::FORM_STATS_STATS_STATS));
            $form->setContent($content);
        } else {
            $form->setContent("§cERROR! Can't load stats!");
        }
        $form->addButton(str_replace("{line}", "\n", MsgMgr::getMsgWithNoExtras(MessageList::FORM_STATS_STATS_BUTTON_CLOSE)), -1, "", "close");
        $form->sendToPlayer($player);
        return $form;
    }
}