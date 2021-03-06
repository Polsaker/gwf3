<?php
final class Quest_Redmond_Johnson_2 extends SR_Quest
{
// 	public function getQuestName() { return 'BikersOutfit'; }
// 	public function getQuestDescription() { return 'Bring a BikerJacket and a BikerHelmet to Mr.Johnson in the Redmond\'s Trolls\' Inn.'; }

	public function getRewardXP() { return 5; }
	public function getRewardNuyen() { return 650; }
		
	public function checkQuest(SR_NPC $npc, SR_Player $player)
	{
		$data = $this->getQuestData();
		if (!isset($data['J'])) {
			if (1 === $this->giveQuesties($player, $npc, 'BikerJacket', 0, 1)) {
				$this->sendStatusUpdate($player);
				$data['J'] = 1;
			}
		}
		if (!isset($data['H'])) {
			if (1 === $this->giveQuesties($player, $npc, 'BikerHelmet', 0, 1)) {
				$this->sendStatusUpdate($player);
				$data['H'] = 1;
			}
		}
		$this->saveQuestData($data);
		
		if (isset($data['J']) && isset($data['H']))
		{
			$this->onSolve($player);
		}
		elseif (isset($data['J']))
		{
			$npc->reply($this->lang('missH'));
// 			$npc->reply('Now only the BikerHelmet is missing. Please bring it to me, too');
		}
		elseif (isset($data['H']))
		{
			$npc->reply($this->lang('missJ'));
// 			$npc->reply('Now only the BikerJacket is missing. Please bring it to me, too');
		}
		else
		{
			$npc->reply($this->lang('missB'));
// 			$npc->reply('I need a BikerJacket and a BikerHelmet. Please bring me those items.');
		}
		return true;
	}
	
	public function onQuestSolve(SR_Player $player)
	{
		return $player->message($this->lang('reward', array($this->displayRewardNuyen(), $this->getRewardXP())));
// 		$player->message(sprintf('Mr.Johnson hands you the payment: %s and %d XP.', Shadowfunc::displayNuyen($ny), $xp));
// 		$player->giveNuyen($ny);
// 		$player->giveXP($xp);
	}
}
?>