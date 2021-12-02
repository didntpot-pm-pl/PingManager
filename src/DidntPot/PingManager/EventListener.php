<?php

declare(strict_types=1);

namespace DidntPot\PingManager;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener
{
    /**
     * @var Loader
     */
    public Loader $plugin;

    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerLoginEvent $ev
     */
    public function onLogin(PlayerLoginEvent $ev)
    {
        $player = $ev->getPlayer();
        $ping = $player->getNetworkSession()->getPing();

        if($ping > $this->plugin->getConfig()->get("max_ping", 200))
        {
            $msg = $this->plugin->getConfig()->get("msg_kick_player", "&cYour ping ({max_ping}ms) is unstable.");
            $msg = str_replace("{max_ping}", $this->plugin->getConfig()->get("max_ping", 200), $msg);
            $msg = str_replace("&", "§", $msg);

            $player->kick($msg);
        }
    }

    /**
     * @param PlayerJoinEvent $ev
     */
    public function onJoin(PlayerJoinEvent $ev)
    {
        $player = $ev->getPlayer();
        $ping = $player->getNetworkSession()->getPing();

        if($ping > $this->plugin->getConfig()->get("max_ping", 200))
        {
            $msg = $this->plugin->getConfig()->get("msg_kick_player", "&cYour ping ({max_ping}ms) is unstable.");
            $msg = str_replace("{max_ping}", $this->plugin->getConfig()->get("max_ping", 200), $msg);
            $msg = str_replace("&", "§", $msg);

            $player->kick($msg);
        }
    }
}