<?php

/**
 * Tiny migrate script for PHP and MySQL.
 *
 * Copyright 2012 Alex Kennberg (https://github.com/kennberg/php-mysql-migrate)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
/**
 * Initialize your database parameters:
 *    cp config.php.sample config.php
 *    vim config.php
 *
 *  The rest is in the usage report.
 */


define('MIGRATE_VERSION_FILE', '.version');
define('MIGRATE_FILE_PREFIX', 'migrate-');
define('MIGRATE_FILE_POSTFIX', '.php');
define('MIGRATIONS_DIR', dirname(__FILE__).'/migrations/');


// Find the latest version or start at 0.
$version = 0;
$f = @fopen(MIGRATE_VERSION_FILE, 'r');
if ($f) {
    $version = intval(fgets($f));
    fclose($f);
}
echo "Current database version is: $version\n";



function query($query) {

    $result = Execute($query);
    if (!$result) {
        echo "Migration failed: " . mysql_error($link) . "\n";
        echo "Aborting.\n";
        Execute('ROLLBACK', $link);

        exit;
    }
    return $result;
}

function get_migrations() {
    // Find all the migration files in the directory and return the sorted.
    $files = array();
    $dir = opendir(MIGRATIONS_DIR);
    while ($file = readdir($dir)) {
        if (substr($file, 0, strlen(MIGRATE_FILE_PREFIX)) == MIGRATE_FILE_PREFIX) {
            $files[] = $file;
        }
    }
    asort($files);
    return $files;
}

function get_version_from_file($file) {
    return intval(substr($file, strlen(MIGRATE_FILE_PREFIX)));
}

if ($_REQUEST['migrate_action'] == 'add') {
    $new_version = $version;

    // Check the new version against existing migrations.
    $migs = get_migrations();
    $last_file = end($migs);
    if ($last_file !== false) {
        $file_version = get_version_from_file($last_file);
        if ($file_version > $new_version)
            $new_version = $file_version;
    }

    // Create migration file path.
    $new_version++;
    $path = MIGRATIONS_DIR . MIGRATE_FILE_PREFIX . sprintf('%04d', $new_version);
    if (!empty($_REQUEST['migrate_name'])) {
        $path .= '-' . nicename($_REQUEST['migrate_name']);
    }
    $path .= MIGRATE_FILE_POSTFIX;

    echo "Adding a new migration script: $path\n";

    $f = @fopen($path, 'w');
    if ($f) {
        fputs($f, "<?php\n\n  doSql(\$query);\n\n");
        fclose($f);
        echo "Done.\n";
    } else {
        echo "Failed.\n";
    }
} else if ($_REQUEST['migrate_action'] == 'migrate') {
    $files = get_migrations();

    // Check to make sure there are no conflicts such as 2 files under the same version.
    $errors = array();
    $last_file = false;
    $last_version = false;
    foreach ($files as $file) {
        $file_version = get_version_from_file($file);
        if ($last_version !== false && $last_version === $file_version) {
            $errors[] = "$last_file --- $file";
        }
        $last_version = $file_version;
        $last_file = $file;
    }
    if (count($errors) > 0) {
        echo "Error: You have multiple files using the same version. " .
        "To resolve, move some of the files up so each one gets a unique version.\n";
        foreach ($errors as $error) {
            echo "  $error\n";
        }
        exit;
    }

    // Run all the new files.
    $found_new = false;
    foreach ($files as $file) {
        $file_version = get_version_from_file($file);
        if ($file_version <= $version) {
            continue;
        }

        echo "Running: $file\n";
        doSql('BEGIN');
        include(MIGRATIONS_DIR . $file);
        doSql('COMMIT');
        echo "Done.\n";

        $version = $file_version;
        $found_new = true;

        // Output the new version number.
        $f = @fopen(MIGRATE_VERSION_FILE, 'w');
        if ($f) {
            fputs($f, $version);
            fclose($f);
        } else {
            echo "Failed to output new version to " . MIGRATION_VERSION_FILE . "\n";
        }
    }

    if ($found_new) {
        echo "Migration complete.\n";
    } else {
        echo "Your database is up-to-date.\n";
    }
}
