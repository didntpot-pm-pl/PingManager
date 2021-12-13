<?php

declare(strict_types=1);

namespace DidntPot\PingManager;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
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

        if($ev->isCancelled()) return;

        if($ping > $this->plugin->getConfig()->get("max_ping", 200))
        {
            $msg = $this->plugin->getConfig()->get("msg_kick_player", "&cYour ping ({max_ping}ms) is unstable.");
            $msg = str_replace("{max_ping}", $this->plugin->getConfig()->get("max_ping", 200), $msg);
            $msg = str_replace("&", "§", $msg);

            $player->kick($msg);
        }
    }

    /**
     * @param PlayerChatEvent $ev
     */
    public function onChat(PlayerChatEvent $ev)
    {
        $player = $ev->getPlayer();
        $message = $ev->getMessage();

        if($ev->isCancelled()) return;

        if($this->plugin->getConfig()->get("enable_ping_in_chat_message", true) === true)
        {
            $format = $this->plugin->getConfig()->get("format_for_chat_message", "&8[&a{ms}&8] &7{player_name}&f: &7{message}");
            $format = str_replace("{ms}", "" . $player->getNetworkSession()->getPing(), $format);
            $format = str_replace("{player_name}", $player->getDisplayName(), $format);
            $format = str_replace("{message}", $message, $format);
            $format = str_replace("&", "§", $format);

            $ev->setFormat($format);
        }
    }
}