<?php
/* Site settings. Change values here; nothing else in the code needs editing.
   Any value can also be set as an environment variable on the server (the name is shown beside it). */
return [
    /* Where form submissions are sent. SG_WEBHOOK_URL */
    'webhook_url' => getenv('SG_WEBHOOK_URL') ?: 'https://www.tedlearning.co.uk/wp-json/uap/v2/uap-60975-60977',

    /* How the data is sent: 'json' (a JSON body), 'form' (a normal form post) or 'multipart'
       (a form post that also attaches the CV file). Match what the receiving webhook is set to expect. SG_WEBHOOK_FORMAT */
    'webhook_format' => getenv('SG_WEBHOOK_FORMAT') ?: 'json',

    /* Seconds to wait for the webhook before giving up. */
    'webhook_timeout' => 10,

    /* Where visitors go after a form is sent. */
    'thank_you_page' => 'thank-you.php',

    /* Used to sign the hidden anti-spam token in each form. Put any long random text here. SG_SECRET */
    'secret' => getenv('SG_SECRET') ?: '',

    /* Shown if a form could not be sent. */
    'fallback_email' => 'hello@squariclegroup.com',
];
