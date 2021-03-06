<?php
final class Quest_NySoft_Andrew1 extends SR_Quest
{
	public function getRewardXP() { return 3; }
	public function getRewardNuyen() { return 900; }
	public function getQuestName() { return 'Surprise'; }
	
	public function getNeededAmount() { return 30; }
	
	public function getQuestDescription() { return sprintf('Bring %d/%d IDCards to Mr.Northwood, NySoft.', $this->getAmount(), $this->getNeededAmount()); }
	
	public function checkQuest(SR_NPC $npc, SR_Player $player)
	{
		$have_before = $this->getAmount();
		$need = $this->getNeededAmount();
		$have_after = $this->giveQuesties($player, $npc, 'IDCard', $have_before, $need);
		
		if ($have_after > $have_before)
		{
			$this->saveAmount($have_after);
		}
			
		if ($have_after >= $need)
		{
			$npc->reply('Well done chummer... take this little reward...');
			$this->onSolve($player);
		}
		else
		{
			$npc->reply(sprintf('Yes, we still need at least %d IDCards.', $need-$have_after));
		}
		return true;
	}
	
	public function onNPCQuestTalkB(SR_TalkingNPC $npc, SR_Player $player, $word, array $args=NULL)
	{
		$need = $this->getNeededAmount();
		switch ($word)
		{
			case 'shadowrun':
				$npc->reply("You say Renraku is doing illegal experiments? Our software might be involved?!... That's a big surprise to me!");
				$npc->reply("Well, if you could bring me $need IDCards I could send a secret team into their office.");
				$npc->reply('It is not a big deal for you, is it?');
				break;
			case 'confirm':
				$npc->reply("Accept?");
				break;
			case 'yes':
				$npc->reply('Alright!');
				break;
			case 'no':
				$npc->reply('Have a good day.');
				break;
		}
		return true;
	}
}
?>
