<?php 
session_start();

include("lib/includes.php");
include("db_connect.php");

if(empty($_SESSION['title'])) { ?>
    <script>
        document.location.href = "dashboard"; 
    </script> <?php
}
?>
<style>
    .prev-next {
        display: none;
    }
</style>

        <div class="update-document">
            <form id="updateForm" enctype="multipart/form-data">
                <input name="update_id" value="<?php echo $_SESSION['id']; ?>" hidden />
                <p id="text">Title: <input name='update_title' value="<?php echo $_SESSION['title']; ?>"></p>
                <p id="text">Author/s: <input name='update_author' value="<?php echo $_SESSION['author']; ?>"></p>
                <p id="text">Adviser: <input name='update_adviser' value="<?php echo $_SESSION['adviser']; ?>"></p>
                <p id="text">Date Published: <input name='update_date' type="date" value="<?php echo $_SESSION['date']; ?>"></p>
                <p id="text">Keywords: <input name='update_keywords' value="<?php echo $_SESSION['keywords']; ?>"></p>
                <p id="text">Executive Summary</p>
                <p id="text"><textarea name='update_abstract'><?php echo $_SESSION['abstract']; ?></textarea></p>
                <p id="text">PDF File: <input value="<?php echo $_SESSION['file']; ?>" readonly></p>
                <p id="text">Upload New File</p>
                <input type='file' class="form-control" id="file" name="file" accept=".pdf"/><br>
                <button type='submit' class="update-btn" id='exclude-btn'><i class="fa-solid fa-file-pen"></i> Update Document</button>
                <span class='responseMsg'>
                    <span class="icon"><i class="fa-solid fa-file-circle-check"></i></span> Document Updated Successfully!
                </span>
            </form>
        </div>
    </div>
</body>
