<?php
final class Redmond_Alchemist_NPC extends SR_TalkingNPC
{
	public function getName() { return 'Carsten'; }
	
	public function getNPCModifiers()
	{
		return array('race'=>'elve');
	}
	
	public function getNPCQuests(SR_Player $player)
	{
		return array('Redmond_Alchemist1');
	}
	
	public function onNPCTalk(SR_Player $player, $word, array $args)
	{
		if (true === $this->onNPCQuestTalk($player, $word, $args))
		{
			return true;
		}
// 		$b = chr(2);
		switch ($word)
		{
			case 'magic':
				return $this->rply('magic');
// 				$this->reply('Yeah, I even sell some magic potions and elixirs.');
			
			case 'chemistry':
				$this->rply('chemistry');
// 				$this->reply("You can buy similar things here for chemistry or even {$b}magic{$b} potions.");
				$player->giveKnowledge('words', 'Magic');
				return true;
			
			case 'hello':
				return $this->rply('hello');
// 				$this->reply("Hello, my name is carsten and I sell items for {$b}chemistry{$b} and similar stuff.");
// 				break;
				
			default:
				return $this->rply('default', array($word));
// 				$this->reply("What do you mean with $word?");
// 				break;
		}
	}
}
?>