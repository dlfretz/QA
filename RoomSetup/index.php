<?php include '../view/header.php';?>
<?php include '../model/question_db.php'?>
<!--This is where successful login attempts are sent, This is the configuration hub for new "Questions" and rooms owned by the user-->
<main>
<div class="row">
    <div id="FolderColumn" class="column left">
        <div id="FolderBox" class="box">
            <div id="HeaderBox" class="lheader">
                <!--TODO check user for login status-->
                <div id="NewFolder">
                    <button type="button" onclick="newFolder()">NEW FOLDER ICON</button>
                </div>
            </div>
            <!--Pull Folders From User-->
            <?php 
                $folders = getFolders($_SESSION['id']);
                if (sizeof($folders) > 0) :
                    foreach($folders as $i=>$index) :?>
                        <div class="folderIter dragable">
                            <h1 ondblclick="convertToForm(this)" class="renameable"><?php echo $index['folderName'].' '.($i+1);?></h1>
                            <p ondblclick="convertToForm(this)" class="renamable"><?php echo $index['folderDescription'];?></p>
                            <button type="button" onclick="deleteFolder(<?php echo $i?>)">TRASHICON</button>
                        </div>
            <?php endforeach; endif;?>
        </div>
    </div>
    <div id="QuestionColumn" class="column center">
        <div id="QuestionBox" class="box">
            <!--Question List Generator-->
            <?php   ?>
            
            <div id="NewQuestionButton">
            </div>
        </div>
    </div>
    <div id="QuestionSetColumn" class="column right">
        <div id="QuestionSetBox" class="box">
            <div id="QuestionSetHeader" class="lheader">
                <div id="NewSet">
                    <button>New Set Icon</button>
                </div>
                
            </div>
            <!--Pull Set List from database-->
            <?php  ?>
        </div>
    </div>
</div>
</main>
<?php include '../view/footer.php';?>