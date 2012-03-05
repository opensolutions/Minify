#!/usr/bin/env php
<?php
 /**
  * A JS and CSS minifier for projects using the Smarty PHP templating engine
  *
  * Released under the BSD License.
  *
  * Copyright (c) 2010 - 2012, Open Source Solutions Limited, Dublin, Ireland <http://www.opensolutions.ie>.
  * All rights reserved.
  *
  * Redistribution and use in source and binary forms, with or without modification, are permitted
  * provided that the following conditions are met:
  *
  *  - Redistributions of source code must retain the above copyright notice, this list of
  *    conditions and the following disclaimer.
  *  - Redistributions in binary form must reproduce the above copyright notice, this list
  *    of conditions and the following disclaimer in the documentation and/or other materials
  *    provided with the distribution.
  *
  * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS
  * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
  * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
  * THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
  * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
  * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
  * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
  * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
  * OF THE POSSIBILITY OF SUCH DAMAGE.
  */


define( 'VERSION', '1.0' );
define( 'SCRIPTDIR', dirname( __FILE__ ) );

// if your project is using JS/CSS caching, furture expire dates, CDNs, etc then you
// should version your bundles to ensure new releases are used immediately
$version = false;

// default configuration file location
$conf = dirname( __FILE__ ) . "/minify-options.php";

for( $i = 1; $i < count( $argv ); $i++ )
{
    switch( $argv[$i] )
    {
        case '--version':
            $version = $argv[++$i];
            break;

        case '--conf':
            $conf = $argv[++$i];
            break;

        case '--verbose':
            $verbose = true;
            break;

        case '--quiet':
            $verbose = false;
            break;

        case '--css-only':
            $whatToCompress = 'css';
            break;

        case '--js-only':
            $whatToCompress = 'js';
            break;

        case '--help':
        case '-h':
            print_help();
            die();
            break;

        case '--license':
        case '-l':
            print_version( false );
            die();
            break;

        case '-v':
            print_version();
            die();
            break;

        default:
            echo "Unknown parameter {$argv[$i]}\n\n";
            print_help();
            die();
    }
}

if( !file_exists( $conf ) )
    die( "ERROR: The configuration file [{$conf}]\n       does not seem to exist.\n\n       Please create the configuration file.\n\n" );

require_once( $conf );

if( in_array( $whatToCompress, array( 'all', 'js' ) ) )
{
    verbose( "\n\nMinifying JavaScript files:\n\n" );

    $files = glob( $js_files );
    sort( $files, SORT_STRING );

    $numFiles = sizeof( $files );
    $count = 0;
    $jshdr = '';

    foreach( $files as $oneFileName )
    {
        $count++;

        verbose( "    [{$count}] " . basename( $oneFileName ) . " => min." . basename( $oneFileName ) . "\n" );

        exec( $js_compiler . " --js {$oneFileName} --js_output_file {$js_dest}/min." . basename( $oneFileName ) );

        $jshdr .= "    <script type=\"text/javascript\" src=\"{$http_js}/" . basename( $oneFileName ) . "\"></script>\n";
    }

    $mergedJs = '';

    verbose( "\n    Combining..." );
    foreach( $files as $fileName )
    {
        $mergedJs .= file_get_contents( "{$js_dest}/min." . basename( $fileName ) );

        if( $del_mini_js )
            unlink( "{$js_dest}/min." . basename( $fileName ) );
    }

    if( $del_old_js_bundles )
    {
        foreach( glob( "{$js_dest}/min.bundle*js" ) as $minf )
            unlink( $minf );
    }

    if( $version )
        file_put_contents( "{$js_dest}/min.bundle-v{$version}.js", $mergedJs );
    else
        file_put_contents( "{$js_dest}/min.bundle.js", $mergedJs );

    if( $js_header_file )
    {
        $jshdrt = "{$mini_js_conditional_if}\n    <script type=\"text/javascript\" src=\"{$http_js}/min.bundle";

        if( $version )
            $jshdrt .= "-v{$version}";

        $jshdrt .= ".js\"></script>\n{$mini_js_conditional_else}\n{$jshdr}{$mini_js_conditional_end}\n";

        file_put_contents( $js_header_file, $jshdrt );
    }

    verbose( " done\n\n" );
}


if( in_array( $whatToCompress, array( 'all', 'css' ) ) )
{

    verbose( "\nMinifying CSS:\n" );

    $files = glob( $css_files );
    sort( $files, SORT_STRING );

    $numFiles = sizeof( $files );
    $count = 0;
    $csshdr = '';

    foreach( $files as $oneFileName )
    {
        $count++;

        verbose( "    [{$count}] " . basename( $oneFileName ) . " => min." . basename( $oneFileName ) . "\n" );

        exec( $css_compiler . " -o {$css_dest}/min." . basename( $oneFileName ) . " {$oneFileName}" );

        $csshdr .= "    <link rel=\"stylesheet\" type=\"text/css\" href=\"{$http_css}/" . basename( $oneFileName ) . "\" />\n";
    }

    $mergedCss = '';

    verbose( "\n    Combining..." );
    foreach( $files as $fileName )
    {
        $mergedCss .= file_get_contents( "{$css_dest}/min." . basename( $fileName ) );

        if( $del_mini_css )
            unlink( "{$css_dest}/min." . basename( $fileName ) );
    }

    if( $del_old_css_bundles )
    {
        foreach( glob( "{$css_dest}/min.bundle*css" ) as $minf )
            unlink( $minf );
    }


    if( $version )
        file_put_contents( "{$css_dest}/min.bundle-v{$version}.css", $mergedCss );
    else
        file_put_contents( "{$css_dest}/min.bundle.css", $mergedCss );

    if( $css_header_file )
    {
        $csshdrt = "{$mini_css_conditional_if}\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"{$http_css}/min.bundle";

        if( $version )
            $csshdrt .= "-v{$version}";

        $csshdrt .= ".css\" />\n{$mini_css_conditional_else}\n{$csshdr}{$mini_css_conditional_end}\n";

        file_put_contents( $css_header_file, $csshdrt );
    }

    verbose( " done\n\n" );
}

if( $version && !$js_header_file )
    echo "\n\n****** VERSION NUMBER WAS SPECIFIED - DON'T FORGET TO UPDATE HEADER FILES!! ******\n\n";


function verbose( $msg )
{
    global $verbose;
    if( $verbose )
        print $msg;
}


function print_version( $mini = true )
{

    echo "
Minify V" . VERSION . "- A JS and CSS minifier for projects using the Smarty PHP templating engine

Copyright (c) 2010 - 2012, Open Source Solutions Limited, Dublin, Ireland - http://www.opensolutions.ie
Released under the BSD License.
";
    if( !$mini )
        echo <<<END_VERSION
Redistribution and use in source and binary forms, with or without modification, are permitted
provided that the following conditions are met:

 - Redistributions of source code must retain the above copyright notice, this list of
   conditions and the following disclaimer.
 - Redistributions in binary form must reproduce the above copyright notice, this list
   of conditions and the following disclaimer in the documentation and/or other materials
   provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS
OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
OF THE POSSIBILITY OF SUCH DAMAGE.

END_VERSION;

}

function print_help()
{
    print_version();

    echo <<<END_HELP

 --version <num>     Version the minified bundles with the given number

 --conf <file>       Alternative configuration file

 --verbose           Be verbose or quiet. Default mode set in the configuration file.
 --quiet

 --css-only          Only minify and bundle CSS or JS rather than the default.
 --js-only

 --help              This help message
 -h

 --license           Print the software license.
 -l

 -v                  Print the version number

END_HELP;

}
