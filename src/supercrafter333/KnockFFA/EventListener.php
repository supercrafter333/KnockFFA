<?php

namespace supercrafter333\KnockFFA;

use pocketmine\block\Fire;
use pocketmine\block\Lava;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use supercrafter333\KnockFFA\Manager\MessageManagement\MessageList;
use supercrafter333\KnockFFA\Manager\MessageManagement\MsgMgr;
use supercrafter333\KnockFFA\Manager\WorldManagement\WorldManager;
use supercrafter333\KnockFFA\Utils\FFAPlayer;

class EventListener implements Listener
{

    # New Player class is FFAPlayer
    public function onPlayerCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(FFAPlayer::class);
        $event->setBaseClass(FFAPlayer::class);
    }

    # Teleport to selected World and send Join messages on Join
    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        KnockFFA::getInstance()->fixPlayer($player);
        if ($player instanceof FFAPlayer) {
            $player->doWorldChange();
            WorldManager::sendWorldMessage(str_replace("{name}", $player->getName(), MsgMgr::getMsg(MessageList::MSG_JOIN_BR)));
        }
        $player->sendMessage(MsgMgr::getMsg(MessageList::MSG_JOIN));
    }

    # Send leave message on leaving
    public function onLeave(PlayerQuitEvent $event)
    {
        WorldManager::sendWorldMessage(str_replace("{name}", $event->getPlayer()->getName(), MsgMgr::getMsg(MessageList::MSG_LEAVE_BR)));
    }

    # Set lastHitter for FFAPLayer on Player Damage
    public function onPlayerDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();
        $damager = $event->getDamager();
        if ($player instanceof FFAPlayer && $damager instanceof FFAPlayer) {
            $player->setLastHitter($damager);
        }
    }

    # Cancel Fall and Fire Damage
    public function onDamage(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            if ($event->getCause() === EntityDamageEvent::CAUSE_FALL || $event->getCause() === EntityDamageEvent::CAUSE_FIRE || $event->getCause() === EntityDamageEvent::CAUSE_LAVA || $event->getCause() === EntityDamageEvent::CAUSE_FIRE_TICK) $event->cancel();
        }
    }

    # Cancel Fire and Lava Damage
    public function onBlockDamage(EntityDamageByBlockEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $player = $entity;
            if ($event->getCause() instanceof Fire) {
                $player->extinguish();
                $event->cancel();
            }
            if ($event->getCause() instanceof Lava) {
                $player->extinguish();
                $event->cancel();
            }
        }
    }

    # Cancel Arrow Damage
    public function onArrowDamage(EntityDamageByChildEntityEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $player = $entity;
            if ($event->getChild() instanceof Arrow) $event->cancel();
        }
    }

    # Fix other death bugs
    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();
        if (!$player instanceof FFAPlayer) return;
        $event->setDrops([]);
        $event->setKeepInventory(false);
        $event->setXpDropAmount(0);
        $player->fallAndDeath();
    }

    # Cancel Hunger
    public function onExhaust(PlayerExhaustEvent $event) {$event->cancel();}

    # Cancel Placing
    public function onPlace(BlockPlaceEvent $event) {$event->cancel();}

    # Cancel Breaking
    public function onBreak(BlockBreakEvent $event) {$event->cancel();}

    # Cancel Sign Changing
    public function onSignChange(SignChangeEvent $event) {$event->cancel();}

    #Cancel Entity-Explosions
    public function onEntityExplode(EntityExplodeEvent $event) {$event->cancel();}

    # Cancel Item-Dropping
    public function onDrop(PlayerDropItemEvent $event) {$event->cancel();}
}