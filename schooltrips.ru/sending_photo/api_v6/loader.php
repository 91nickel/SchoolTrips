<?php
/**
 * AmoCRM class loader
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace Amo;

define('AMOAPP', dirname(__FILE__));
define('AMODULES', AMOAPP . '/modules');
define('AMOTEMP', AMOAPP . '/temp');

// основные компоненты класса
include(AMOAPP . '/amo.class.php');
include(AMOAPP . '/components/functions.php');
include(AMOAPP . '/components/query.php');
include(AMOAPP . '/components/response.php');
include(AMOAPP . '/components/logger.php');
include(AMOAPP . '/components/buffer.php');
Logger::setPath(AMOTEMP);

// сущности CRM
include(AMOAPP . '/entity/entity.php');
include(AMOAPP . '/entity/contacts.php');
include(AMOAPP . '/entity/company.php');
include(AMOAPP . '/entity/leads.php');
include(AMOAPP . '/entity/notes.php');
include(AMOAPP . '/entity/tasks.php');
include(AMOAPP . '/entity/entity.edithor.php');
include(AMOAPP . '/entity/entity.cf.edithor.php');
include(AMOAPP . '/entity/catalog_elements.edithor.php');
include(AMOAPP . '/entity/entity.editable.php');
include(AMOAPP . '/entity/entity.cf.editable.php');
include(AMOAPP . '/entity/customers.php');
include(AMOAPP . '/entity/customers_periods.php');
include(AMOAPP . '/entity/transactions.php');
include(AMOAPP . '/entity/catalogs.php');
include(AMOAPP . '/entity/catalog_elements.php');
include(AMOAPP . '/entity/service.php');

// модули класса
include(AMODULES . '/fn.php');
include(AMODULES . '/tempfile.php');
include(AMODULES . '/custom.php');
include(AMODULES . '/cfields.php');
include(AMODULES . '/taskinfo.php');
include(AMODULES . '/leadinfo.php');
include(AMODULES . '/userinfo.php');

include(AMODULES . '/get/get.php');
include(AMODULES . '/set/set.php');
include(AMODULES . '/update/update.php');
include(AMODULES . '/get/list.php');

include(AMODULES . '/links/links.php');
include(AMODULES . '/links/leads.php');
include(AMODULES . '/links/contacts.php');
include(AMODULES . '/links/links.set.php');
include(AMODULES . '/links/links.list.php');

include(AMODULES . '/save/save.php');
include(AMODULES . '/create/create.php');
include(AMODULES . '/delete/delete.php');

include(AMODULES . '/unsubscribe/webhooks.php');
include(AMODULES . '/remove/notes.php');
