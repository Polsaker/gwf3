<?php
final class BAIM_ValidateMC extends GWF_Method
{
	const SHA512_LEN = 128;
	const SHARED_SECRET = 'isgX93$4t7.dgh84t';
	
	public function execute()
	{
		GWF_Website::plaintext();
		
		if (false === ($uid = Common::getGetString('id')))
		{
			return $this->garbage();
		}
		if (false === ($token = Common::getGetString('token')))
		{
			return $this->garbage();
		}
		if (false === ($mc = Common::getGetString('mc')))
		{
			return $this->garbage();
		}
		
		return $this->validate($uid, $token, $mc);
	}
	
	private function garbage($real_token='???', $real_mc='???', $ext_msg='somethings wrong')
	{
		$msg = sprintf('InvalidMC: uid=%d, token=%s(%s), mc=%s(%s): %s.', Common::getGet('id')-1000, Common::getGet('token'), $real_token, Common::getGet('mc'), $real_mc, $ext_msg);
		GWF_Log::log('baim_log.txt', $msg);
		return GWF_Random::randomKey(self::SHA512_LEN, GWF_Random::HEXLOWER);
	}
	
	private function validate($userid, $token, $mc)
	{
		$userid = ((int)$userid) - 1000;
		if (false === ($row = BAIM_MC::getByUID($userid))) {
			return $this->garbage('???', '???', 'Unknown UID');
		}
		
		$real_token = $row->getToken();
		if ($real_token !== $token) {
			return $this->garbage($real_token, '???', 'Wrong token');
		}

		# No MC has been set for this user yet... set it :)
		if ($row->getMC() === NULL) {
			if (false === ($row->changeMC($mc))) {
				return $this->garbage($real_token, '???', 'Cannot change MC');
			}
		}
		$real_mc = $row->getMC();
		
		if ($row->isExpired() === true) {
			return $this->garbage($real_token, $real_mc, 'has expired');
		}
		
		if ($userid == 2604)
		{
			# general key
		}
		elseif ($row->getMC() !== $mc) {
			return $this->garbage($real_token, $real_mc, 'wrong MC');
		}
		
		return $this->hash($row);
	}
	
	private function hash(BAIM_MC $row)
	{
		$s = self::SHARED_SECRET;
		return strtolower(hash('SHA512', $s.$row->getToken().$s.$row->getMC().$s));
	}
}
?>
