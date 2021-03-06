<?php
final class Seattle_Store extends SR_Store
{
// 	public function getFoundText(SR_Player $player) { return 'You find a small Store. There are no employees as all transactions are done by slot machines.'; }
	public function getFoundText(SR_Player $player) { return $this->lang($player, 'found'); }
	
	public function getFoundPercentage() { return 50.00; }
	
	public function getNPCS(SR_Player $player)
	{
		if ($this->isMaloisHere($player))
		{
			return array('talk'=>'Seattle_DElve2');
		}
		return parent::getNPCS($player);
	}
	
	private function isMaloisHere(SR_Player $player)
	{
		$quest = SR_Quest::getQuest($player, 'Seattle_IDS');
		if (false === $quest->isDone($player))
		{
			return false; # dunno about malois yet.
		}
		$quest1 = SR_Quest::getQuest($player, 'Chicago_HotelWoman1');
		if (true === $quest1->isAccepted($player))
		{
			return false; # He got busted.
		}
		return true;
	}
	
	public function getEnterText(SR_Player $player)
	{
		if ($this->isMaloisHere($player))
		{
			return $this->lang($player, 'enter2');
// 			return 'You enter the Seattle Store. No employees are around. In front of a slot machine you see Malois.';
		}
		else
		{
			return $this->lang($player, 'enter1');
// 			return 'You enter the Seattle Store. No people or employees are around.';
		}
	}
	
	public function getHelpText(SR_Player $player)
	{
		if ($this->isMaloisHere($player))
		{
// 			return parent::getHelpText($player)." Use #talk to talk to Malois.";
			return parent::getHelpText($player).' '.$this->lang($player, 'help');
		}
		else
		{
			return parent::getHelpText($player);
		}
	}
	
	public function getStoreItems(SR_Player $player)
	{
		return array(
			array('Milk', 100.0, 69.95),
			array('Stimpatch', 100.0, 1000),
			array('Ether', 100.0, 1000),
			array('AimWater', 100.0, 500),
			array('StrengthPotion', 100.0, 300),
			array('QuicknessElixir', 100.0, 400),
			array('Roses', 100.0, 499.95),
			array('Scanner_v2', 100.0, 349.95),
			array('Credstick', 100.0, 129.95),
			array('Backpack', 100.0, 350),
			array('Shovel', 100.0, 179),
			array('RacingBike', 100.0, 950),
		);
	}
}
?>
