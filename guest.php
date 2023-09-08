<?php 
session_start();

include("lib/includes.php");
include("db_connect.php");

$COUNT_ARTICLES = 0;

$SQL_COUNT = "SELECT * FROM files";
$SQL_COUNT_QUERY = mysqli_query($db, $SQL_COUNT);

if(mysqli_num_rows($SQL_COUNT_QUERY) > 0) {
    while($ROWS = mysqli_fetch_assoc($SQL_COUNT_QUERY)) {
        $COUNT_ARTICLES += 1;
    }
}

?>
        <div class="body">
            <div class="header-label">
                <span class="logo"><img src='assets/icons/ccsit-logo.jpg'></span>
                <span class="text">College of Computer Studies and Information Technology</span>
            </div>
            <span class="recent-uploads" id='exclude-header'><i class="fa-solid fa-newspaper"></i> Recent Uploads</span>
            <span class="total-articles" id='exclude-header'><i class="fa-solid fa-box-open"></i> Total Articles: <?php echo $COUNT_ARTICLES; ?></span>
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
                <?php 
                
                $sql = "SELECT * FROM files ORDER BY created_at DESC";
                $query = mysqli_query($db, $sql);
                $cnt = 1;
                if(mysqli_num_rows(($query)) > 0) {
                    while($rows = mysqli_fetch_assoc($query)) {
                        $date = $rows['date_submission'];
                        ?> 
                                <tr>
                                    <td><center><?php echo $cnt; ?></center></td>
                                    <td><?php echo $rows['title']; ?></td>
                                    <td><?php echo $rows['author']; ?></td>
                                    <td><center><?php echo  date('F, Y', strtotime($date)); ?></center></td>
                                    <td><center><?php echo $rows['adviser']; ?></center></td>
                                    <td><?php echo $rows['keywords']; ?></td>
                                    <td id='exclude-cell'><center>
                                        <form method="POST" action="server.php">
                                            <span>
                                                <input name='authID' value=<?php echo $rows['id']; ?> hidden>
                                                <button type="submit" name="submit_authID"><i class="fa-solid fa-eye"></i> View</button>
                                            </span>
                                        </form>
                                        </center>
                                    </td>
                                </tr>
                        <?php
                        $cnt += 1;
                    }
                }
                
                ?>
            </table>
        </div>
    </div>
        
    </div>
</body>