<?php

$current_view = $config['VIEW_PATH'] . 'contacts' . DS;
$file = $config['DATA_PATH'] . 'contacts.txt';
$dir = $config['DATA_PATH'] . 'uploads'. DS;

switch (get('action')) {
    case 'view':{
        $view = $current_view . 'view.phtml';
        $contacts = file($file);
        break;
    }

    case 'update':{
        $view = $current_view . 'update.phtml';
        $contacts = file($file);
        $id = get('id');
        $contact_to_update = '';
        foreach($contacts as $index => $contact) {
            $fields = explode(',', $contact);
            if($fields[0] == $id){
                $contact_to_update = $fields;
                break;
            }
        }
        break;
    }

    case 'doUpdate':{
        $id = get('id');
        $contacts = file($file);
        $image = picUpload($dir, $id);
        $comment = str_replace(PHP_EOL, " ", get('comment'));
        $updated_contact = $id . ',' . get('title') . ',' . get('firstname'). ',' . get('lastname'). ','
            .get('email'). ',' . get('website'). ',' . get('cellnumber'). ',' . get('officenumber')
             .',' . get('homenumber'). ',' . get('twitter'). ',' . get('facebook'). ',' . $image . ',' . $comment . PHP_EOL;

        foreach($contacts as $index => $contact) {
            $fields = explode(',', $contact);
            if($fields[0] == $id){
                $contacts[$index] = $updated_contact;
                break;
            }
        }
        file_put_contents($file, implode('', $contacts));
        header('location: /comp1230/contacts/public/?page=contacts&action=view');
        break;
    }

    case 'delete':{
        $view = $current_view . 'delete.phtml';
        $id = get('id');
        $contacts = file($file);
        foreach($contacts as $index => $contact) {
            $fields = explode(',', $contact);
            if($fields[0] == $id){
                unset($contacts[$index]);

                deleteImg($dir . $id . '.jpeg');
                break;
            }
        }
        file_put_contents($file, implode('', $contacts));
        @header('location: /comp1230/contacts/public/?page=contacts&action=view');
        break;
        }


    case 'add':{
        $view = $current_view . 'add.phtml';
        break;
    }

    case 'doAdd':{
        $id = getID();
        $image = picUpload($dir, $id);
        $contacts = file($file);
        $comment = str_replace(PHP_EOL, " ", get('comment'));
        $new_contact = $id . ',' . get('title') . ',' . get('firstname'). ',' . get('lastname'). ','
            .get('email'). ',' . get('website'). ',' . get('cellnumber'). ',' . get('officenumber')
            .',' . get('homenumber'). ',' . get('twitter'). ',' . get('facebook'). ','. $image . ',' . $comment . PHP_EOL;

        file_put_contents($file, $new_contact, FILE_APPEND);
        header('location: /comp1230/contacts/public/?page=contacts&action=view');
        break;
    }
}