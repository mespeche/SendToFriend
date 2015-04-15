<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace SendToFriend;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\Message;
use Thelia\Model\MessageQuery;
use Thelia\Module\BaseModule;

class SendToFriend extends BaseModule
{
    const DOMAIN = 'sendtofriend';

    const MESSAGE_NAME = 'sendtofriend.mail';
    const FORM_NAME    = 'front.sendToFriend.send';

    public function postActivation(ConnectionInterface $con = null)
    {
        // Create messages from templates, if not already defined
        $email_templates_dir = __DIR__.DS.'I18n'.DS.'email-templates'.DS;

        if (null === MessageQuery::create()->findOneByName(self::MESSAGE_NAME)) {
            $message = new Message();

            $message
                ->setName(self::MESSAGE_NAME)

                ->setLocale('en_US')
                ->setTitle('Product sharing email')
                ->setSubject('{$contact_name} wants to share this product with you: {$product_title}')
                ->setHtmlMessage(file_get_contents($email_templates_dir.'en.html'))
                ->setTextMessage(file_get_contents($email_templates_dir.'en.txt'))

                ->setLocale('fr_FR')
                ->setTitle('Message de partage de produit par mail')
                ->setSubject('{$contact_name} a partagé ce produit avec vous: {$product_title}')
                ->setHtmlMessage(file_get_contents($email_templates_dir.'fr.html'))
                ->setTextMessage(file_get_contents($email_templates_dir.'fr.txt'))

                ->save()
            ;
        }
    }

}
