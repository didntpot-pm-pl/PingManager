<?php

declare(strict_types=1);

namespace DidntPot\PingManager\tasks;

use DidntPot\PingManager\Loader;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class TagTask extends Task
{
    /**
     * @var Loader
     */
    public Loader $plugin;

    /**
     * @param Loader $instance
     */
    public function __construct(Loader $instance)
    {
        $this->plugin = $instance;
    }

    /**
     * Actions to execute when run
     */
    public function onRun(): void
    {
        foreach(Server::getInstance()->getOnlinePlayers() as $player)
        {
            $nametag = $this->plugin->nametag;

            $nametag = str_replace("{ms}", "" . $player->getNetworkSession()->getPing(), $nametag);
            $nametag = str_replace("&", "§", $nametag);

            $player->setScoreTag($nametag);
        }
    }
}