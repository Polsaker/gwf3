<?php
final class TrollCellar_Storage1 extends SR_SearchRoom
{
	public function getAreaSize() { return 12; }
	public function getSearchLevel() { return 5; }
	public function getSearchMaxAttemps() { return 2; }
	public function getFoundPercentage() { return 50.00; }

	public function getFoundText(SR_Player $player) { return $this->lang($player, 'found'); }
	public function getEnterText(SR_Player $player) { return $this->lang($player, 'enter'); }
	
	public function getSearchLoot(SR_Player $player)
	{
		$amt = SR_PlayerVar::getVal($player, 'TR_CE_1', 0);
		if ($amt >= 2)
		{
			return parent::getSearchLoot($player);
		}
		SR_PlayerVar::setVal($player, 'TR_CE_1', $amt+1);
		return array(SR_Item::createByName('Wine'));
	}
}
?>
