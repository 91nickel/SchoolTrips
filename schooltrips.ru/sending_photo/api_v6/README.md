
```
#!php

include(dirname(__FILE__) . '/class/amoapi/loader.php');

$dlatestov = array(
    'domain' => 'dlatestov',
    'login' => 'mery131@yandex.ru',
    'hash' => 'ee8d12668c60e1a6bbda4130352093c1',
);
$amo = Amo\api($dlatestov);
$amo->logs(1); // вкл выкл логов
$amo->accountCacheTime(3600); // время жизни кеша данных по аккаунту в сек. (0 - выкл) по умолч. 60 сек

// создание сделки
$service = $amo->leads->set();
$service->name('Новая сделка')
		->price(1000)
		->respId(12345) // ID ответственного
		->custom('Город', 'Чебоксары');
$createdLeadId = $service->run();

// создание компании с привязкой к сделке
$service = $amo->company->set();
$service->name('Новая компания')
		->leads($createdLeadId)
		->respId(12345) // ID ответственного
		->custom('Город', 'Чебоксары');
$createdCompanyId = $service->run();

// создание контакта с привязкой к сделке и компании
$service = $amo->contacts->set();
$service->name('Новый контакт')
		->leads($createdLeadId)
		->company($createdCompanyId)
		->respId(12345) // ID ответственного
		->custom('Город', 'Чебоксары');
$createdContactId = $service->run();

// создание задачи с привязкой к контакту
$service = $amo->tasks->set();
$service->type('Тип задачи')
		->text('Текст задачи')
		->elemType('contact')
		->elemId($createdContactId)
		->respId(12345) // ID ответственного
		->setDate(date('Y-m-d 23:59:59'));
$createdTaskId = $service->run();

// получение текстовых примечаний по сделкам
$service = $amo->notes->get();
$service->setMax(2000)->setParam('type', 'leads')->setParam('note_type', 4);
$service->sort(['id' => 'DESC']);

$entitys = $service->run();
foreach ($entitys as $entity) {
	echo $entity->id().' - '.$entity->createdDate().' - '.$entity->modifiedDate()."\r\n<br>";
}

// получение сделки
$service = $amo->leads->get()->limit(1);
$leads = $service->run();

// обновление сделки по id
$service = $amo->leads->update();
$service->set(13954562) // id сделки
		->name('lead upd')
		->price(100600)
		->custom('Выбранный материал', 'Массив')
		->tags('Сделка,Lead');
print_r( $service->bind() );
if (!$updatedLead = $service->run()) {
	print_r( $service->getError() );
}

// получение контакта по ID
$contact = $amo->contacts->get()->byId(12345);

// обновление контакта
$service = $amo->contacts->update();
$service->set($contact)
		->name('Василий');
		
print_r( $service->bind() );
if (!$updatedContact = $service->run()) {
	print_r( $service->getError() );
}

// получение покупателя
$service = $amo->customers->get();
$customer = $service->fromId(19757);

// получение покупок
$customer->transactions();

// добавление покупки
$service = $customer->addTransaction();
$service->price(100200)
		->comment('Test addd')
		->nextPrice(300400)
		->nextDate(time()+86400);
$service->run();

// получение покупателей контакта
$contact = $amo->contacts->get()->byId(32425923);
$contact->customerLinks();
$contact->customers();

```