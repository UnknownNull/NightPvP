<?php

declare(strict_types=1);

namespace Estem0\NightPvP;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Estem0\NightPvP\Night extends PluginBase implements Listener{
    

    public function onEnable() : void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        
    }

    public function onEntityDamageByEntity(EntityDamageEvent $event) 
    {
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $entity = $event->getEntity();
        if ($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if ($entity instanceof Player && $damager instanceof Player) {
                if (!$this->isNight($entity->getWorld()->getTime())) {
                    $worlds = $config->get('worlds');
                    foreach ($worlds as $world) {
                        if ($world !== $entity->getWorld()->getDisplayName()) {
                            $event->cancel();
                        }
                        if (!$damager->hasPermission("nightpvp.exempt.victim") && $damager->hasPermission("nightpvp.exempt.damager")) {
                            $event->cancel();
                        }
                    }
                }
            }
        }
    }
    
    public function isNight($t) {
        return ($t >= 10900 && $t < 17800);
    }
}
