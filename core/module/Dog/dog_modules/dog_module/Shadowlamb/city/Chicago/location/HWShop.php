<?php
final class Chicago_HWShop extends SR_School
{
	public function getFoundPercentage() { return 25.00; }
	public function getNPCS(SR_Player $player) { return array('talk' => 'Chicago_HWGuy'); }
	
	public function getFoundText(SR_Player $player) { return $this->lang($player, 'found'); }
	public function getEnterText(SR_Player $player) { return $this->lang($player, 'enter'); }
// 	public function getHelpText(SR_Player $player) { $c = Shadowrun4::SR_SHORTCUT; return "You can use {$c}talk, {$c}learn, {$c}courses, {$c}view and {$c}buy here."; }

	public function getFields(SR_Player $player)
	{
		return array(
			array('computers', 3500),
			array('electronics', 3500),
		);
	}
	public function getStoreItems(SR_Player $player)
	{
		return array(
			array('AT1024'),
			array('DG442'),
			array('GN4884'),
			array('NIA62'),
		);
	}
}
?>
