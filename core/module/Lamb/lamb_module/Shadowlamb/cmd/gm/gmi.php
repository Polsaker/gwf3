<?php
final class Shadowcmd_gmi extends Shadowcmd
{
	public static function execute(SR_Player $player, array $args)
	{
		$bot = Shadowrap::instance($player);
		if ( (count($args) < 2) || (count($args) > 3) ) {
			$bot->reply(Shadowhelp::getHelp($player, 'gmi'));
			return false;
		}

		$target = Shadowrun4::getPlayerByShortName($args[0]);
		if ($target === -1)
		{
			$player->message('The username is ambigious.');
			return false;
		}
		if ($target === false)
		{
			$player->message('The player is not in memory or unknown.');
			return false;
		}
		
		if (false !== ($error = self::checkCreated($target))) {
			$bot->reply(sprintf('The player %s has not started a game yet.', $args[0]));
			return false;
		}
		
		if (false === ($item = SR_Item::createByName($args[1]))) {
			$bot->reply(sprintf('The item %s could not be created.', $args[1]));
			return false;
		} 
		
		if (isset($args[2]))
		{
			if (!$item->isItemStackable())
			{
				$bot->reply('No amount for equipment!');
				return false;
			}
			$item->saveVar('sr4it_amount', intval($args[2]));
		}
		
		$b = chr(2);
		$target->giveItems(array($item), sprintf("{$b}[GM]_%s{$b}", $player->getName()));
		
		return true;
	}
}
?>
