<?php
final class Quest_Seattle_Archery extends SR_Quest
{
// 	public function getQuestName() { return 'AngryBows'; }
// 	public function getQuestDescription() { return sprintf('Punish %s/%s Angry Elves and return to the Seattle_Archery.', $this->getAmount(), $this->getNeededAmount()); }
	public function getQuestDescription() { return $this->lang('descr', array($this->getAmount(), $this->getNeededAmount())); }

	public function getNeededAmount() { return 20; }
	
	public function checkQuest(SR_NPC $npc, SR_Player $player)
	{
		if (!$this->isInQuest($player))
		{
			return false;
		}
		
		if ($this->getAmount() >= $this->getNeededAmount())
		{
			$npc->reply($this->lang('thanks'));
// 			$npc->reply('Thank you very much!');
			$player->message($this->lang('reward'));
// 			$player->message(sprintf('The elve cheers and hands out a glowing dark bow.'));
			$bow = SR_Item::createByName('DarkBow');
			$mod1 = SR_Rune::randModifier($player, 10);
			$mod2 = SR_Rune::randModifier($player, 10);
			$modifiers = SR_Rune::mergeModifiers($mod1, $mod2);
			$bow->addModifiers($modifiers);
			$player->giveItems(array($bow), $npc->getName());
			$this->onSolve($player);
			return true;
		}
		else
		{
			$npc->reply($this->lang('more', array($this->getAmount(), $this->getNeededAmount())));
// 			$npc->reply(sprintf('I see you have punished %s of %s Angry Elves.', $this->getAmount(), $this->getNeededAmount()));
			return false;
		}
	}
}
?>