
/**
 * Verify valid session before submitting a form
 */
sessionChecker.checkBeforeSubmit = function() {

    sessionChecker.log('About to run checkBeforeSubmit');

    $.ajax({
        type: "GET",
        url: sessionChecker.checkUrl
    }).always(function(data) {
        var modal = $('#session-checker-modal');
        var isOpen = (modal.data('bs.modal') || {})._isShown;

        if (data == 1) {
            sessionChecker.log("Good Session");
            if (isOpen) modal.modal('hide');

            if (sessionChecker.status) {
                // Session still good - submit
                sessionChecker.log("Submitting!");
                sessionChecker.formSubmitDataEntry();
            } else {
                // Connection has gone good so lets remove the modal
                sessionChecker.status = true;
                sessionChecker.log("Removing Modal");

                // Tell user it is save to SAVE again with an alert that dismisses after 5 seconds
                $('#session-checker-success-alert')
                    .fadeTo(5000, 500)
                    .slideUp(500, function(){
                        $("#session-checker-success-alert").slideUp(500);
                    });
            }
        } else {
            sessionChecker.log("Bad Session");
            if (sessionChecker.status) {
                // Open modal
                if (!isOpen) modal.modal('show');

                // Session has gone bad!
                sessionChecker.status = false;
                sessionChecker.log("Session Now Bad");
            } else {
                // Session still bad!
            }
        }

        sessionChecker.log("Session state", sessionChecker.status);
    });
};


/**
 * Once down, use an interval to check if the session is back online.
 */
window.setInterval( function() {

    // If we are good, then don't do anything
    if (sessionChecker.status) return;

    // Check session again!
    sessionChecker.checkBeforeSubmit();

}, 5 * 1000);


/**
 * A logging function for javascript
 * @returns {boolean}
 */
sessionChecker.log = function() {
    if (!sessionChecker.jsLog) return false;

    // Make console logging more resilient to Redmond
    try {
        console.log.apply(this,arguments);
    } catch(err) {
        // Error trying to apply logs to console (problem with IE11)
        try {
            console.log(arguments);
        } catch (err2) {
            // Can't even do that!  Argh!  Damn you Redmond!  No logging
        }
    }
};


/**
 * Override the normal formSubmitDataEntry function from base.js
 * with a replacement function that allows us to insert our check first
 */
// Archive the original
sessionChecker.formSubmitDataEntry = formSubmitDataEntry;

// Redefine the original
formSubmitDataEntry = function () {
    // Reset the status on a new-save
    sessionChecker.status = true;

    return sessionChecker.checkBeforeSubmit();
};

sessionChecker.log("Proxied formSubmitDataEntry function with sessionChecker");
