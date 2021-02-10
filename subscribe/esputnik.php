<?php

namespace ESputnikEmail;

use Brownie\ESputnik\Config;
use Brownie\ESputnik\ESputnik;
use Brownie\ESputnik\HTTPClient\CurlClient;
use Brownie\ESputnik\HTTPClient\HTTPClient;
use Brownie\ESputnik\Model\ChannelList;
use Brownie\ESputnik\Model\Contact;
use Brownie\ESputnik\Model\EmailChannel;
use Brownie\ESputnik\Model\Group;
use Brownie\ESputnik\Model\GroupList;
use Brownie\ESputnik\Model\SmsChannel;

class ESputnikEmail
{
    /**
     * @var ESputnik
     */
    private $eSputnik;

    /**
     * @var Contact
     */
    private $contact;

    /**
     * Sets incoming data.
     *
     * @param string $login Login ESputnik
     * @param string $password Password ESputnik
     */
    public function __construct($login, $password)
    {
        $this->eSputnik = new ESputnik(
            new HTTPClient(
                new CurlClient(),
                new Config([
                    'login' => $login,
                    'password' => $password,
                ])
            )
        );
    }

    /**
     * Add client to ESputnik.
     *
     * @param string $groupName Группа клиентов
     * @param string $name Имя клиента
     * @param string $lastName Фамилия клиента
     * @param string $email Почта клиента
     * @param string $phone Телефон клиента
     * @return ESputnikEmail
     */
    public function addClient($groupName, $name, $lastName, $email, $phone)
    {
        /* Добавляем почту и телефон в канал связи */
        $channelList = new ChannelList();
        $channelList->add(new EmailChannel([
            'value' => $email
        ]));
        $channelList->add(new SmsChannel([
            'value' => $phone
        ]));

        /* Добавляем в группу */
        $groupList = new GroupList();
        $groupList->add(new Group([
            'name' => $groupName
        ]));

        /* Создаем контакт */
        $this->contact = new Contact();
        $this->contact
            ->setFirstName($name)
            ->setLastName($lastName)
            ->setContactKey($email)
            ->setChannelList($channelList)
            ->setGroupList($groupList);
        return $this;
    }

    /**
     * Save data to ESputnik.
     *
     * @return Contact
     */
    public function saveContact()
    {
        return $this->eSputnik->addContact($this->contact);
    }
}