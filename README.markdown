#PHP Email Form

This was designed to be my replacement for the antiquated CGIemail.

* `$subject` is used for the subject line of the email
* `$my_email` is the email box it's delivered to
* `$site_name` is used for the page title in the Thank You page
* `$site_url` is used for the meta refresh link
* `$continue` is used for the homepage link in the Thank You body copy.

The form submits to `thankyou/` (thankyou/index.php) for a cleaner URL.  The Thank You page template is at the bottom of the PHP page.

95% of the PHP script is based on [Form To Email](http://formtoemail.com) by Charles Sweeney.  His pro version adds a lot of extra features if you're looking for something beefier.