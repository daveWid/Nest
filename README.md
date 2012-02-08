# Nest

Nest is a file-system driven wiki engine for PHP 5.3+.

## That this Library is

I just wanted the ability to create a simple knowledge base in a wiki type style.
I also wanted git to be able to track all of my changes.

## What The Library is not

I have no plans to make this a full fledged wiki system with access privileges or
a web interface. If that is something you are looking for, then there are probably
better libraries for you. Or if you are a developer, fork this repository and hack away!

## Setup

The first thing you will want to do is install the library files anywhere on your
system. After you clone in the repository you will want to run a recursive update
to pull in linked submodules.

```bash
git submodule update --init --recursive
```

### Bash script

After you have all of the code you will need to link the cli script in your path
somewhere.

The easiest place to link the script is in `/usr/local/bin'

```bash
cd /usr/local/bin
ln -s NEST_PATH/bin/nest.php nest
```

If everything goes as planned the script should be found (_i.e. `which nest` should
print back a location_).

## Command Line

Run `nest help` from the command line for a quick guide on how to use the cli script.

Adding `-v` to the end of a command will turn on verbose output if you would like to
see what is going on when the script is running.

## Creating a Site

You will want to navigate to the directory that you want to create your wiki in
and run `nest create`. This will add some default files/directories to get you
on your way.

**_wiki**: This directory will hold all of your "pages" in the raw format.

**.htaccess**: This file turns on url rewriting and protects some files. If your
wiki is not in the root of a site (_i.e. /wiki/_) you will need to change the
`RewriteBase /` line to reflect that (`RewriteBase /wiki/`).

**index.php**: The "controller" for the wiki. You will need to set the path to
the nest files at the top and that is all. This path can be relative to the current
file or an absolute path.

**config.ini*: The configuration file. This file will need to be edited to fit your
setup. Below is a list of options that can be set.

Option | Description
-------|------------
renderer | The name of the renderer class that will be use to build the pages
extension | The file extension to search for when building the pages
base_url | The base url for the site. Default is "/", in our wiki example it would be "/wiki/"

## Creating a Layout

You will want to create the main layout for you site in the file `_wiki/layout.php`.
All of the pages that are accessed will be wrapped into this file. The only requirement
of the layout is that wherever you want your page content to show up you will need to
echo `$content` (`<?php echo $content; ?>`).

### Helper Function

Since wiki's might not always be at the root of the site, you can use `\Nest\Core::url()`
and pass in a url. So with a file at `css/style.css` you could do `\Nest\Core::url("css/style.css")`
and that function will add the `base_url` configuration option to the beginning
of the url. This way you can specify all paths relative to the root of the site and
not have to worry about anything if you wiki gets moved.

## Custom Error

You can also create a custom error page. Put this page in the `_wiki` directory
named `errorEXTENSION` replacing EXTENSION with the extension you set in your
config file.

## Hacking

Want to add a renderer or see some broken code? Please fork this repo and work off
of the develop branch. When you get your feature implemented, the send those pull
requests!

-------

Developed by [Dave Widmer](http://www.davewidmer.net).
