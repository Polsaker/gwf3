<?php
final class Chicago_Arena extends SR_Arena
{
	public function getFoundPercentage() { return 5.0; }
	public function getFoundText(SR_Player $player) { return 'You found the local arena, well hidden and illegal.'; }
	public function getEnterText(SR_Player $player) { return 'You enter the arena and walk to the director.'; }
// 	public function getHelpText(SR_Player $player) { $c = Shadowrun4::SR_SHORTCUT; return "You can use {$c}view and {$c}buy here."; }
	
	public function getNPCS(SR_Player $player) { return array('talk' => 'Chicago_ArenaGuy'); }
	
// 	public function getCommands(SR_Player $player) { return array('view', 'buy', 'challenge'); }
	
	public function getArenaEnemies(SR_Player $player)
	{
		return array(
			array(0x01, 'Chicago_SmallDrone', 'A small combat drone.', 250),
			array(0x02, 'Chicago_MediumDrone', 'A medium combat drone.', 500),
			array(0x04, 'Chicago_LargeDrone', 'A large combat drone.', 2000),
			array(0x08, 'Chicago_KillBot', 'A military comabat drone.', 5000),
		);
	}
	
// 	public function getStoreItems(SR_Player $player)
// 	{
// 		return array(
// 			array('Aygo1000'),
// 		);
		
// 	}
}
?>