<?php 

global $EMBED_SEARCH_RESULT;
global $RESPONSE_ERROR;
 
// If form is submitted 
if(isset($_POST['author']) || isset($_POST['title']) || isset($_POST['date']) || isset($_POST['adviser']) || isset($_POST['keywords']) || isset($_POST['abstract']) || isset($_POST['file'])){ 
    
    // File upload folder 
    $uploadDir = 'assets/resources/pdf/'; 
    
    // Allowed file types 
    $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
    
    // Default response 
    $response = array( 
        'status' => 0, 
        'message' => 'Form submission failed, please try again.' 
    ); 
    
    // Get the submitted form data 
    $author = $_POST['author']; 
    $title = $_POST['title']; 
    $date_submission = $_POST['date'];
    $adviser = $_POST['adviser'];
    $keywords = $_POST['keywords'];
    $abstract = $_POST['abstract'];
    
    // Check whether submitted data is not empty 
    if(!empty($title) && !empty($author)){ 
        // Validate email 
        
            $uploadStatus = 1; 
             
            // Upload file 
            $uploadedFile = ''; 
            if(!empty($_FILES["file"]["name"])){ 
                // File path config 
                $fileName = basename($_FILES["file"]["name"]); 
                $targetFilePath = $uploadDir . $fileName; 
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                 
                // Allow certain file formats to upload 
                if(in_array($fileType, $allowTypes)){ 
                    // Upload file to the server 
                    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
                        $uploadedFile = $fileName; 
                    }else{ 
                        $uploadStatus = 0; 
                        $response['message'] = 'Sorry, there was an error uploading your file.'; 
                    } 
                }else{ 
                    $uploadStatus = 0; 
                    $response['message'] = 'Sorry, only '.implode('/', $allowTypes).' files are allowed to upload.'; 
                } 
            } 
             
            if($uploadStatus == 1){ 
                // Include the database config file 
                include_once 'db_connect.php'; 
                // Insert form data in the database 
                session_start();
                $admin_id = $_SESSION['AUTH_ID'];
                $sqlQ = "INSERT INTO files (admin_id, author, title, date_submission, adviser, abstract, keywords, file_name) VALUES (?,?,?,?,?,?,?,?)"; 
                $stmt = $db->prepare($sqlQ); 
                $stmt->bind_param("ssssssss", $admin_id, $author, $title, $date_submission, $adviser, $abstract, $keywords, $uploadedFile); 
                $insert = $stmt->execute(); 
                 
                if($insert){ 
                    $response['status'] = 1; 
                    $response['message'] = 'File uploaded successfully!'; 
                } 
            } 
        
    }else{ 
         $response['message'] = 'Please fill all the mandatory fields (name and email).'; 
    } 

    // Return response 
    echo json_encode($response);
} 

if(isset($_POST['submit_authID'])) {

    include_once 'db_connect.php'; 

    $authID = $_POST['authID'];

    $SQL_AUTH = "SELECT * FROM files WHERE id=$authID";
    $SQL_AUTH_QUERY = mysqli_query($db, $SQL_AUTH);

    if(mysqli_num_rows($SQL_AUTH_QUERY) > 0) {
        while($ROWS_AUTH_QUERY = mysqli_fetch_assoc($SQL_AUTH_QUERY)) {
            session_start();

            $_SESSION['title'] = $ROWS_AUTH_QUERY['title'];
            $_SESSION['author'] = $ROWS_AUTH_QUERY['author'];
            $_SESSION['adviser'] = $ROWS_AUTH_QUERY['adviser'];
            $_SESSION['date'] = $ROWS_AUTH_QUERY['date_submission'];
            $_SESSION['abstract'] = $ROWS_AUTH_QUERY['abstract'];
            $_SESSION['keywords'] = $ROWS_AUTH_QUERY['keywords'];
            $_SESSION['file'] = $ROWS_AUTH_QUERY['file_name'];

            header('Location: view-document');
        }
    }
}

