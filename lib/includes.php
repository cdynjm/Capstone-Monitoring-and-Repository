<?php 
$_SERVER['SERVER_NAME'];
$_SERVER['HTTP_HOST']; 
getenv('HTTP_HOST') 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--@include ... links & script tags-->

    <link rel="shortcut icon" type="image/png" href="assets/icons/favicon.jpg">
    <link rel="stylesheet" type="text/css" href="css/app.css">
    <link rel="stylesheet" type="text/css" href="css/print-page.css" media="print">

    <link rel="stylesheet" type="text/css" href=" https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/css/swiper.min.css">
    
    <script src="https://kit.fontawesome.com/fcdde7325c.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/b3a3a25b87.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.3.5/js/swiper.min.js"></script>

    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!--@include ... meta-tags-->

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--@include ...-->

    </script>

    <script type="text/javascript" src="js/app.js"></script>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    
</head>

<title>CMR - Dashboard</title>
<body>
    <div class="container" id="printableArea">
        <div class="header">
            <span class="label">
                Capstone Monitoring & Repository
            </span>
            <span class="logo">
                <img src="assets/icons/user-logo.png">
            </span>
            <?php if(!empty($_SESSION['AUTH_USER'])) { ?>
                <span class="text">
                    <?php echo $_SESSION['AUTH_NAME']; ?>
                </span>
            <?php } ?>
            <?php if(empty($_SESSION['AUTH_USER'])) { ?>
                <span class="text">
                    GUEST
                </span>
            <?php } ?>
        </div>
        <div class="sub-header">
            <?php if(empty($_SESSION['AUTH_USER'])) { ?>
                <span class="search-bar">
                    <input placeholder="Search..." id="user_search">
                </span>
            <?php } ?>
            <?php if(!empty($_SESSION['AUTH_USER'])) { ?>
                <span class="search-bar">
                    <input placeholder="Search..." id="admin_search">
                </span>
            <?php } ?>
            <span class="text"><i class="fa-solid fa-filter"></i> Filter Result:</span>
            <select name="filter_options" id="filter_option">
                <option value="Keywords">Keywords</option>
                <option value="Adviser">Adviser</option>
                <option value="Title">Title</option>
                <option value="Year">Year</option>
            </select>
            <?php if(!empty($_SESSION['AUTH_USER'])) { ?>
            <button id="upload-pop-up">
                <span class="icon"><i class="fa-solid fa-cloud-arrow-up"></i></span>
                <span>Upload File</span>
            </button>
            <?php } ?>
            <button id="" onclick="printPageArea('areaID')">
                <span class="icon"><i class="fa-solid fa-print"></i></span>
                <span>Print</span>
            </button>
          <!--  <input type=button onclick="window.open('assets/resources/pdf/Capstone-Project-2-FINAL.pdf'); return true;" value="Open"> -->
        </div>
        <div class="sidebar">
            <div class="logo">
                <img src="assets/icons/ccsit-logo.jpg">
            </div>
            <div class="label">
                <label>CCSIT</label>
            </div>
            <div class="sidebar-buttons">
                <li>
                    <span class="icon"><i class="fa-solid fa-computer"></i></span>
                    <span class="text"><a href="dashboard">Dashboard</a></span>
                </li>
                <?php if(empty($_SESSION['AUTH_USER'])) { ?>
                    <li>
                        <span class="icon"><i class="fa-solid fa-right-to-bracket"></i></span>
                        <span class="text"><button class="login-box">Log In</button></span>
                    </li>
                <?php } ?>
                <?php if(!empty($_SESSION['AUTH_USER'])) { ?>
                    <li>
                        <span class="icon"><i class="fa-flip-horizontal fa-solid fa-right-to-bracket"></i></span>
                        <span class="text"><button class="log-out">Log Out</button></span>
                    </li>
                <?php } ?>
            </div>
        </div>

        <div class="upload-pop-up">
            <!-- Status message -->
            <div class="statusMsg"></div>
            <form id="fupForm" enctype="multipart/form-data">
                <h3>Upload New File</h3>
                <label>Title</label><br>
                <input placeholder="Title" name='title' required><br>
                <label>Author</label><br>
                <input placeholder="Author" name='author' required><br>
                <label>Adviser</label><br>
                <input placeholder="Adviser" name='adviser' required><br>
                <label>Keywords</label><br>
                <input placeholder="Keywords" name='keywords' required><br>
                <label>Abstract</label><br>
                <textarea placeholder="Absract" name='abstract' required></textarea><br>
                <label>Date of Submission</label><br>
                <input type="date" id='date' name='date' required><br>
                <label>Upload PDF File</label><br>
                <input type="file" class="form-control" id="file" name="file" accept=".pdf, .doc, .docx" required/><br>

                <button type="submit" class="submitBtn">Upload</button>
                <button class="cancel-btn" id="close-upload-pop-up">Cancel</button>
            </form>
        </div>

        <div class="log-in" id='log-in'>
            <section id="content">
                <form id='logAUTH'>
                    <h1>Administrator</h1>
                    <div>
                        <input type="text" placeholder="Username" required="" id="username" name="username" />
                    </div>
                    <div>
                        <input type="password" placeholder="Password" required="" id="password" name="password"/>
                    </div>
                    <div class="responseError"></div>
                    <div>
                        <button type="submit"><span class="icon">Log In</button>
                    </div>
                </form>
            </section>
        </div>

        <div class="footer" id="exclude-footer">
            <!--
                <div class="prev-next">
                    <span class="label">Page</span>
                    <button><i class="fa-solid fa-chevron-left"></i></button>
                    <span class="text">1</span>
                    <button><i class="fa-solid fa-chevron-right"></i></button>
                </div> 
             -->
            <p>College of Computer Studies and Information Technology | Developed By: Jemuel Cadayona © 2023</p>
        </div>

        <div id='areaID'>