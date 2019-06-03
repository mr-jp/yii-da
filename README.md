# DeviantArt Stash Uploader

This web app allows you to publish multiple images from a Deviantart stash. Interface is ugly and unrefined, but eh ... works for me. It's really not for the faint of heart!

----
## Requirements:
1. php
1. curl ([make sure SSL is set properly](https://stackoverflow.com/questions/28858351/php-ssl-certificate-error-unable-to-get-local-issuer-certificate))
1. composer

----
## How to use:

1. Register for a DeviantArt account (if you haven't already!)
1. Clone this repo and run `composer update`
1. Setup a virtual host to point to this application and save that URL as the environment variable `DA_REDIRECT_URI`
1. Get [DeviantArt API access](https://www.deviantart.com/developers/)
1. Publish an API and create environment variables for `client_id` as `DA_CLIENT_ID` and `client_secret` as `DA_CLIENT_SECRET`
1. Visit the URL you created earlier, click on login
1. Login to your DeviantArt account

----
## Stash

Visit the Stash page by clicking on `stash` on the top menu

1. Navigate the stack levels
1. In a stack level with images in it, you can click on `Publish Items on this stack`
1. On that page, enter `artist_comments`, `tags` and select a Gallery to publish to
1. Click `Publish All`
