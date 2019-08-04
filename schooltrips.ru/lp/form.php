<?

file_put_contents('form.log', file_get_contents('php://input').chr(10).chr(10), FILE_APPEND);

$data = json_decode(file_get_contents('php://input'),1);
$data_in = $data;
$phone = $data['lead_data']['phone'];
$email = $data['lead_data']['email'];

header('Access-Control-Allow-Origin: *');
require_once "../excursions/amocrm_functions.php";
$user = file_get_contents('http://nova-agency.ru/api/config/?meth=get&subdomain=schooltrip5');
$user = json_decode($user,1);

file_put_contents('new.log', chr(10).chr(10).json_encode($_GET), FILE_APPEND);

//$user['main_user']['email'];
//$user['main_user']['subdomain'];
//$user['main_user']['key'];

$amo['amocrm_account']=$user['main_user']['email'];
$amo['amocrm_domain']=$user['main_user']['subdomain'];
$amo['amocrm_hash']=$user['main_user']['key'];
auth_amoCRM($amo);

//new
$data = [];
$data['add'] = [];
$data['add'][0] = [];
$data['add'][0]['name'] = 'Новый контакт';
$data['add'][0]['custom_fields'] = [["id"=>95404,"values"=>[["value"=>$phone,"enum"=>"WORK"]]],["id"=>95406,"values"=>[["value"=>$email,"enum"=>"WORK"]]]];

$con = create_contact_amoCRM($amo,$data);

print_r($con);

$con = $con['_embedded']['items'][0]['id'];

$data = [];
$data['add'] = [];
$data['add'][0] = [];
$data['add'][0]['name'] = 'Запись с карамельного сайта';
$data['add'][0]['contacts_id'] = $con;
$data['add'][0]['status_id'] = 15562204;
$data['add'][0]['custom_fields'] = [['id'=>594682,'values'=>[['value'=>$data['lead_data']['roistat']]]]];
$data['add'][0]['tags'] = 'карамель, с сайта';

$lead = create_lead_amoCRM($amo,$data);

$lead = $lead['_embedded']['items'][0]['id'];

file_get_contents('http://nova-agency.ru/auto/schooltrip/messages/addToMess.php?id='.$lead);

$note = '';

foreach ($data_in['meta_data'] as $key => $value) {
	$note.=$key.' - '.$value.chr(10);
}

foreach ($data_in['utm_data'] as $key => $value) {
	$note.=$key.' - '.$value.chr(10);
}

echo json_encode(array(
        'id'=>$lead,
        'element_type'=>2,
        'note_type'=>4,
        'text'=>$note
));

add_note_amoCRM($amo,array(
        'id'=>$lead,
        'element_type'=>2,
        'note_type'=>4,
        'text'=>$note
));
?>