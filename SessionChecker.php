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
            </script>
            <script src='<?php echo $this->getUrl('sessionChecker.js'); ?>'></script>

            <style>
                /* Fix redcap alert border */
                #session-checker-success-alert { border: 1px solid darkgreen !important; }
            </style>

            <div id="session-checker-modal" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Authenticated Server Session Unavailable</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>You are either offline or your authenticated session has expired.</p>
                            <p>This can happen for many reasons, including an unstable network,
                                IP address change, different WiFi network, etc.</p>
                            <p>If you were to have saved now, you would have <u>lost the new data</u> on this form.</p>
                            <p>To continue, you must first verify you have a valid connection to the REDCap server.
                                Make sure you are online and then click the button below:</p>
                        </div>
                        <div class="modal-footer">
                            <a target="_BLANK" href="<?php echo $checkUrl . "&action=refresh"; ?>">
                                <button type="button" class="btn btn-primary">Verify Server Connection</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-success darkgreen collapse" id="session-checker-success-alert">
                <button type="button" class="close" data-dismiss="session-checker-success-alert">x</button>
                <h4 class="alert-heading">REDCap Session Re-Established</h4>
                You may now safely save your record.
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