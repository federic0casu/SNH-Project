<?php

//Redirect to the specified page.
//The query param is optional, and if used must be supplied
//after being urlencoded.
function redirect_to_page($page_name, $query=NULL){
    //Build url to the page directory
    $url = "../../pages/".$page_name.'.html';
    //If query was submitted, append it to the url
    if(!is_null($query)){
        $url = $url.'?'.$query;
    }
    //Redirect to the computed url
    header("Location: ".$url);
    die();
}

//Used as a wrapper to redirect_to_page whenever an error
//query is needed
function redirect_with_error($page_name, $error_message){
    //Urlencode the error message, and call the redirect function
    redirect_to_page($page_name, "error=".urlencode($error_message));
}

?>