if(isset($_POST['submit_updateID'])) {

    include_once 'db_connect.php'; 

    $updateID = $_POST['updateID'];

    $SQL_AUTH = "SELECT * FROM files WHERE id=$updateID";
    $SQL_AUTH_QUERY = mysqli_query($db, $SQL_AUTH);

    if(mysqli_num_rows($SQL_AUTH_QUERY) > 0) {
        while($ROWS_AUTH_QUERY = mysqli_fetch_assoc($SQL_AUTH_QUERY)) {
            session_start();

            $_SESSION['id'] = $ROWS_AUTH_QUERY['id'];
            $_SESSION['title'] = $ROWS_AUTH_QUERY['title'];
            $_SESSION['author'] = $ROWS_AUTH_QUERY['author'];
            $_SESSION['adviser'] = $ROWS_AUTH_QUERY['adviser'];
            $_SESSION['date'] = $ROWS_AUTH_QUERY['date_submission'];
            $_SESSION['abstract'] = $ROWS_AUTH_QUERY['abstract'];
            $_SESSION['keywords'] = $ROWS_AUTH_QUERY['keywords'];
            $_SESSION['file'] = $ROWS_AUTH_QUERY['file_name'];

            header('Location: update-document');
        }
    }
}

if(isset($_POST['user_search'])) {

    include_once 'db_connect.php'; 
    $cnt = 1;
    $user_search = $_POST['user_search'];
    $filter_option = '';

    $array_options = array(
        "Keywords",
        "Adviser",
        "Title",
        "Year"
    );

    $array_column_value = array(
        "keywords",
        "adviser",
        "title",
        "date_submission"
    );

    for ($loop_options = 0; $loop_options <= 3; $loop_options++) {
        switch($_POST['filter_option']) {
            case $array_options[$loop_options]:
                $SEARCH = "SELECT * FROM files WHERE $array_column_value[$loop_options] LIKE '%$user_search%' ORDER BY created_at DESC";
            break;
        }
    }

    $EMBED_SEARCH_RESULT .= "
        <span class='recent-uploads' id='exclude-header'><i class='fa-solid fa-magnifying-glass'></i> Search Results</span>
        <table>
            <tr>
                <th width='5%'>No.</th>
                <th width='20%'>Title</th>
                <th width='15%'>Author/s</th>
                <th width='15%'>Date of Submission</th>
                <th>Adviser</th>
                <th>Keywords</th>
                <th width='20%' id='exclude-header'>Action</th>
            </tr>
    ";

    $SQL_AUTH_SEARCH = $SEARCH;
    $SQL_AUTH_SEARCH_QUERY = mysqli_query($db, $SQL_AUTH_SEARCH);

    session_start();
    
    if(mysqli_num_rows($SQL_AUTH_SEARCH_QUERY) > 0) {
        while($ROWS_AUTH_SEARCH_QUERY = mysqli_fetch_assoc($SQL_AUTH_SEARCH_QUERY)) {
            $date = $ROWS_AUTH_SEARCH_QUERY['date_submission'];
            $EMBED_SEARCH_RESULT .= "
            <tr>
            <td><center>".$cnt."</center></td>
            <td>".$ROWS_AUTH_SEARCH_QUERY['title']."</td>
            <td>".$ROWS_AUTH_SEARCH_QUERY['author']."</td>
            <td><center>".date('F, Y', strtotime($date))."</center></td>
            <td><center>".$ROWS_AUTH_SEARCH_QUERY['adviser']."</center></td>
            <td>".$ROWS_AUTH_SEARCH_QUERY['keywords']."</td>
            <td id='exclude-cell'><center>
                <form method='POST' action='server.php'>
                    <span>
                        <input name='authID' value=".$ROWS_AUTH_SEARCH_QUERY['id']." hidden>
                        <button type='submit' name='submit_authID'><i class='fa-solid fa-eye'></i> View</button>
                    </span>";
            if(!empty($_SESSION['AUTH_USER'])) {
            $EMBED_SEARCH_RESULT .= "<span>
                        <input name='updateID' value=".$ROWS_AUTH_SEARCH_QUERY['id']." hidden>
                        <button type='submit' name='submit_updateID'><i class='fa-solid fa-pen-to-square'></i> Edit</button>
                    </span>
                    <span>
                        <input name='deleteID' value=".$ROWS_AUTH_SEARCH_QUERY['id']." hidden>
                        <button style='background: darkRed;' type='submit' name='submit_deleteID'><i class='fa-solid fa-trash'></i> Delete</button>
                    </span>
                </form>
                </center>
            </td>
        </tr>
            ";
            }
            $cnt += 1;
        }
    }
    else {
        $EMBED_SEARCH_RESULT .= "<td colspan='7'><center>No Results Found!</center></td>";
    }

    echo $EMBED_SEARCH_RESULT;
}

