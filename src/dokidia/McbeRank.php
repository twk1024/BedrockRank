<?php
namespace dokidia;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Internet;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;

class McbeRank extends PluginBase implements Listener{
    public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . 'Config.yml', Config::YAML, [
            'domain' => '서버 도메인을 적어주세요.',
            'port' => $this->getServer()->getPort()
        ]);
        $this->db = $this->config->getAll();
        
            
    }
    
    public function onJoin(PlayerJoinEvent $event){

        $url = 'http://be.diamc.kr:3500/api/servers/'. $this->db['domain'] . ':' . $this->db['port'];
        $data = (array) json_decode(Internet::getURL($url));
        $rank = $data['rank'];

           $event->getPlayer()->sendMessage('§l§b[ §f알림 §b] §r§f현재 우리 서버의 순위는 §6' . $rank . '위§r§f 입니다! §r(MCBE RANK 기준)');
        
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $array) : bool{
        if($cmd->getName() == "서버순위"){
            $url = 'http://be.diamc.kr:3500/api/servers/diamc.kr:19132' . $this->db['domain'] . ':' . $this->db['port'];
            $data = (array) json_decode(Internet::getURL($url));
            $rank = $data['rank'];
    
               $sender->getPlayer()->sendMessage('§l§b[ §f알림 §b] §r§f현재 우리 서버의 순위는 §6' . $rank . '위§r§f 입니다! §r(MCBE RANK 기준)');
            
        }
        return true;
    }
}
