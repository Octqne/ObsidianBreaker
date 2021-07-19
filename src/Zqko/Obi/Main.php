<?php

namespace Zqko\Obi;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\block\Block;
use Zqko\Obi\Obsidian;
use pocketmine\block\Obsidian;
use pocketmine\block\BlockFactory;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\scheduler\ClosureTask;

class Main extends PluginBase implements Listener{

    public function onEnable(){
        BlockFactory::registerBlock(new MyObsidian(), true);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public $obsidians = [];
    public static $durability = 4;

    public function addToList(Block $b){
        $pos = $b->getFloorX()."_".$b->getFloorY()."_".$b->getFloorZ()."_".$b->getLevel()->getName();
        if(!isset($this->obsidians[$pos])){
            $this->obsidians[$pos] = self::$durability;
        }
    }
    public function existsBlock(Block $b){
        $pos = $b->getFloorX()."_".$b->getFloorY()."_".$b->getFloorZ()."_".$b->getLevel()->getName();
        return isset($this->obsidians[$pos]);
    }
    public function getDurability(Block $b){
        $pos = $b->getFloorX()."_".$b->getFloorY()."_".$b->getFloorZ()."_".$b->getLevel()->getName();
        if($this->existsBlock($b)){
            return $this->obsidians[$pos];
        }
        return self::$durability;
    }
    public function reduceDurability(Block $b, int $v = 1){
        $pos = $b->getFloorX()."_".$b->getFloorY()."_".$b->getFloorZ()."_".$b->getLevel()->getName();
        if($this->existsBlock($b)){
            $this->obsidians[$pos] = $this->getDurability($b) - $v;
            return true;
        }
        return false;
    }
    public function onExplode(EntityExplodeEvent $e){
        $bl = $e->getBlockList();
        $nbl = [];
        foreach($bl as $b){
            if($b->getId() == Item::OBSIDIAN){
                if($this->existsBlock($b) and $this->getDurability($b) <= 0){
                    $nbl[] = $b;
                }else{
                    $this->addToList($b);
                    $this->reduceDurability($b);
                }
            }else{
                $nbl[] = $b;
            }
        }
        $e->setBlockList($nbl);
    }
}