if(isset($_POST['admin_search'])) {

    include_once 'db_connect.php'; 
    session_start();
    $admin_id = $_SESSION['AUTH_ID'];
    $cnt = 1;
    $admin_search = $_POST['admin_search'];
    $filter_option = '';

    $array_options = array(
        "Keywords",
        "Adviser",
        "Title",
        "Year"
    );

    $array_column_value = array(
        "keywords",
        "adviser",
        "title",
        "date_submission"
    );

    for ($loop_options = 0; $loop_options <= 3; $loop_options++) {
        switch($_POST['filter_option']) {
            case $array_options[$loop_options]:
                $SEARCH = "SELECT * FROM files WHERE admin_id = '$admin_id' AND $array_column_value[$loop_options] LIKE '%$admin_search%' ORDER BY created_at DESC";
            break;
        }
    }

    $EMBED_SEARCH_RESULT .= "
        <span class='recent-uploads' id='exclude-header'><i class='fa-solid fa-magnifying-glass'></i> Search Results</span>
        <table>
            <tr>
                <th width='5%'>No.</th>
                <th width='20%'>Title</th>
                <th width='15%'>Author/s</th>
                <th width='15%'>Date of Submission</th>
                <th>Adviser</th>
                <th>Keywords</th>
                <th width='20%' id='exclude-header'>Action</th>
            </tr>
    ";

    $SQL_AUTH_SEARCH = $SEARCH;
    $SQL_AUTH_SEARCH_QUERY = mysqli_query($db, $SQL_AUTH_SEARCH);
    
    if(mysqli_num_rows($SQL_AUTH_SEARCH_QUERY) > 0) {
        while($ROWS_AUTH_SEARCH_QUERY = mysqli_fetch_assoc($SQL_AUTH_SEARCH_QUERY)) {
            $date = $ROWS_AUTH_SEARCH_QUERY['date_submission'];
            $EMBED_SEARCH_RESULT .= "
            <tr>
            <td><center>".$cnt."</center></td>
            <td>".$ROWS_AUTH_SEARCH_QUERY['title']."</td>
            <td>".$ROWS_AUTH_SEARCH_QUERY['author']."</td>
            <td><center>".date('F, Y', strtotime($date))."</center></td>
            <td><center>".$ROWS_AUTH_SEARCH_QUERY['adviser']."</center></td>
            <td>".$ROWS_AUTH_SEARCH_QUERY['keywords']."</td>
            <td id='exclude-cell'><center>
                <form method='POST' action='server.php'>
                    <span>
                        <input name='authID' value=".$ROWS_AUTH_SEARCH_QUERY['id']." hidden>
                        <button type='submit' name='submit_authID'><i class='fa-solid fa-eye'></i></button>
                    </span>";
            if(!empty($_SESSION['AUTH_USER'])) {
            $EMBED_SEARCH_RESULT .= "<span>
                        <input name='updateID' value=".$ROWS_AUTH_SEARCH_QUERY['id']." hidden>
                        <button style='background: orange;' type='submit' name='submit_updateID'><i class='fa-solid fa-pen-to-square'></i></button>
                    </span>
                    <span>
                        <input name='deleteID' value=".$ROWS_AUTH_SEARCH_QUERY['id']." hidden>
                        <button style='background: darkRed;' type='submit' name='submit_deleteID'><i class='fa-solid fa-trash'></i></button>
                    </span>
                </form>
                </center>
            </td>
        </tr>
            ";
            }
            $cnt += 1;
        }
    }
    else {
        $EMBED_SEARCH_RESULT .= "<td colspan='7'><center>No Results Found!</center></td>";
    }

    echo $EMBED_SEARCH_RESULT;
}

