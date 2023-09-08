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
        <div class="view-document">
            <h2 id="doc-title"><?php echo $_SESSION['title']; ?></h2>
            <p id="doc-author">Author/s: <?php echo $_SESSION['author']; ?></p>
            <h4 id="doc-adviser">Adviser: <?php echo $_SESSION['adviser']; ?></h4>
            <h5 id="doc-date">Date Published: <?php echo $_SESSION['date']; ?></h5>
            <h5 id="doc-keywords">Keywords: <?php echo $_SESSION['keywords']; ?></h5>
            <h5 id="doc-keywords">Executive Summary</h5>
            <p id="doc-abstract"><?php echo $_SESSION['abstract']; ?></p>
            <?php if(!empty($_SESSION['AUTH_USER'])) { ?>
            <button class="view-btn" onclick="window.open('assets/resources/pdf/<?php echo $_SESSION['file']; ?>'); return true;" id='exclude-btn'><i class="fa-solid fa-eye"></i> View Full Document</button>
            <?php } ?>
        </div>
    </div>
</body>

