<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
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
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/
namespace SendToFriend\Form;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

class SendToFriendForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            ->add('email', 'email', array(
                    'constraints' => array(
                        new NotBlank()
                    ),
                    'label' => Translator::getInstance()->trans('Your email address', array(), 'sendtofriend'),
                    'label_attr' => array(
                        'for' => 'sendtofriend-email'
                    )
                ))
            ->add('friend-email', 'email', array(
                    'constraints' => array(
                        new NotBlank()
                    ),
                    'label' => Translator::getInstance()->trans('The email address of your friend', array(), 'sendtofriend'),
                    'label_attr' => array(
                        'for' => 'sendtofriend-friend-email'
                    )
                ))
            ->add('message', 'text', array(
                    'constraints' => array(
                        new NotBlank()
                    ),
                    'label' => Translator::getInstance()->trans('Your message', array(), 'sendtofriend'),
                    'label_attr' => array(
                        'for' => 'sendtofriend-message'
                    )
                ))
            ->add("product_id", "hidden", array(
                    "constraints" => array(
                        new NotBlank(),
                        new GreaterThan(array('value' => 0))
                    )
                ))
            ->add("return_url", "hidden", array(
                    "constraints" => array(
                        new NotBlank()
                    )
                ))

        ;
    }

    public function getName()
    {
        return 'front_send_to_friend_send';
    }
}
