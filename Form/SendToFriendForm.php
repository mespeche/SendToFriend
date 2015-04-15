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

use SendToFriend\SendToFriend;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Form\BaseForm;
use Thelia\Model\Customer;

class SendToFriendForm extends BaseForm
{
    protected function buildForm()
    {
        if (null !== $currentUser = $this->getRequest()->getSession()->getCustomerUser()) {
            /** @var Customer $currentUser */
            $userName = $currentUser->getFirstname() . ' ' . $currentUser->getLastname();
            $userEmail = $currentUser->getEmail();
        } else {
            $userName = $userEmail = '';
        }

        $this->formBuilder
            ->add('email', 'email', [
                'constraints' => [
                    new NotBlank()
                ],
                'data' => $userEmail,
                'label' => $this->translator->trans('Your email address', [], SendToFriend::DOMAIN),
                'label_attr' => [
                    'for' => 'sendtofriend-email'
                ]
            ])
            ->add('name', 'text', [
                'constraints' => [
                    new NotBlank()
                ],
                'data' => $userName,
                'label' => $this->translator->trans('Your name', [], SendToFriend::DOMAIN),
                'label_attr' => [
                    'for' => 'sendtofriend-name'
                ]
            ])
            ->add('friend-email', 'email', [
                    'constraints' => [
                        new NotBlank()
                    ],
                    'label' => $this->translator->trans('E-mail address of your friend', [], SendToFriend::DOMAIN),
                    'label_attr' => [
                        'for' => 'sendtofriend-friend-email'
                    ]
                ])
            ->add('message', 'text', [
                    'constraints' => [
                        new NotBlank()
                    ],
                    'label' => $this->translator->trans('Your message', [], SendToFriend::DOMAIN),
                    'label_attr' => [
                        'for' => 'sendtofriend-message'
                    ]
                ])
            ->add("product_id", "hidden", [
                    "constraints" => [
                        new NotBlank(),
                        new GreaterThan(['value' => 0])
                    ]
                ])
        ;
    }

    public function getName()
    {
        return 'front_send_to_friend_send';
    }
}
