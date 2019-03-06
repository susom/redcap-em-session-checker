# Session Checker

An EM designed to be globally enabled at sites where user authentication is done
outside of REDCap.  It helps prevent lost user submissions due to stale sessions.

### The Problem

At Stanford we use Shibboleth authentication (SAML).  We received the occasional
end-user complaint that after 'saving' a form, the data wasn't there and they were
redirected to the add/edit record page.

After an exhaustive period of trouble-shooting, we determined that the reason
this was happening was that the end-user's save POST had come through a new IP
address.  This might happen if the user moved from site-to-site, or if the IT
infrastructure used some sort of outbound NAT proxy.  In either case, it led
to user frustration and lost data.

So, we built this tool to verify that before a form is submitted, the connection
to the server is still valid.  It currently only captures data entry forms (not
project setup or other points of entry).


### How to Use

Simply enable this EM for ALL PROJECTS (first checkbox).  It assumes that
the EM url (e.g. https://your.site/external_modules/?prefix=session_checker&page=check&pid=xx)
is protected by your server's web-server authentication (Shibboleth).

There are numerous options to enable server-side logging via the optional emLogger
em or client-side logging via javascript console.log.


### How does it work?

This EM overrides the javascript function that normally submits a data entry form when
a user clicks 'save'.  Before saving, it checks that it can reach the `check.php` script
in the EM.  If it can, it then saves the form and nothing changes.  If it CANNOT
verify the connection, it displays a dialog telling the user to click on a button
to open a new tab and create a new server session.  After the session is made, 
the user is instructed to close the tab and return to their original form and re-save.