<?php 
    include_once "includes/database.php";
    $obj = new Query();

    if(isset($_GET['type']) && $_GET['type'] == 'delete'){
        $id = $obj->get_safe_string($_GET['id']);
        $obj->deleteData('user', ['id' => $id]);
    }

    if(isset($_POST['submit'])){
        $conditions = array(
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'mobile' => $_POST['mobile']
        );

        $_POST = [];
        $obj->insertData('user', $conditions);
    }

    $result = $obj->getData('user', [], "*", "AND", "ID", "desc");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud</title>
    <link rel="stylesheet" href="style/bootstrap.css">
    <link rel="stylesheet" href="style/index.css">
    <!-- <link rel="stylesheet" href="style/all.css"> -->
</head>
<body>
    <div class="container ms-auto me-auto">
        <div class="card mt-5 d-flex flex-row justify-content-between rounded-3 px-1 py-2 align-middle bg-light">
            <div class="content-1 ms-3 text-dark fw-bold">
               <i class="fa fa-fw fa-globe"></i> Browse User
            </div>
            <button type="submit" class="content-2 me-3 btn btn-sm btn-dark rounded-3 add" >
                <i class="fa fa-fw fa-plus"></i> Add User
            </button>
        </div>
        <hr>
        <form method="post" action="index.php" class="form my-3 d-none">
            <div class="mx-auto my-3" style="width: 98%">
                <div class=" fw-normal fs-4">
                    Field with name <span class="text-danger">*</span> are Mandatory
                </div>
                <div class="my-3 d-none ">
                    <label for="Id">Id</label>
                    <input type="text" name="id" id="id" class="form-control" disabled >
                </div>        
                <div class="my-3">
                    <label for="name">Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name">
                </div>
                <div class="my-3">
                    <label for="email">Email Address<span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Email Address">
                </div>
                <div class="my-3">
                    <label for="mobile">Mobile<span class="text-danger">*</span></label>
                    <input type="number" name="mobile" id="mobile" class="form-control" placeholder="Enter Your Mobile Number">
                </div>
                <div class="my-3">
                    <input type="submit" name="submit" id="submit" value="Add User" class="btn btn-primary">
                </div>
            </div>
        </form>
        <table class="table table-responsive table-bordered mt-1 table-striped">
            <thead class="bg-primary text-light text-center">
                <tr>
                    <th>Sr#</th> <th>Name</th> <th>Email</th> <th>Mobile</th> <th>Action</th>
                </tr>
            </thead>
            <tbody >
                <?php
                    if(isset($result[0])){
                        $id = 1;
                        foreach($result as $list){ ?>
                        <tr >
                            <td class="text-center"><?=$id?></td>
                            <td><?=$list->name?></td>
                            <td><?=$list->email?></td>
                            <td><?=$list->mobile?></td>
                            <td class="text-center">
                                <a data-id="<?=$list->id ?>" class="btn btn-sm text-primary btn-edit">
                                    <i class="fa fa-fw fa-edit"></i> Edit
                                </a> | 
                                <a href="?type=delete&id=<?=$list->id?>" class="btn btn-sm text-danger btn-delete">
                                    <i class="fa fa-fw fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                <?php $id++; } } else { ?>
                    <tr>
                        <td colspan="5" style="text-align:center">Record Not Found!</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="mx-auto container response-message">
    </div>

    <div class="backdrop d-none"></div>
    <div class="footer text-center p-3 bg-light card">
        Copyright &copy; 2021
    </div>
    <script src="script/bootstrap.min.js"></script>
    <script src="script/index.js"></script>
    <script src="script/all.js"></script>
</body>
</html>