# Social Media Popup

[![Build Status](https://travis-ci.org/gruz0/social-media-popup.svg?branch=master)](https://travis-ci.org/gruz0/social-media-popup)

Plugin creates the popup window with most popular social media widgets

## Description
Plugin creates popup window with most popular social media widget.

Available widgets:
* Facebook Page Plugin
* VK.com Community Widget
* Odnoklassniki
* Google+ Page and Profile Badge
* Twitter Timeline
* Pinterest Board

## Installation

This section describes how to install the plugin and get it working.

1. Upload plugin zip-archive to the `/wp-content/plugins/` directory
2. Extract files from the archive
3. Activate the plugin through the 'Plugins' menu in WordPress

## Development

Install npm first (for CentOS, e.g.):
```
yum install nodejs npm
```

Then install required packages (run in repository directory):
```
npm install
```

## PHP CodeSniffer usage

In the project directory run:
```
phpcs --standard=phpcs.xml path/to/file.php -s
```
