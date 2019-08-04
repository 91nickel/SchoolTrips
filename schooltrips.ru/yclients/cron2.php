<?
include_once '../date/amocrm_lite.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];

auth_amoCRM($amo);
$offset = 0;
$leads = [];

while(1)
{
    $p = m($amo, '/api/v2/leads?status[]=17475193&limit_rows=500&limit_offset='.$offset);
    $offset+=500;

    if(isset($p['_embedded']['items']))
        $leads = array_merge($leads,$p['_embedded']['items']);
    else
        break;
}

echo count($leads);
echo "<br>";

$fori=0;

foreach ($leads as $key => $value) {
    $t = false;
    if(empty($value['catalog_elements']))
        continue;
    foreach ($value['custom_fields'] as $key2 => $value2) {
        if($value2['id']=='574181')
            $t = true;
    }
    if(!$t)
    {
        $fori++;
        if($fori<10)
        {
            echo 'get '.$value['id']."<br>";
            file_get_contents('http://nova-agency.ru/auto/schooltrip/yclients/cron.php?link='.$value['id']);
        }
    }
}
echo "<br>".$fori."<br>";

$offset = 0;
$leads = [];

while(1)
{
    $p = m($amo, '/api/v2/leads?status[]=21120444&limit_rows=500&limit_offset='.$offset);
    $offset+=500;

    if(isset($p['_embedded']['items']))
        $leads = array_merge($leads,$p['_embedded']['items']);
    else
        break;
}

echo count($leads);
echo "<br>";

$fori=0;

foreach ($leads as $key => $value) {
    $t = false;
    if(empty($value['catalog_elements']))
        continue;
    foreach ($value['custom_fields'] as $key2 => $value2) {
        if($value2['id']=='578295')
            $t = true;
    }
    if(!$t)
    {
        $fori++;
        if($fori<10)
        {
            echo 'get '.$value['id']."<br>";
            file_get_contents('http://nova-agency.ru/auto/schooltrip/yclients/cron.php?link='.$value['id']);
        }
    }
}
echo "<br>".$fori."<br>";
?>