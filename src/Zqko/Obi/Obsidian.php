<?php

namespace Zqko\Obi;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\block\Block;
use pocketmine\block\Obsidian;
use pocketmine\block\BlockFactory;

class Obsidian extends \pocketmine\block\Obsidian
{
    public function getBlastResistance(): float
    {
        return 10;//same as stone
    }
}