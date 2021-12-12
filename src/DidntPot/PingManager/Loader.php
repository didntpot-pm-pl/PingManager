<?php

declare(strict_types=1);

namespace DidntPot\PingManager;

use DidntPot\PingManager\commands\PingCommand;
use DidntPot\PingManager\tasks\TagTask;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase
{
    /** @var string */
    public string $nametag;

    // First plugin for poggit, don't blame me if something goes wrong ;( !
    public function onEnable(): void
    {
        $this->saveResource("settings.yml");
        $this->saveDefaultConfig();

        // Storing it to handle lag.
        $this->nametag = $this->getConfig()->get("format_for_nametag", "&aPing: {ms}");

        if($this->getConfig()->get("enable_ping_in_nametag", true) === true)
        {
            $this->getScheduler()->scheduleTask(new TagTask($this));
        }

        if($this->getConfig()->get("enable_ping_command", true) === true)
        {
            $this->getServer()->getCommandMap()->register("PingManager", new PingCommand($this));
        }

        if($this->getConfig()->get("enable_max_ping_kick", true) === true)
        {
            $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        }
    }
}