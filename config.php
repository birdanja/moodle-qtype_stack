<?php
/**
 * Simulating Moodle global configuration variables.
 */

$CFG = new stdClass;
$PAGE = new stdClass;

// This is the directory into which you put the scripts.
$CFG->wwwroot = "/var/www/api";
// The base url of the installation
// The server path of the installation.
$CFG->dirroot = realpath(dirname(__FILE__));
// You must have a data directory into which the webserver can write.  Don't put this in your web directory.
$CFG->dataroot = "/var/data/api";

// URL of your web server, e.g.
$CFG->dataurl = "http://localhost/api/";

// You will need to replace this with the compiled version.
$CFG->maximacommand = 'maxima';
$CFG->maximaversion = 'default';
// Once you have compiled maxima you will need to change this.
$CFG->platform     = 'unix';
// $CFG->platform     = 'unix-optimised';

/*
 * These settings are hard-wired here.  See settings.php for more details.
 * You probably don't need to change many of the following.
 */
$CFG->maximalocalfolder = $CFG->dataroot . 'maxima/';

// Type (int).
$CFG->castimeout = 10;
$CFG->casdebugging = 1;
$CFG->casresultscache = 'none';
$CFG->maximalibraries = '';
$CFG->serveruserpass = '';
//$CFG->

$CFG->plotcommand = "gnuplot";
$CFG->ajaxvalidation = 0;
$CFG->replacedollars = false;

$CFG->questionsimplify = 1;
$CFG->assumepositive = 0;
$CFG->assumereal = 0;
$CFG->prtcorrect = '';
$CFG->prtpartiallycorrect = '';
$CFG->prtincorrect = '';
$CFG->multiplicationsign = 'dot';
$CFG->sqrtsign = 1;
$CFG->complexno = 'i;';
$CFG->inversetrig = 'cos-1';
$CFG->matrixparens = "[";

$CFG->inputtype = 'algebraic';
$CFG->inputboxsize = 15;
$CFG->inputstrictsyntax = 1;
$CFG->inputinsertstars = 0;
$CFG->inputforbidwords = '';
$CFG->inputforbidfloat = 0;
$CFG->inputrequirelowestterms = 1;
$CFG->inputcheckanswertype = 1;
$CFG->inputmustverify = 1;
$CFG->inputshowvalidation = 1;

$CFG->stackmaximaversion = "2013060300";
$CFG->version = "2013070900";

// Do not change this setting.
$CFG->mathsdisplay = 'api';

$GLOBALS['CFG'] =& $CFG;

define('MOODLE_INTERNAL', true);
define('PARAM_RAW', 'raw');
define('PARAM_PLUGIN', true);

// Do not change this variable.
define('MINIMAL_API', true);
