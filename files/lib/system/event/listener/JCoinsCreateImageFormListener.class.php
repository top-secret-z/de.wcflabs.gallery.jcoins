<?php
namespace gallery\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\exception\NamedUserException;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;
use wcf\system\WCF;

/**
 * JCoins create image form listener.
 *
 * @author		2017-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		de.wcflabs.gallery.jcoins
 */
class JCoinsCreateImageFormListener implements IParameterizedEventListener {
	/**
	 * @inheritdoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (!MODULE_JCOINS || JCOINS_ALLOW_NEGATIVE) return;
		if (!WCF::getSession()->getPermission('user.jcoins.canEarn') || !WCF::getSession()->getPermission('user.jcoins.canUse')) return;
		
		$statement = UserJCoinsStatementHandler::getInstance()->getStatementProcessorInstance('de.wcflabs.jcoins.statement.image');
		
		if ($statement->calculateAmount() < 0 && ($statement->calculateAmount() * -1) > WCF::getUser()->jCoinsAmount) {
			throw new NamedUserException(WCF::getLanguage()->getDynamicVariable('wcf.jcoins.amount.tooLow'));
		}
		
		// check whether the user can create an album 
		$statement = UserJCoinsStatementHandler::getInstance()->getStatementProcessorInstance('de.wcflabs.jcoins.statement.album');
		
		if ($statement->calculateAmount() < 0 && ($statement->calculateAmount() * -1) > WCF::getUser()->jCoinsAmount) {
			WCF::getTPL()->assign([
					'jcoinsCanCreateAlbum' => false
			]);
		}
	}
}
