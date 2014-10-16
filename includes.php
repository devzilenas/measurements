<?
set_include_path(get_include_path() . PATH_SEPARATOR . 'class');
set_include_path(get_include_path() . PATH_SEPARATOR . 'lib');

# -------------- CONFIG -------------------
include 'config.inc.php'                   ;
include 'db.inc.php'                       ;

# -----------------------------------------
# -------------- LIB ----------------------
# -----------------------------------------
# -------------- DBOBJS -------------------
include_once 'dbobjs/dbobj.class.php'      ;
include_once 'dbobjs/objset.class.php'     ;
include_once 'dbobjs/objset_html.class.php';
include_once 'dbobjs/filter.class.php'     ;
include_once 'dbobjs/sql_filter.class.php' ;
# -------------- ITEMS LIST ---------------
include_once 'dbobjs/html/items_list.html.php';
include_once 'dbobjs/req/list.req.php'     ;
# -------------- LANGUAGE ----------------- 
include_once 'lang/language.class.php'     ;
include_once 'lang/dict/lt.inc.php'        ;
include_once 'lang/dict/ru.inc.php'        ;
include_once 'lang/dict/de.inc.php'        ; 
# -------------- LOGGER ------------------- 
include_once 'sys/logger/error.inc.php'    ;
include_once 'sys/logger/logger_html_block.class.php';
# -------------- HTML ---------------------
include_once 'html/form.class.php'         ;
# -------------- SESSION ------------------
include_once 'sys/session.class.php'       ;
include_once 'sys/session.inc.php'         ;
# -------------- REQUEST ------------------
include_once 'sys/request.class.php'       ;
# -------------- USER ---------------------
include_once 'auth/crypt.class.php'        ;
include_once 'auth/user.class.php'         ;
include_once 'auth/login.class.php'        ;
include_once 'auth/sys/user_session.class.php';
include_once 'auth/login_html_block.class.php';

# -------------- CALENDAR -----------------
include_once 'calendar/calendar_date.class.php';

# -------------- CHART --------------------
include_once 'chart/chart.class.php'       ;
include_once 'chart/chart_bar.class.php'       ;
include_once 'chart/chart_html_block.class.php';

include_once 'lib.inc.php'                 ;

# -----------------------------------------
# ------------- INTERFACE -----------------
# -----------------------------------------
include 'req.interface.php';


# -----------------------------------------
# -------------- SETUP --------------------
# -----------------------------------------
$DBOBJS = array("Temperature", "Heartrate", "Height", "Weight", "Person", "Measurement");
foreach($DBOBJS as $name) include 'dbobjs/'.strtolower(c2u($name)).'.class.php';
# -------------- REQUEST ------------------
include 'req.class.php'                    ;
# -------------- HTML ---------------------
include 'html_block.class.php'             ;