if(isset($_POST['username'])) {

    include_once 'db_connect.php'; 
    $username = $_POST['username'];
    $password = $_POST['password'];

    $SQL_AUTH_LOGIN = "SELECT * FROM account WHERE username='$username' AND password='$password'";
    $SQL_AUTH_LOGIN_QUERY = mysqli_query($db, $SQL_AUTH_LOGIN);

    if(mysqli_num_rows($SQL_AUTH_LOGIN_QUERY) > 0) {
        while($ROWS_AUTH_LOGIN_QUERY = mysqli_fetch_assoc($SQL_AUTH_LOGIN_QUERY)) { 
            session_start();
            $_SESSION['AUTH_NAME'] = $ROWS_AUTH_LOGIN_QUERY['name'];
            $_SESSION['AUTH_USER'] = $ROWS_AUTH_LOGIN_QUERY['type'];
            $_SESSION['AUTH_ID'] = $ROWS_AUTH_LOGIN_QUERY['id'];
            ?>
            <script>
                document.location.href = "dashboard"; 
            </script> <?php
        }
    }
    else {
        $RESPONSE_ERROR .= "<i class='fa-solid fa-triangle-exclamation'></i> Invalid Account";
        echo $RESPONSE_ERROR;
    }

}
if(isset($_POST['update_title'])) {
    // File upload folder 
    $uploadDir = 'assets/resources/pdf/'; 
    
    // Allowed file types 
    $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
    
    // Default response 
    $response = array( 
        'status' => 0, 
        'message' => 'Form submission failed, please try again.' 
    );

    // Get the submitted form data 
    $update_id = $_POST['update_id'];
    $update_author = $_POST['update_author']; 
    $update_title = $_POST['update_title']; 
    $update_date = $_POST['update_date'];
    $update_adviser = $_POST['update_adviser'];
    $update_keywords = $_POST['update_keywords'];
    $update_abstract = $_POST['update_abstract'];
    
    // Check whether submitted data is not empty 
    if(!empty($update_title) && !empty($update_author)){ 
        // Validate email 
        
            $uploadStatus = 1; 
             
            // Upload file 
            $uploadedFile = ''; 
            if(!empty($_FILES["file"]["name"])){ 
                // File path config 
                $fileName = basename($_FILES["file"]["name"]); 
                $targetFilePath = $uploadDir . $fileName; 
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                 
                // Allow certain file formats to upload 
                if(in_array($fileType, $allowTypes)){ 
                    // Upload file to the server 
                    if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
                        $uploadedFile = $fileName; 
                    }else{ 
                        $uploadStatus = 0; 
                        $response['message'] = 'Sorry, there was an error uploading your file.'; 
                    } 
                }else{ 
                    $uploadStatus = 0; 
                    $response['message'] = 'Sorry, only '.implode('/', $allowTypes).' files are allowed to upload.'; 
                } 
             
             
            if($uploadStatus == 1){ 
                // Include the database config file 
                include_once 'db_connect.php'; 
                // Insert form data in the database 
                $SQL_UPDATE = "UPDATE files SET title='$update_title', author='$update_author', date_submission='$update_date', adviser='$update_adviser', abstract='$update_abstract', keywords='$update_keywords', file_name='$uploadedFile' WHERE id='$update_id'";
                mysqli_query($db, $SQL_UPDATE);
                 
                if($insert){ 
                    $response['status'] = 1; 
                    $response['message'] = 'File uploaded successfully!'; 
                } 
            }
        }
        if(empty($_FILES["file"]["name"])) {
            // Include the database config file 
            include_once 'db_connect.php'; 
            // Insert form data in the database 
            $SQL_UPDATE = "UPDATE files SET title='$update_title', author='$update_author', date_submission='$update_date', adviser='$update_adviser', abstract='$update_abstract', keywords='$update_keywords' WHERE id='$update_id'";
            mysqli_query($db, $SQL_UPDATE);
        }
        
    }else{ 
         $response['message'] = 'Please fill all the mandatory fields (name and email).'; 
    } 

    // Return response 
    echo json_encode($response);
}

if(isset($_POST['submit_deleteID'])) {

    include_once 'db_connect.php'; 

    $delete_id = $_POST['deleteID'];

    mysqli_query($db, "DELETE FROM files WHERE id='$delete_id'");

    header('Location: dashboard');

}

if(isset($_POST['destroy_session'])) { session_start(); session_unset(); session_destroy(); }