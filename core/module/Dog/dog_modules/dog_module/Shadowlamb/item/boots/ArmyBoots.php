<?php
final class Item_ArmyBoots extends SR_Boots
{
	public function getItemLevel() { return 10; }
	public function getItemPrice() { return 150; }
	public function getItemWeight() { return 950; }
	public function getItemDescription() { return 'Gray/greenish army boots.'; }
	public function getItemModifiersA(SR_Player $player)
	{
		return array(
			'defense' => 0.4,
			'marm' => 0.6,
			'farm' => 0.6,
		);
	}
}
?>