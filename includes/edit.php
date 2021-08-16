<?php
    include_once 'database.php';
    $obj = new Query();

    if(isset($_POST['edit-id'])){
        $result = $obj->getdata('user', ['id' => $_POST['edit-id']]);
        echo json_encode($result[0]);
    }

    if(isset($_POST['id'])){
        $conditions = array(
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'mobile' => $_POST['mobile'],
        );
        $obj->updateData('user', $conditions, 'id', $_POST['id']);
        echo "Updated";
    }
?>