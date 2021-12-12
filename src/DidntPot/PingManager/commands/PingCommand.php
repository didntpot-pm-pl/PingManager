<?php

declare(strict_types=1);

namespace DidntPot\PingManager\commands;

use DidntPot\PingManager\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;

class PingCommand extends Command implements PluginOwned
{
    public Loader $plugin;

    public function __construct(Loader $instance)
    {
        $name = 'ping';
        $usageMessage = '/ping [player_name]';

        parent::__construct($name, '', $usageMessage, []);

        $this->plugin = $instance;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!isset($args[0]) && $sender instanceof Player)
        {
            $msg = $this->plugin->getConfig()->get("msg_player_ping", "&aYour ping is {ms}.");

            $msg = str_replace("{ms}", "" . $sender->getNetworkSession()->getPing(), $msg);
            $msg = str_replace("&", "§", $msg);

            $sender->sendMessage($msg);
            return;
        }

        if(isset($args[0]) && $this->plugin->getServer()->getPlayerExact($args[0]) === null)
        {
            $msg = $this->plugin->getConfig()->get("msg_player_ping", "&c{target_name} is not online.");

            $msg = str_replace("{target_name}", $args[0], $msg);
            $msg = str_replace("&", "§", $msg);

            $sender->sendMessage($msg);
            return;
        }

        $target = $this->plugin->getServer()->getPlayerExact($args[0]);

        if($target instanceof Player)
        {
            $msg = $this->plugin->getConfig()->get("msg_target_ping", "&a{target_name}'s ping is {ms}.");

            $msg = str_replace("{ms}", "" . $target->getNetworkSession()->getPing(), $msg);
            $msg = str_replace("&", "§", $msg);

            $sender->sendMessage($msg);
        }
    }

    public function getOwningPlugin(): Loader
    {
        return $this->plugin;
    }
}