<?php
final class Quest_Delaware_DBarkeeper extends SR_Quest
{
// 	public function getQuestName() { return 'Whine'; }
// 	public function getQuestDescription() { return sprintf('Bring %d/%d bottles of wine to the barkeeper in the Delaware Dallas.', $this->getAmount(), $this->getNeededAmount()); }
	public function getQuestDescription() { return $this->lang('descr', array($this->getAmount(), $this->getNeededAmount())); }
	
	public function getNeededAmount() { return 8; }
	public function getRewardXP() { return 5; }
	public function getRewardNuyen() { return 600; }
	
	public function checkQuest(SR_NPC $npc, SR_Player $player)
	{
		$have_before = $this->getAmount();
		$need = $this->getNeededAmount();
		$have_after = $this->giveQuesties($player, $npc, 'Wine', $have_before, $need);
		$this->saveAmount($have_after);
		if ($have_after >= $need)
		{
			$npc->reply($this->lang('thx'));
// 			$npc->reply('Thank you chummer, this will last until my next delivery!');
			return $this->onSolve($player);
		}
		else
		{
			return $npc->reply($this->lang('more', array($need-$have_after)));
// 			return $npc->reply(sprintf("Please bring me %d more bottles of wine. Thank you.", $need-$have_after));
		}
	}
	
	public function onNPCQuestTalkB(SR_TalkingNPC $npc, SR_Player $player, $word, array $args=NULL)
	{
		$nw = $this->getNeededAmount();
		$dp = $this->displayRewardNuyen();
		switch ($word)
		{
			case 'shadowrun':
				$npc->reply($this->lang('sr1'));
// 				$npc->reply("Haha, you are looking for a job? I suppose you don't mean to stand behind the bar, right?");
				$npc->reply($this->lang('sr2'));
// 				$npc->reply("Hmm, well, my latest delivery of wine got stolen by the Trolls, but I need some bottles urgently.");
				$npc->reply($this->lang('sr3', array($nw, $dp)));
// 				$npc->reply("If you bring me $nw bottles, I will reward you with $dp. What do you think?");
				break;
			case 'confirm':
				$npc->reply($this->lang('confirm'));
// 				$npc->reply("Can you organize this stuff?");
				break;
			case 'yes':
				$npc->reply($this->lang('yes'));
// 				$npc->reply('Thank you, I am awaiting your delivery.');
				break;
			case 'no':
				$npc->reply($this->lang('no'));
// 				$npc->reply('Ok chummer.');
				break;
		}
		return true;
	}
}
?>
