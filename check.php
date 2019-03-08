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

    $old_ip = @$_GET['ip'];
    $new_ip = $module->getIp();

    ?>
        <div class="jumbotron text-center alert-danger">
            <h2 class="display-4">REDCap Session Established</h2>
            <p class="lead text-center">Your REDCap session is now working.</p>
            <?php
                if (!empty($old_ip) && ($old_ip != $new_ip)) {
                    // The ip addresses have changed
                    echo "<p>It appears that your IP address has changed since your last valid session ($old_ip => $new_ip). " .
                        "This could explain why your session was lost.  If you work in an environment where your network IP " .
                        "is unreliable, you may want to try making a VPN connection first.</p>";
                }

            ?>
            <p class="text-center">Please close this tab and return to your REDCap form and press 'SAVE' to submit your form.</p>
            <div class="mt-4 btn btn-danger btn-lg" role="button">Close Tab</div>
        </div>
        <script>
            $(".btn").on('click', function() { close(); });
        </script>
    <?php

    $module->emLog("Session refreshed", $module->getIp());
    exit();
}

// SESSION IS VALID
echo "1";
