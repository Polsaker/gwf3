<?php
final class Harbor_Depot3 extends SR_SearchRoom
{
	public function getFoundPercentage() { return 50.0; }
	public function getAreaSize() { return 80; }
	
// 	public function getFoundText(SR_Player $player) { return 'You found a big Depot labeled "Depot3".'; }
// 	public function getEnterText(SR_Player $player) { return 'You enter the depot. You see a lot of big crates in the hall.'; }
	public function getFoundText(SR_Player $player) { return $this->lang($player, 'found'); }
	public function getEnterText(SR_Player $player) { return $this->lang($player, 'enter'); }
	
	public function isLocked() { return true; } 
	public function getLockLevel() { return 2.0; } # 0.0-10.0
	
	public function getSearchMaxAttemps() { return 3; }
	public function getSearchLevel() { return 8; }
	public function getSearchLoot(SR_Player $player)
	{
		$quest = SR_Quest::getQuest($player, 'Seattle_GJohnson4');
		$quest instanceof Quest_Seattle_GJohnson4;
		return $quest->giveElectronicParts($player);
	}
	
	public function onEnter(SR_Player $player)
	{
		if (!parent::onEnter($player))
		{
			return false;
		}
		$party = $player->getParty();
		$this->partyMessage($player, 'suprise');
// 		$party->notice(sprintf('Four depot guards surprise you and attack.'));
		SR_NPC::createEnemyParty('Harbor_DepotGuard','Harbor_DepotGuard','Harbor_DepotGuard','Harbor_DepotGuard')->fight($party, true);
		return true;
	}
}
?>
