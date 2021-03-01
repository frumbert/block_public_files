# block_public_files

This Moodle Block is just like the internal "private_files" block, except it's public. Files added to this block are available in the system context - meaning all users can access them whenever and wherever this block is visible.

![Screenshot hosted by Imgur](http://i.imgur.com/0hIngNE.png)

Only the *site admin* has the ability to add files. Allows any files types of any file size as per the system specification, including urls, files and folders. Clicking a filename prompts the user to download it.

This is a single-instance only block, and you can configure it's title - but that's about it. Other than that, it works exactly the same as the private_files block. Files are not visible to guests or non-authenticated on users.

### Linking to URLS

Supports macos and Windows URL shortcuts. Drag a URL to your desktop from your browser to create a shortcut to that page / site. On macos it gets a '.webloc' extension; on Windows it gets a '.url' extension. Then drag that shortcut to the Manage Files tab - it will attach like any regular file. When the user clicks the link, it will open the URL in a new tab.

## What it's for

Do you have a standard set of documents that you need to put on all your courses, such as your site policy and assessment guidelines? Are you currently attaching these to a dummy course or as seperate copies in file resources in all your courses? No more - just add a public_files block on your dashboard or wherever, and there you go.

## installation

Belongs in /blocks/public_files 
Install as you would any other plugin (e.g. site admin > notifications, Plugins > install, etc)

## Requirements

2.7+
Last tested in Moodle 3.10.1+
Raise an issue in the github issue tracker if you find a version that it's NOT working with!

## Licence

GPL3, same as Moodle