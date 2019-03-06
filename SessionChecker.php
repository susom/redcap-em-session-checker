<?php
namespace Stanford\SessionChecker;

include_once "emLoggerTrait.php";

class SessionChecker extends \ExternalModules\AbstractExternalModule
{
    use emLoggerTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function redcap_data_entry_form_top($project_id, $record = NULL, $instrument, $event_id, $group_id = NULL, $repeat_instance = 1)
    {
        // CREATE SESSION CHECKER
        $checkUrl = $this->getUrl('check.php');
        ?>
            <script>
                // Create an object to contain our sessionChecker javascript
                if(typeof sessionChecker === 'undefined') { var sessionChecker = {}; }

                // This is the url we will use to verify the session
                sessionChecker.checkUrl = <?php echo json_encode($checkUrl) ?>;

                // This determines whether or not we log to the client console
                sessionChecker.jsLog = <?php echo $this->isDev() ?>;

                // We default to a valid session as we just loaded a data entry page
                sessionChecker.status = true;
            </script>
            <script src='<?php echo $this->getUrl('sessionChecker.js'); ?>'></script>

            <style>
                div.session-checker-alert-wrapper { display: none; position: fixed; top:0; left:50%; width: 100%; z-index: 1000;}
                div.session-checker-alert-wrapper div.alert { position: relative; left:-50%;  border: 2px solid red;}
                div.session-checker-alert-wrapper a { text-decoration: none;}
                div.session-checker-alert-wrapper strong { font-size: larger; font-weight: bold; }
            </style>

            <div class="session-checker-alert-wrapper">
                <div class="alert alert-danger text-center">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <strong>SERVER UNAVAILABLE WARNING!</strong><br>
                    You are either offline or your authentication session has expired.<br>
                    This can happen if your network is unstable or you changed IP addresses since your last page.<br>
                    If you submit now, <u>you risk loosing the data</u> you may have entered on the form.<br>
                    To re-establish a valid server session before saving, make sure you are online and click on:
                    <div>
                        <a target="_BLANK" href="<?php echo $checkUrl . "&action=refresh"; ?>">
                            <span class="button btn-danger btn-xs">VERIFY CONNECTION</span>
                        </a>
                    </div>
                    When you return to this tab, this warning should clear after ~30 seconds.
                </div>
            </div>

        <?php



    }




    /**
     * Try to determine if this is a dev server and we should do js logging
     * @return int
     */
    private function isDev()
    {
        $is_localhost = (@$_SERVER['HTTP_HOST'] == 'localhost');
        $is_dev_server = (isset($GLOBALS['is_development_server']) && $GLOBALS['is_development_server'] == '1');
        $debug_enabled = $this->getSystemSetting('enable-js-debug-logging');
        $is_dev = ($is_localhost || $is_dev_server || $debug_enabled) ? 1 : 0;
        return $is_dev;
    }

}