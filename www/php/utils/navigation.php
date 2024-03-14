<?php

//Redirect to the specified page.
//The query param is optional, and if used must be supplied
//after being urlencoded.
function redirect_to_page(string $page_name, string $query=NULL) : void{
    //Build url to the page directory
    $url = "/pages/".$page_name.'.php';
    //If query was submitted, append it to the url
    if(!is_null($query)){
        $url = $url.'?'.$query;
    }
    //Redirect to the computed url
    header("Location: //".$_SERVER["SERVER_NAME"].$url);
    //Close connection to db if it was open, then exit
    if(isset($conn)){
        $conn->close();
    }
    die();
}

//Used as a wrapper to redirect_to_page whenever an error
//query is needed
function redirect_with_error(string $page_name, string $error_message) : void{
    //Urlencode the error message, and call the redirect function
    redirect_to_page($page_name, "error=".urlencode($error_message));
}

//Redirect to the index page
function redirect_to_index() : void{
    header("Location: //".$_SERVER["SERVER_NAME"]);
}

?>