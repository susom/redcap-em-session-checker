
/**
 * Verify valid session before submitting a form
 */
sessionChecker.checkBeforeSubmit = function() {

    sessionChecker.log('About to run checkBeforeSubmit');

    $.ajax({
        type: "GET",
        url: sessionChecker.checkUrl
    }).always(function(data) {
        if (data == 1) {
            sessionChecker.log("Good");
            if (! sessionChecker.status) {
                // The session was BAD, now it is good, so let's remove the warning
                $('div.session-checker-alert-wrapper').slideUp();
                sessionChecker.log("Removing warning");
            } else {
                // Session was good, so let's simply submit the proxied function!
                sessionChecker.log("Submitting!");
                sessionChecker.formSubmitDataEntry();
            }
            sessionChecker.status = true;
        } else {
            sessionChecker.log("Bad Session");
            if (sessionChecker.status) {
                // good session went bad
                $('div.session-checker-alert-wrapper').slideDown();
                sessionChecker.status = false;
                sessionChecker.log("Adding session warning");
            } else {
                // session was already bad - nothing has changed
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

}, 15 * 1000);


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
    return sessionChecker.checkBeforeSubmit();
};

sessionChecker.log("Proxied formSubmitDataEntry");
