<?php
namespace gallery\system\event\listener;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\user\jcoins\UserJCoinsStatementHandler;

/**
 * JCoins create image listener.
 *
 * @author		2017-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		de.wcflabs.gallery.jcoins
 */
class JCoinsCreateImageListener implements IParameterizedEventListener {
	/**
	 * @inheritdoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (!MODULE_JCOINS) return;
		
		switch ($eventObj->getActionName()) {
			case 'triggerPublication':
				foreach ($eventObj->getObjects() as $object) {
					if (!$object->isDeleted && !$object->isDisabled && $object->userID) {
						UserJCoinsStatementHandler::getInstance()->create('de.wcflabs.jcoins.statement.image', $object->getDecoratedObject());
					}
				}
				break;
				
				// 'enable' calls triggerPublication
				
			case 'disable':
				foreach ($eventObj->getObjects() as $object) {
					if (!$object->isDeleted && $object->userID) {
						UserJCoinsStatementHandler::getInstance()->revoke('de.wcflabs.jcoins.statement.image', $object->getDecoratedObject());
					}
				}
				break;
				
			case 'trash':
				foreach ($eventObj->getObjects() as $object) {
					if (!$object->isDisabled && $object->userID) {
						UserJCoinsStatementHandler::getInstance()->revoke('de.wcflabs.jcoins.statement.image', $object->getDecoratedObject());
					}
				}
				break;
				
			case 'restore':
				foreach ($eventObj->getObjects() as $object) {
					if (!$object->isDisabled && $object->userID) {
						UserJCoinsStatementHandler::getInstance()->create('de.wcflabs.jcoins.statement.image', $object->getDecoratedObject());
					}
				}
				break;
		}
	}
}
