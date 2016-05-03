<?php
include_once '../library/docs.php';

$docs = new pocketapps_docs();
$docs->header("Account Management", "PocketApps", "Henry Jooste", "03-06-2016");

//Authentication
$implementation = "include_once 'library/pocketapps/service.php';<br/>\$service = new pocketapps_service();<br/>" .
    "\$service->authenticate('user@domain.com', 'password');";
$response = array(
    $docs->generate_response("Failed response", "{<br/>   \"error\": true,<br/>   \"code\": \"0x001\"<br/>   \"message\":" .
        " \"Invalid email address and password combination\"<br/>}"),
    $docs->generate_response("Successful response", "{<br/>   \"error\": false,<br/>   \"id\": \"1234-5678-9012-3456\"<br/>   " .
        "\"session\": \"9876-5432-1098-7654\"<br/>}")
);
$errorCodes = array(
    $docs->generate_error_code("0x001", "User is not registered"),
    $docs->generate_error_code("0x002", "Account needs to be verified"),
    $docs->generate_error_code("0x003", "Invalid password"),
    $docs->generate_error_code("0x004", "Unknown server error"),
);
$docs->add_item("Authentication", "Allow registered users to authenticate using their email address and password",
    $implementation, $response, $errorCodes);

//Register Account
$implementation = "include_once 'library/pocketapps/service.php';<br/>\$service = new pocketapps_service();<br/>" .
    "\$service->register_account('user@domain.com', 'password');";
$response = array(
    $docs->generate_response("Failed response", "{<br/>   \"error\": true,<br/>   \"code\": \"0x005\"<br/>   \"message\":" .
        " \"User is already registered\"<br/>}"),
    $docs->generate_response("Successful response", "{<br/>   \"error\": false,<br/>   \"id\": \"1234-5678-9012-3456\"<br/>   " .
        "\"message\": \"Please check your emails to verify your account\"<br/>}")
);
$errorCodes = array(
    $docs->generate_error_code("0x005", "User is already registered"),
    $docs->generate_error_code("0x006", "User is already registered but account needs to be verified"),
    $docs->generate_error_code("0x007", "Invalid email address provided"),
    $docs->generate_error_code("0x008", "Unknown server error"),
);
$docs->add_item("Register Account", "Register a new account using the provided email address and password",
    $implementation, $response, $errorCodes);

$docs->footer("PocketApps");