<?php
namespace Stanford\SessionChecker;
/** @var \Stanford\SessionChecker\SessionChecker $module */

use \HtmlPage;

// PREVENT CACHING
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// GIVE INSTRUCTIONS ON A REFRESH
if (isset($_GET['action']) && $_GET['action'] == "refresh") {

    // RENDER A NICE PAGE
    $HtmlPage = new HtmlPage();
    $HtmlPage->PrintHeaderExt();
    ?>
        <div class="jumbotron text-center alert-danger">
            <h2 class="display-4">REDCap Session Established</h2>
            <p class="lead text-center">Your REDCap session is now working.</p>
            <p class="text-center">Please close this tab and return to your REDCap form and press 'SAVE' to submit your form.</p>
            <div class="mt-4 btn btn-danger btn-lg" role="button">Close Tab</div>
        </div>
        <script>
            $(".btn").on('click', function() { close(); });
        </script>
    <?php
    $module->emLog("Session refreshed");
    exit();
}

// SESSION IS VALID
echo "1";
