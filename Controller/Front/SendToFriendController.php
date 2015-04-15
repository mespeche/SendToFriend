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
use SendToFriend\SendToFriend;
use Thelia\Controller\Front\BaseFrontController;
use Thelia\Core\Translation\Translator;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Log\Tlog;
use Thelia\Mailer\MailerFactory;
use Thelia\Model\ConfigQuery;
use Thelia\Model\ProductQuery;

/**
 * Class SendToFriendController
 * @package SendToFriend\Controller\Front
 * @author Michaël Espeche <mespeche@openstudio.fr>
 */
class SendToFriendController extends BaseFrontController
{
    public function send()
    {
        $sendToFriend = $this->createForm(SendToFriend::FORM_NAME);

        try {
            $form = $this->validateForm($sendToFriend);

            $data = $form->getData();

            $locale = $this->getSession()->getLang()->getLocale();

            $product = ProductQuery::create()
                ->joinWithI18n($locale)
                ->filterByPrimaryKey($data['product_id'])
                ->findOne();

            if (null === $product) {
                throw new \InvalidArgumentException(sprintf("%d product ID does not exist", $data['product_id']));
            }

            $this->getMailer()->sendEmailMessage(
                SendToFriend::MESSAGE_NAME,
                [ $data['email'] => $data['email'] ],
                [ $data['friend-email'] => '' ],
                [
                    'contact_name'    => $data['name'],
                    'contact_email'   => $data['email'],
                    'contact_message' => $data['message'],

                    'product_title'   => $product->getTitle(),
                    'product_ref'     => $product->getRef(),
                    'product_id'      => $product->getId(),
                ],
                $locale
            );

            // Redirect to the success URL
            return $this->generateRedirect($sendToFriend->getSuccessUrl());

        } catch (\Exception $e) {
            $error_message = $e->getMessage();
        }

        if ($error_message !== false) {
            Tlog::getInstance()->error(sprintf('Error during sending mail : %s', $error_message));

            $sendToFriend->setErrorMessage($error_message);

            $this->getParserContext()
                ->addForm($sendToFriend)
                ->setGeneralError($error_message)
            ;

            // We have here to display again the current template (2.2 only)
            if (method_exists($this->getParserContext(), 'getCurrentTemplateContext')) {
                return $this->render(
                    $this->getParserContext()->getCurrentTemplateContext()->getName(),
                    $this->getParserContext()->getCurrentTemplateContext()->getParameters()
                );
            }

            // At this point we are not able to guess the current template context, let's go to the index page...
        }
    }
}
