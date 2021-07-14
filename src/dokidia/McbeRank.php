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
            'domain' => 'MCBE Rank에 등록한 서버 도메인을 적어주세요.',
            'port' => $this->getServer()->getPort()
        ]);
        $this->db = $this->config->getAll();
    }
    
    public function onlineChecker($event){
        $url = 'http://be.diamc.kr:3500/api/servers/'. $this->db['domain'] . ':' . $this->db['port'];
        $data = (array) json_decode(Internet::getURL($url));
        $rank = $data['rank'];
        $online = $data['online'];
        
        if($online == true){
            $event->getPlayer()->sendMessage('§l§b[ §f알림 §b] §r§f현재 우리 서버의 순위는 §6' . $rank . '위§r§f 입니다! §r(MCBE Rank 기준)');
        }else{
            $event->getPlayer()->sendMessage('§l§b[ §f알림 §b] §r§fMCBE Rank에 이 서버가 등록되어 있지 않습니다. https://be.diamc.kr/new 에서 서버를 등록해 주세요.');
        }
    }
    
    public function onJoin(PlayerJoinEvent $event){
        $this->onlineChecker();
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $array) : bool{
        if($cmd->getName() == "서버순위"){
            $this->onlineChecker();
        }
        return true;
    }
}
