var notes = [];
var offset=0;
function getNext()
{
	$.ajax({
         url: "/api/v2/notes",
         data: {type:'lead',note_type:103,limit_rows:500,limit_offset:offset},
         type: "GET",
         beforeSend: function(xhr){xhr.setRequestHeader('if-modified-since', 'Fri, 01 Jun 2018 00:00:00');},
         success: function(data) {
			console.log(data)
			notes = notes.concat(data._embedded.items);
            if(data._embedded.items.length==500)
            {
                offset+=500;
                getNext();
            }
		}
      });
}
getNext()

var leads = [];
var offset_l=0;
function getNextLeads()
{
    $.ajax({
         url: "/api/v2/leads",
         data: {limit_rows:500,limit_offset:offset_l},
         type: "GET",
         success: function(data) {
            console.log(data)
            leads = leads.concat(data._embedded.items);
            if(data._embedded.items.length==500)
            {
                offset_l+=500;
                getNextLeads();
            }
        }
      });
}
getNextLeads()

var contacts = [];
var offset_c=0;
function getNextContacts()
{
    $.ajax({
         url: "/api/v2/contacts",
         data: {limit_rows:500,limit_offset:offset_c},
         type: "GET",
         success: function(data) {
            console.log(data)
            contacts = contacts.concat(data._embedded.items);
            if(data._embedded.items.length==500)
            {
                offset_c+=500;
                getNextContacts();
            }
        }
      });
}
getNextContacts();

/*
stop
*/

var leads_key = {};
for(var i in leads)
{
    leads_key[leads[i].id] = leads[i];
}

for(var i in contacts)
{
    for(var t in contacts[i].custom_fields)
        if(contacts[i].custom_fields[t].id==95404)
            contacts[i].phone = contacts[i].custom_fields[t].values[0].value
}

var contacts_key = {};
for(var i in contacts)
{
    contacts_key[contacts[i].id] = contacts[i];
}

for(var i in leads_key)
{
    leads_key[i].contacts.data = [];
    for(var j in leads_key[i].contacts.id)
        leads_key[i].contacts.data.push(contacts_key[leads_key[i].contacts.id[j]]);
}

for(var i in leads_key)
    for(var j in leads_key[i].contacts.data)
        for(var t in leads_key[i].contacts.data[j].custom_fields)
            if(leads_key[i].contacts.data[j].custom_fields[t].id==95404)
                leads_key[i].contacts.data[j].phone = leads_key[i].contacts.data[j].custom_fields[t].values[0].value

var idsToEdit = [];

for(var i in notes)
    if(leads_key[notes[i].element_id]!=undefined)
    {
        var p1 = notes[i].params.PHONE;
        var is = false;
        for(var t in leads_key[notes[i].element_id].contacts.data)
            if(leads_key[notes[i].element_id].contacts.data[t].phone==p1)
                is = true;
        if(!is)
            idsToEdit.push({id:notes[i].element_id,p1:notes[i].params.PHONE,p2:leads_key[notes[i].element_id].contacts.data});
    }

console.log(idsToEdit);

var id_key = {};
for(var i in idsToEdit)
    id_key[idsToEdit[i].id] = idsToEdit[i];

var ids = [];
for(var i in id_key)
    ids.push(id_key[i]);

console.log(ids);

//find real contact
var offset = 0;
function let100()
{
    var in_offset = 0;
    for(var i=offset;i<ids.length;i++)
    {
        var p = ids[i].p1;

        var is = false;

        for(var t in contacts)
            if(contacts[t].phone==p)
            {
                is = contacts[t].id;
            }

        //add contact
        if(!is)
        {
            $.post('/api/v2/contacts',{"add":[{name:'Новый контакт',custom_fields:[{id:95404,values:[{value:'+'+p,enum: "MOB"}]}], leads_id:ids[i].id}]},function(data){})
            console.log('add');
        }else{
            var leads_id = contacts_key[is].leads.id;
            leads_id.push(ids[i].id);
            console.log(leads_id);
            $.post('/api/v2/contacts',{"update":[{id:is,updated_at: (new Date()).getTime()/1000,leads_id:leads_id}]},function(data){})
            console.log('update');
        }

        console.log(leads_key[ids[i].id]);

        //remove another contact
        //if(leads_key[ids[i].id].contacts.id!=undefined)
         //   $.post('/api/v2/leads',{"update":[{id:ids[i].id, updated_at: (new Date()).getTime()/1000, unlink:{contacts_id:[leads_key[ids[i].id].contacts.id[0]]}}]},function(data){})

        if(in_offset>=100)
            break;
        offset++;
        in_offset++;
    }
}

