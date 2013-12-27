LeezyPheanstalkBundleExtra
==========================

Some extra classes to work with LeezyPheanstalkBundle.

This package currently contains:

* __PrefixedTubePheanstalkProxy__ - A Pheanstalk Proxy for adding a Prefix to all used tubes.

------------------------

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

Active the proxy in the config.yml like this:

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



