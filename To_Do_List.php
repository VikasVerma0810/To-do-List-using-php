<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "crudapp";

$insert = false;
$update = false;
$delete = false;

$connection = mysqli_connect($server, $username, $password, $database);

if (!$connection) {
    die("Could not connect to database" . mysqli_connect_errno());
}

if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $sqlQuery = "DELETE from notes where notes.sno = $sno;";
    $result = mysqli_query($connection, $sqlQuery);
    if ($result) {
        $delete = true;
    } else {
        echo "Deletion failed " . mysqli_error($connection);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['snoEdit'])) {
        $title = $_POST['titleEdit'];
        $desc = $_POST['descEdit'];
        $sno = $_POST['snoEdit'];
        $sqlQuery = "UPDATE `notes` SET `description` = '$desc' , `title` = '$title' WHERE `notes`.`sno` = '$sno'";
        $result = mysqli_query($connection, $sqlQuery);
        if ($result) {
            $update = true;
        } else {
            echo "insertion failed " . mysqli_error($connection);
        }
    } else {
        $title = $_POST['title'];
        $desc = $_POST['desc'];

        $sqlQuery = "insert into notes (title,description) values('$title','$desc');";
        $result = mysqli_query($connection, $sqlQuery);
        if ($result) {
            $insert = true;
        } else {
            echo "insertion failed " . mysqli_error($connection);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>


    <title>PHP CRUD</title>
    <style>
        #heading{
            font-size: 50px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Edit modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal">
       Edit Modal
    </button> -->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/PHP/To_Do_List.php" method="POST">
                    <div class="modal-body">

                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="form-group">
                            <label for="title">Note Title</label>
                            <input type="text" name="titleEdit" class="form-control" id="titleEdit" aria-describedby="emailHelp">
                        </div>

                        <div class="form-group">
                            <label for="desc">Note Description</label>
                            <textarea class="form-control" name="descEdit" id="descEdit" rows="3"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php
    if ($insert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your note have been inserted successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
    ?>
    <?php
    if ($update) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your note have been Updated successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
    ?>
    <?php
    if ($delete) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your note have been Deleted successfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
    ?>
    <div id="heading" class="my-3">
        To Do List Using PHP Crud Operation
    </div>


    <div class="container my-3">
        <h2>Add a Note</h2>
        <form action="/PHP/To_Do_List.php" method="POST">
            <div class="form-group">
                <label for="title">Note Title</label>
                <input type="text" name="title" class="form-control" id="title" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
                <label for="desc">Note Description</label>
                <textarea class="form-control" name="desc" id="desc" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Note</button>
        </form>
    </div>

    <div class="container my-4">

        <table class="table " id="myTable">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlQuery = "SELECT * FROM notes";
                $result = mysqli_query($connection, $sqlQuery);

                $numRow = mysqli_num_rows($result);
                //   echo $numRow;
                $sno = 0;
                echo "<br>";

                if ($numRow > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $sno = $sno + 1;
                        echo "  <tr>
                        <th scope='row'>" . $sno . "</th>
                        <td>" . $row['title'] . "</td>
                        <td>" . $row['description'] . "</td>
                        <td>    <button class='btn btn-sm btn-primary edit' id=" . $row['sno'] . ">Edit</button> <button class='btn btn-sm btn-danger delete' id=d" . $row['sno'] . ">Delete</button> </td>
                    </tr>";
                    }
                }

                ?>


            </tbody>
        </table>
    </div>
    <hr>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach(element => {
            element.addEventListener('click', (e) => {
                // console.log('edit');
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName('td')[0].innerText;
                desc = tr.getElementsByTagName('td')[1].innerText;
                console.log(title, desc);
                titleEdit.value = title;
                descEdit.value = desc;
                snoEdit.value = e.target.id;
                console.log(e.target.id);
                $('#editModal').modal('toggle');
            })
        });


        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach(element => {
            element.addEventListener('click', (e) => {
                // console.log('edit');
                sno = e.target.id.substr(1, );
                snoDelete = e.target.id;

                if (confirm("Are you sure to delete this note?")) {
                    // console.log('yes');
                    window.location = `/PHP/To_Do_List.php?delete=${sno}`;
                } else {
                    console.log('no');
                }
            })
        });
    </script>
</body>

</html>