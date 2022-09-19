<?php
namespace gallery\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;

/**
 * JCoins create album listener.
 *
 * @author		2017-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		de.wcflabs.gallery.jcoins
 */
class JCoinsCreateAlbumListener implements IParameterizedEventListener {
	/**
	 * @inheritdoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (!MODULE_JCOINS) return;
		
		switch ($eventObj->getActionName()) {
			case 'create':
				$returnValues = $eventObj->getReturnValues();
				$album = $returnValues['returnValues'];
				
				// need user
				if (!$album->userID) return;
				
				UserJCoinsStatementHandler::getInstance()->create('de.wcflabs.jcoins.statement.album', $album, ['userID' => $album->userID]);
				break;
				
			case 'delete':
				foreach ($eventObj->getObjects() as $object) {
					$album = $object->getDecoratedObject();
					
					// need user
					if (!$album->userID) continue;
					
					UserJCoinsStatementHandler::getInstance()->revoke('de.wcflabs.jcoins.statement.album', $album, ['userID' => $album->userID]);
				}
				break;
		}
	}
}
