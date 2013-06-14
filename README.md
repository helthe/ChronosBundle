# Chronos Bundle

The Helthe Chronos Bundle provides an object oriented library for managing cron jobs both with crontab and
programmatically in Symfony2.

[![Build Status](https://secure.travis-ci.org/helthe/ChronosBundle.png?branch=master)](http://travis-ci.org/helthe/ChronosBundle)

## Installation

### Step 1: Composer

Add the following in your componser.json:

    {
        "require": {
            "helthe/chronos-bundle": "~1.0"
        }
    }

### Step 2: Register the bundle

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Helthe\Bundle\ChronosBundle(),
        );
    }

### Step 3: Configure the bundle

The bundle comes with a sensible default configuration, which is listed below.

    helthe_chronos
        cache_dir: %kernel.cache_dir%/helthe_chronos
        crontab:
            default_user: ~ # If null, edits the crontab of the user running the command.
            executable: /usr/bin/crontab # Path to crontab executable.
            run_job: false # If true, will add the helthe:chronos:run command to crontab to be run every minute.
        enable_annotations: false # If true, allows for the use of annotations.

## Resources

You can run the unit tests with the following command:

    $ cd path/to/Helthe/Bundle/XXX/
    $ composer.phar install --dev
    $ phpunit
