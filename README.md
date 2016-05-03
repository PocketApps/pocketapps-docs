# PocketApps Docs
Create and manage API documentation

## Setup
1. Download the source code or clone the repo (git clone)
2. Include the docs.php and the bootstrap files in your project
3. Include the class:
    `include_once 'path/to/docs.php';`
4. Create a new instance of the class:
    `$docs = new pocketapps_docs();`
5. Start your documentation file:
    `$docs->header('API_NAME', 'APP_NAME', 'AUTHOR', 'DATE');`
6. Create a new response:
    `$docs->generate_response('TITLE', 'RESPONSE');`
7. Create a new error code:
    `$docs->generate_error_code('ERROR_CODE', 'DESCRIPTION');`
8. Add documentation:
    `$docs->add_item('TITLE', 'DESCRIPTION', 'IMPLEMENTATION', 'RESPONSE', 'ERROR_CODES');`
9. End your documentation file:
    `$docs->footer('COMPANY_NAME', 'START_YEAR');`

Check out the example directory for a working example of this framework

## Contact Us
1. Visit our website - [https://www.pocketapps.co.za](https://www.pocketapps.co.za)
2. Send us an email - [support@pocketapps.co.za](mailto:support@pocketapps.co.za)
2. Like us on [Facebook](http://facebook.com/PocketAppsSoftware)
3. Follow us on [Twitter](https://twitter.com/MyPocketApps)