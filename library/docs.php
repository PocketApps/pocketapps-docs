<?php

class pocketapps_docs {
    private $PLACEHOLDER_SIDEBAR = '@POCKETAPPS_DOCS_SIDEBAR_CONTENT@';
    private $PLACEHOLDER_MAIN = '@POCKETAPPS_DOCS_MAIN_CONTENT@';

    private $htmlDocument;
    private $sidebarContent;
    private $mainContent;

    private function guid() {
        return strtolower(sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
            mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));
    }

    public function header($title, $appName, $author = null, $date = null) {
        $dir = !empty($_SERVER['HTTPS']) ? 'https' : 'http' . '://' . $_SERVER['HTTP_HOST'] .
            substr(dirname(__FILE__), strlen(substr($_SERVER['SCRIPT_FILENAME'], 0,
                strlen($_SERVER['SCRIPT_FILENAME']) - strlen($_SERVER['REQUEST_URI']))));;

        $subtitle = "";
        if (!empty($author)) {
            $subtitle .= "Documented by: " . $author;
        }

        if (!empty($date)) {
            if (!empty($subtitle)) {
                $subtitle .= "<br/>";
            }

            $subtitle .= "Date: " . $date;
        }

        if (!empty($subtitle)) {
            $subtitle = "<p align='center'><i>$subtitle</i></p>";
        }

        $this->htmlDocument .= "<html style='overflow-x: hidden'>
                <head>
                    <title>$title | $appName</title>
                    <link rel='stylesheet' type='text/css' href='$dir/bootstrap-3.3.6/css/bootstrap.min.css'/>
                    <link rel='stylesheet' type='text/css' href='$dir/bootstrap-3.3.6/css/bootstrap-theme.min.css'/>
                </head>
                <body style='background-color: #fcfcfc'>
                    <div class='row'>
                        <h1 align='center'>$title</h1>$subtitle<hr/>
                    </div>
                    <div class='row' style='margin-left: 3%; margin-right: 3%'>                    
                        <div class='col-lg-2 col-md-4 col-sm-12 col-xs-12'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'><strong>Contents</strong></div>
                                <div class='panel-body'><ul class='list-group' style='margin: 0'>$this->PLACEHOLDER_SIDEBAR</ul></div>
                            </div>                            
                        </div>
                        <div class='col-lg-10 col-md-8 col-sm-12 col-xs-12' style='padding-left: 3%'>$this->PLACEHOLDER_MAIN</div>
                    </div>";
    }

    public function generate_response($title, $response) {
        return json_encode(array(
            'title' => $title . ":",
            'response' => $response
        ));
    }

    public function generate_error_code($code, $description) {
        return json_encode(array(
            'code' => $code . ":",
            'description' => $description
        ));
    }

    private function echo_response($response) {
        $decodedResponse = json_decode($response);
        $title = $decodedResponse->{'title'};
        $value = $decodedResponse->{'response'};

        if (empty($title) && empty($value)) {
            $this->mainContent .= "<pre>$response</pre>";
        } else {
            if (!empty($title)) {
                $this->mainContent .= "<p><br/><strong>$title</strong><br/></p>";
            }

            if (!empty($value)) {
                $this->mainContent .= "<pre>$value</pre>";
            }
        }
    }

    private function echo_error_code($errorCode) {
        $decodedErrorCode = json_decode($errorCode);
        $code = $decodedErrorCode->{'code'};
        $description = $decodedErrorCode->{'description'};

        if (empty($code) || empty($description)) {
            $this->mainContent .= "<pre>$errorCode</pre>";
        } else {
            $this->mainContent .= "<p><strong>$code</strong>  $description</p>";
        }
    }

    public function add_item($title, $description, $implementation, $response, $errorCodes,
                              $sinceVersion = null,  $sinceDate = null, $lastUpdated = null) {
        $id = $this->guid();
        $this->mainContent .= "<div id='$id' class='panel panel-default'><div class='panel-heading'><strong>$title</strong></div>" .
            "<div class='panel-body'>";

        if (!empty($sinceVersion)) {
            $this->mainContent .= "<strong><i>Since version: </strong>$sinceVersion</i><br/>";
        }

        if (!empty($sinceDate)) {
            $this->mainContent .= "<strong><i>Initial release: </strong>$sinceDate</i><br/>";
        }

        if (!empty($lastUpdated)) {
            $this->mainContent .= "<strong><i>Last Updated: </strong>$lastUpdated</i><br/>";
        }

        if (!empty($sinceVersion) || !empty($sinceDate) || !empty($lastUpdated)) {
            $this->mainContent .= "<br/>";
        }

        $this->mainContent .= "<p>$description</p>";

        if (!empty($implementation)) {
            $this->mainContent .= "<h4 style='margin-top: 25px'><u>Implementation</u></h4><pre>$implementation</pre>";
        }

        if (!empty($response)) {
            $this->mainContent .= "<h4 style='margin-top: 25px'><u>Expected Response</u></h4>";
            if (is_array($response)) {
                foreach ($response as $singleResponse) {
                    $this->echo_response($singleResponse);
                }
            } else {
                $this->echo_response($response);
            }
        }

        if (!empty($errorCodes)) {
            $this->mainContent .= "<h4 style='margin-top: 25px'><u>Error Code(s)</u></h4><br/>";
            if (is_array($errorCodes)) {
                foreach ($errorCodes as $errorCode) {
                    $this->echo_error_code($errorCode);
                }
            } else {
                $this->echo_error_code($errorCodes);
            }
        }

        $this->mainContent .= "</div></div>";
        $this->sidebarContent .= "<li class='list-group-item'><a href='#$id'>$title</a></li>";
    }

    public function footer($company, $startYear = null) {
        $startYear = empty($startYear) ? date('Y') : $startYear;
        $this->htmlDocument .= "<strong><br/><div align='center'>© " . ((string)$startYear === date('Y') ? $startYear : ($startYear . '-' . date('Y'))) .
            " " . $company . ". All rights reserved</div><br/></strong>";
        $this->htmlDocument .= "</body></html>";
        $this->htmlDocument = str_replace($this->PLACEHOLDER_SIDEBAR, $this->sidebarContent, $this->htmlDocument);
        $this->htmlDocument = str_replace($this->PLACEHOLDER_MAIN, $this->mainContent, $this->htmlDocument);
        echo $this->htmlDocument;
    }
}