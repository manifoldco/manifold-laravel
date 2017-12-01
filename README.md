## Introduction
The Manifold Laravel package allows you to connect to your Manifold account and
pull your credentials, keys, configurations, etc. from your Manifold account
into your Laravel application.

## Installation

1. Install the package
`composer require manifoldco/manifold-laravel`

2. Publish the config file
`php artisan vendor:publish`
Select `manifoldco\manifold-laravel` from the vendor list.

3. Add, at the very least, your Manifold Bearer token to your `.env` file as
follows
`MANIFOLD_API_TOKEN=YOUR-TOKEN-HERE`

4. You may optionally specify a Resource, Project, or Product by providing their
respective IDs in your `.env` file.  
`MANIFOLD_RESOURCE_ID=YOUR-RESOURCE-ID`  
`MANIFOLD_PROJECT_ID=YOUR-PROJECT-ID`  
`MANIFOLD_PRODUCT_ID=YOUR-PRODUCT-ID`  


## Usage
Once installed and configured, your project/resource variables from Manifold
will be pulled into your Laravel application as configurations. Your variables
can be accessed using the `config` helper, using the key as it exists in
Manifold. For example, a variable stored in your Manifold resource named
`API_KEY` can be accessed using `config('API_KEY')`.

Note that keys are case sensitive. Also note that when variable keys conflict,
either with each other or with other Laravel configs, the last value with the
given key will be the one added to the configuration.
