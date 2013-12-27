[![Build Status](https://travis-ci.org/h4cc/LeezyPheanstalkBundleExtra.png?branch=master)](https://travis-ci.org/h4cc/LeezyPheanstalkBundleExtra)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/h4cc/LeezyPheanstalkBundleExtra/badges/quality-score.png?s=fcaac46cf7a9c97db9da9a336f0222594cf4a5eb)](https://scrutinizer-ci.com/g/h4cc/LeezyPheanstalkBundleExtra/)
[![Code Coverage](https://scrutinizer-ci.com/g/h4cc/LeezyPheanstalkBundleExtra/badges/coverage.png?s=63fb90374cb2105f5e26bd076c40582aeb50c0b1)](https://scrutinizer-ci.com/g/h4cc/LeezyPheanstalkBundleExtra/)

LeezyPheanstalkBundleExtra
==========================

Some extra classes to work with LeezyPheanstalkBundle.

This package currently contains:

* __PrefixedTubePheanstalkProxy__ - A Pheanstalk Proxy for adding a Prefix to all used tubes.

------------------------

## Installation

Installing this package can be done with the following command:

```
php composer.phar require h4cc/pheanstalk-bundle-extra:dev-master
```

_Hint: Use a more stable version if available!_


## PrefixedTubePheanstalkProxy

This Proxy is abled to prefix all tubes with a given string.

New Methods are:
```
PrefixedTubePheanstalkProxy
    - setTubePrefix($prefix);
    - getTubePrefix();
```

## Using a custom Proxy in Symfony2

Define the the proxy as a service:

__services.xml__
```
<service id="your_app.pheanstalk.proxy" class="h4cc\LeezyPheanstalkBundleExtra\Proxy\PrefixedTubePheanstalkProxy">
    <call method="setTubePrefix">
        <argument>your_app_</argument>
    </call>
</service>
```

_or_ 

__services.yml__
```
services:
    your_app.pheanstalk.proxy:
        class: "h4cc\LeezyPheanstalkBundleExtra\Proxy\PrefixedTubePheanstalkProxy"
        calls:
            - [ setTubePrefix, [ "your_app_" ] ]
```

Activate the proxy in the app/config/config.yml like this:

```
leezy_pheanstalk:
    enabled: true
    pheanstalks:
        primary:
            server: 127.0.0.1
            port: 11300
            timeout: 60
            default: true
            proxy: your_app.pheanstalk.proxy
```


A cleaner way would be to define the TubePrefix String as a Parameter.



