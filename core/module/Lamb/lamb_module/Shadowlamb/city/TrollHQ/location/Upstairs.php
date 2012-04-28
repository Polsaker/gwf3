<?php
final class TrollHQ_Upstairs extends SR_Tower
{
	public function getFoundText(SR_Player $player) { return $this->lang($player, 'found'); }
// 	public function getFoundText(SR_Player $player) { return "You found a stair that leads up to the second floor."; }
	public function getFoundPercentage() { return 50.00; }
	public function onEnter(SR_Player $player)
	{
		return $this->teleportOutside($player, 'TrollHQ2_Downstairs');
	}
}
?>
