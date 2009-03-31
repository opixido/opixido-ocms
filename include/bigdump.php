<?php


$linespersession = 300000000;   // Lines to be executed per one import session
$delaypersession = 0;      // You can specify a sleep time in milliseconds after each session
                           // Works only if JavaScript is activated. Use to reduce server overrun

// Allowed comment delimiters: lines starting with these strings will be dropped by BigDump

$comment[]='#';           // Standard comment lines are dropped by default
$comment[]='-- ';
// $comment[]='---';      // Uncomment this line if using proprietary dump created by outdated mysqldump
// $comment[]='/*!';         // Or add your own string to leave out other proprietary things


// Connection character set should be the same as the dump file character set (utf8, latin1, cp1251, koi8r etc.)
// See http://dev.mysql.com/doc/refman/5.0/en/charset-charsets.html for the full list

$db_connection_charset = '';


// *******************************************************************************************
// If not familiar with PHP please don't change anything below this line
// *******************************************************************************************

define ('VERSION','0.27b');
define ('DATA_CHUNK_LENGTH',16384);  // How many chars are read per time
define ('MAX_QUERY_LINES',300);      // How many lines may be considered to be one query (except text lines)
define ('TESTMODE',false);           // Set to true to process the file without actually accessing the database



@ini_set('auto_detect_line_endings', true);
@set_time_limit(0);



// ****************************************************
// START IMPORT SESSION HERE
// ****************************************************
$_REQUEST["start"] = 0;
$_REQUEST["foffset"] = 0;
$_REQUEST["fn"] = 'test.sql';
if (!$error && isset($_REQUEST["start"]) && isset($_REQUEST["foffset"]) && eregi("(\.(sql|gz))$",$_REQUEST["fn"]))
{



$gzipmode = false;

// Start processing queries from $file

  if (!$error)
  { $query="";
    $queries=0;
    $totalqueries=$_REQUEST["totalqueries"];
    $linenumber=$_REQUEST["start"];
    $querylines=0;
    $inparents=false;

// Stay processing as long as the $linespersession is not reached or the query is still incomplete

    while ($linenumber<$_REQUEST["start"]+$linespersession || $query!="")
    { 

// Read the whole next line

      $dumpline = "";
      while (!feof($file) && substr ($dumpline, -1) != "\n") 
      { if (!$gzipmode)
          $dumpline .= fgets($file, DATA_CHUNK_LENGTH);
        else
          $dumpline .= gzgets($file, DATA_CHUNK_LENGTH);
      }
      if ($dumpline==="") break;
      
// Handle DOS and Mac encoded linebreaks (I don't know if it will work on Win32 or Mac Servers)

      $dumpline=ereg_replace("\r\n$", "\n", $dumpline);
      $dumpline=ereg_replace("\r$", "\n", $dumpline);
      
// DIAGNOSTIC
// echo ("<p>Line $linenumber: $dumpline</p>\n");

// Skip comments and blank lines only if NOT in parents

      if (!$inparents)
      { $skipline=false;
        reset($comment);
        foreach ($comment as $comment_value)
        { if (!$inparents && (trim($dumpline)=="" || strpos ($dumpline, $comment_value) === 0))
          { $skipline=true;
            break;
          }
        }
        if ($skipline)
        { $linenumber++;
          continue;
        }
      }

// Remove double back-slashes from the dumpline prior to count the quotes ('\\' can only be within strings)
      
      $dumpline_deslashed = str_replace ("\\\\","",$dumpline);

// Count ' and \' in the dumpline to avoid query break within a text field ending by ;
// Please don't use double quotes ('"')to surround strings, it wont work

      $parents=substr_count ($dumpline_deslashed, "'")-substr_count ($dumpline_deslashed, "\\'");
      if ($parents % 2 != 0)
        $inparents=!$inparents;

// Add the line to query

      $query .= $dumpline;

// Don't count the line if in parents (text fields may include unlimited linebreaks)
      
      if (!$inparents)
        $querylines++;
      
// Stop if query contains more lines as defined by MAX_QUERY_LINES

      if ($querylines>MAX_QUERY_LINES)
      {
        echo ("<p class=\"error\">Stopped at the line $linenumber. </p>");
        echo ("<p>At this place the current query includes more than ".MAX_QUERY_LINES." dump lines. That can happen if your dump file was ");
        echo ("created by some tool which doesn't place a semicolon followed by a linebreak at the end of each query, or if your dump contains ");
        echo ("extended inserts. Please read the BigDump FAQs for more infos.</p>\n");
        $error=true;
        break;
      }

// Execute query if end of query detected (; as last character) AND NOT in parents

      if (ereg(";$",trim($dumpline)) && !$inparents)
      { if (!TESTMODE && !mysql_query(trim($query), $dbconnection))
        { echo ("<p class=\"error\">Error at the line $linenumber: ". trim($dumpline)."</p>\n");
          echo ("<p>Query: ".trim(nl2br(htmlentities($query)))."</p>\n");
          echo ("<p>MySQL: ".mysql_error()."</p>\n");
          $error=true;
          break;
        }
        $totalqueries++;
        $queries++;
        $query="";
        $querylines=0;
      }
      $linenumber++;
    }
  }

// Get the current file position

  if (!$error)
  { if (!$gzipmode) 
      $foffset = ftell($file);
    else
      $foffset = gztell($file);
    if (!$foffset)
    { echo ("<p class=\"error\">UNEXPECTED: Can't read the file pointer offset</p>\n");
      $error=true;
    }
  }

// Print statistics
?>