<?php

function getTitle(){
        global $pageTitle;

        if(isset($pageTitle)){
            echo $pageTitle;
        } else {
            echo "AccountSaver - Page";
        }
}