# Minify

Minify is a JS and CSS minifier toolkit (with support for generating templates for engines such as Smarty).

Its main features are:

* automatically find all CSS/JS files in a given directory named `xxx-blah.css` where `xxx` is a three digit ordering / sequence number;
* minify these files and create a single file bundle;
* automatically generate template include files allowing production / development mode (i.e. use individual CSS/JS or bundles based on an application option);
* versioning for those using CDNs, future expiry dates, etc to ensure clients load fresh JS/CSS bundles.

See the **Example Usage** section below.

## Usage

Using Minify is as simple as copying the included `minify-options.php.dist` to `minify-options.php` and editing to suit you environment. Then run the script:

    ./minify.php

A full destription of the available options is commented within `minify-options.php.dist` and the script has a help option:

    ./minify.php --help


## Example Usage

[IXP Manager](https://github.com/inex/IXP-Manager) uses Minify. You can see IXP Manager's [JS files here](https://github.com/inex/IXP-Manager/tree/develop/public/js) and its [CSS files here](https://github.com/inex/IXP-Manager/tree/develop/public/css). 

The Minify script bundles the individual files into `min.bundle-vX.css` and `min.bundle-vX.js` files and also creates templates with logic for using the original uncompressed files or the compressed bundles in [this file for CSS](https://github.com/inex/IXP-Manager/blob/develop/application/views/header-css.tpl) and [this file for JS](https://github.com/inex/IXP-Manager/blob/develop/application/views/header-js.tpl).

The configuration for generating these [is here](https://github.com/inex/IXP-Manager/blob/develop/bin/minify-options.php).

## Used By

* [ePayroll](http://www.epayroll.ie/) - Online payroll processing and management.
* [IXP Manager](https://github.com/inex/IXP-Manager) - A web application to assist in the management of Internet Exchange Points (IXPs)
* [TallyStick](http://www.tallystick.net/) - Focus on what you love to do, while we take care of tracking your time.
* [ViMbAdmin](https://github.com/opensolutions/vimbadmin/wiki) - Virtual Mailbox Administration


Do you use Minify? Do you want to get listed here? Drop us a note to `info _at_ opensolutions _dot_ it` or open a pull request.

## License and Copyright

Copyright (c) 2010 - 2014, [Open Source Solutions Limited](http://www.opensolutions.ie/), Dublin, Ireland

Released under the BSD license:

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


## Thirdparty Tools

Minify includes two thirdparty tools:

* The [Google Closure Compiler](http://code.google.com/closure/compiler) licensed under the Apache License, Version 2.0.
* Yahoo's [YUI Compressor](http://developer.yahoo.com/yui/compressor/) issued under a BSD license.


