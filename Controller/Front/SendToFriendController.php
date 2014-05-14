<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia                                                                       */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*      along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace SendToFriend\Controller\Front;

use SendToFriend\Form\SendToFriendForm;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\ConfigQuery;
use Thelia\Model\ProductQuery;

/**
 * Class SendToFriendController
 * @package SendToFriend\Controller\Front
 * @author Michaël Espeche <mespeche@openstudio.fr>
 */
class SendToFriendController extends BaseFrontController {


    public function send() {

        $error_message = false;
        $contactForm = new SendToFriendForm($this->getRequest());

        try {
            $form = $this->validateForm($contactForm);

            $productId = $form->get('product_id')->getData();

            $locale = $this->getSession()->getLang()->getLocale();

            $product = ProductQuery::create()->joinWithI18n($locale)->filterByPrimaryKey($productId)->findOne();

            if (null === $product) {
                throw new \InvalidArgumentException(sprintf("%d product id does not exist", $productId));
            }

            $subject = Translator::getInstance()->trans('A contact wants to share a product with you : ', array(), 'sendtofriend') . $product->getRef() . ' - ' . $product->getTitle();

            $body = $subject . "\n\r";
            $body .= Translator::getInstance()->trans('You can access this product in the following link : ', array(), 'sendtofriend') . $product->getUrl();
            $body .= "\n\r----------------------------------------\n\r";
            $body .= $form->get('message')->getData();
            $body .= "\n\r" . Translator::getInstance()->trans('This message was sent by : ', array(), 'sendtofriend') . $form->get('email')->getData();


            $message = \Swift_Message::newInstance($subject)
                ->addFrom($form->get('email')->getData(), ConfigQuery::read('store_name'))
                ->addTo($form->get('friend-email')->getData())
                ->setBody($body)
            ;

            $this->getMailer()->send($message);

        } catch (FormValidationException $e) {
            $error_message = $e->getMessage();
        }

        if ($error_message !== false) {
            \Thelia\Log\Tlog::getInstance()->error(sprintf('Error during sending mail : %s', $error_message));

            $contactForm->setErrorMessage($error_message);

            $this->getParserContext()
                ->addForm($contactForm)
                ->setGeneralError($error_message)
            ;

            $this->redirect('/');

        } else {
            $this->redirect($form->get('return_url')->getData() . '&sendtofriend_success=1');
        }

    }

} 
