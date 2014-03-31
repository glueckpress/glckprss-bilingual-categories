# GlückPress Bilingual Categories

Relabels default categories as “languages” and makes them available on pages.

## Description

This plugin is a hack. It requires that you set up your categories in WordPress as languages, i.e.:

* English (slug: `en`)
* Deutsch (slug: `de`)

It then

* relabels categories as languages
* makes them available on pages
* when the current category is `de`:
    * sets locale to de_DE
    * reloads the default textdomain in the front-end

**Feel free to use, but know what you’re doing!**

## Installation

### Requirements

* WordPress 3.8.1
* PHP 5.3

### Installation

If you don’t know how to install a plugin for WordPress, leave now.

## Settings

* There is no settings page.
* The plugin requires the default category to be `en` and an alternate one to be `de`.
* You can hack this to modify settings.

## Changelog

### 2014.01

* Alpha Release
