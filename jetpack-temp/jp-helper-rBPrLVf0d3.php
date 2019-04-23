<?php /* JPR Helper Script */
define( 'JP_EXPIRES', 1545588683 );
define( 'JP_SECRET', 'uvhbsN7nTENTFGR1xkQiE58giK1LWbhA' );
ini_set( 'error_reporting', 0 );

// Error codes
define( 'COMMS_ERROR',        128 );
define( 'MYSQLI_ERROR',       129 );
define( 'MYSQL_ERROR',        130 );
define( 'NOT_FOUND_ERROR',    131 );
define( 'READ_ERROR',         132 );
define( 'INVALID_TYPE_ERROR', 133 );
define( 'MYSQL_INIT_ERROR',   134 );
define( 'CREDENTIALS_ERROR',  135 );
define( 'WRITE_ERROR',        136 );

// Ported from wp-includes/compat.php so we don't have to load WP for things that don't need it
if ( ! function_exists( 'hash_equals' ) ) :
/**
	* Timing attack safe string comparison
	*
	* Compares two strings using the same time whether they're equal or not.
	*
	* This function was added in PHP 5.6.
	*
	* Note: It can leak the length of a string when arguments of differing length are supplied.
	*
	* @since 3.9.2
	*
	* @param string $a Expected string.
	* @param string $b Actual, user supplied, string.
	* @return bool Whether strings are equal.
	*/
function hash_equals( $a, $b ) {
		$a_length = strlen( $a );
		if ( $a_length !== strlen( $b ) ) {
				return false;
		}
		$result = 0;

		// Do not attempt to "optimize" this.
		for ( $i = 0; $i < $a_length; $i++ ) {
				$result |= ord( $a[ $i ] ) ^ ord( $b[ $i ] );
		}

		return $result === 0;
}
endif;

// Added in PHP 5.3; stub it out if we don't have it
if ( ! function_exists( 'json_last_error' ) ) :
function json_last_error() {
	return "JSON error information not available due to PHP version";
}
endif;

// Unpack arguments; support CLI or web.
$is_cli = ( 'cli' === php_sapi_name() );
if ( $is_cli ) {
	if ( count( $argv ) !== 3 ) {
		fatal_error( COMMS_ERROR, 'Invalid args', 400 );
	}

	list( $script, $action, $base64_args ) = $argv;
} else {
	$action      = $_POST['action'];
	$base64_args = $_POST['args'];
	$salt        = $_POST['salt'];
	$signature   = $_POST['signature'];
}

$json_args = base64_decode( $base64_args );

if ( ! $is_cli ) {
	// Check signature.
	if ( ! authenticate( $action, $json_args, $salt, $signature ) ) {
		fatal_error( COMMS_ERROR, 'Forbidden', 403 );
	}	
}

// Execute action.
$args = (array)json_decode( $json_args );
jpr_action( $action, $args );
exit( 0 );

function fatal_error( $code, $message, $http_code = 200 ) {
	global $is_cli;

	if ( $is_cli ) {
		fwrite( STDERR, "\n" . json_encode( array(
			'code'    => $code,
			'message' => $message,
		) ) . "\n" );
		die( $code );
	} else {
		header( 'X-VP-Ok: 0', true, $http_code );
		header( 'X-VP-Error-Code: ' . $code );
		header( 'X-VP-Error: ' . base64_encode( $message ) );
		exit;
	}
}

function success_header() {
	global $is_cli;

	if ( ! $is_cli ) {
		header( 'X-VP-Ok: 1', true );
	}
}

function authenticate( $action, $json_args, $salt, $incoming_signature ) {
	$to_sign   = "{$action}:{$json_args}:{$salt}";
	$signature = hash_hmac( 'sha1', $to_sign, JP_SECRET );

	return hash_equals( $signature, $incoming_signature );
}

function jpr_action( $action, $args ) {
	$actions = array(
		'db_results'       => 'action_db_results',
		'db_upload'        => 'action_db_upload',
		'db_import'        => 'action_db_import',
		'ls'               => 'action_ls',
		'stat'             => 'action_stat',
		'test'             => 'action_test',
		'info'             => 'action_info',
		'cleanup_helpers'  => 'action_cleanup_helpers',
		'cleanup_restore'  => 'action_cleanup_restore',
		'walk'             => 'action_walk',
		'flush'            => 'action_flush',
		'trigger_jp_sync'  => 'action_trigger_jp_sync',
		'delete_tree'      => 'action_delete_tree',
		'get_active_theme' => 'action_get_active_theme',
	);

	if ( empty( $actions[ $action ] ) ) {
		fatal_error( COMMS_ERROR, 'Invalid method', 405 );
	}

	call_user_func( $actions[ $action ], $args );
}

function localize_path( $path ) {
	return preg_replace( '/^{\$ABSPATH\}/', dirname( __DIR__ ), $path );
}

function load_wp( $with_plugins = false ) {
	if ( ! defined( 'WP_INSTALLING' ) && ! $with_plugins ) {
		define( 'WP_INSTALLING', true );
	}

	$wp_directory = dirname( __DIR__ );
	$wp_load_path = $wp_directory . '/wp-load.php';
	if ( ! file_exists( $wp_load_path ) ) {
		fatal_error( CREDENTIALS_ERROR, "Could not find WordPress in {$wp_directory}" );
	}

	if ( ! is_readable( $wp_load_path ) ) {
		fatal_error( CREDENTIALS_ERROR, "Can not read wp-load.php in {$wp_directory}" );
	}

	require_once( $wp_load_path );
}

function load_wpdb( ) {
	if ( ! defined( 'IS_PRESSABLE' ) || ! IS_PRESSABLE ) {
		return load_wp();
	}

	global $wpdb;
	
	$wp_directory = dirname( __DIR__ );
	$wp_config_path = $wp_directory . '/wp-config.php';
	
	if ( ! file_exists( $wp_config_path ) ) {
		error_log("wp-config not where we wanted it...", 0);
		load_wp();
		return;
	}

	if ( ! is_readable( $wp_config_path ) ) {
		error_log("wp-config not readable...", 0);
		load_wp();
		return;
	}

	if ( ! defined( 'WP_INSTALLING' ) ) {
		define( 'WP_INSTALLING', true );
	}

	require_once $wp_config_path;

	if ( defined( 'IS_PRESSABLE' ) && IS_PRESSABLE ) {
		$wp_directory = $wp_directory . '/__wp__';
	}
	
	try {
		require_once $wp_directory . '/wp-includes/formatting.php';
		require_once $wp_directory . '/wp-includes/functions.php';
		require_once $wp_directory . '/wp-includes/kses.php';
		require_once $wp_directory . '/wp-includes/pluggable.php';
		require_once $wp_directory . '/wp-includes/plugin.php';
		require_once $wp_directory . '/wp-includes/wp-db.php';
		require_once $wp_directory . '/wp-includes/class-wp-error.php';
	} catch (Exception $e) {
		error_log( 'Caught exception: ' . $e->getMessage() );
		load_wp();
		return;
	}
	
	
	if( ! function_exists( 'is_multisite' ) ) {
		function is_multisite () {
			return false;
		}
	}
	
	if( ! function_exists( '__' ) ) {
		function __ ( $text ) {
			return $text;
		}
	}
	
	$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
}

function encode_json_with_check( $obj ) {
	$json_options = 0;
	if ( defined( 'JSON_PARTIAL_OUTPUT_ON_ERROR' ) ) {
		// since PHP 5.5.0; allows us to handle more weird characters without completely failing
		// since PHP 5.4.0; gives us better output for some unicode characters that don't seem to escape otherwise
		$json_options = JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_UNESCAPED_UNICODE;
	} elseif ( defined( 'JSON_UNESCAPED_UNICODE' ) ) {
		// since PHP 5.4.0; gives us better output for some unicode characters that don't seem to escape otherwise
		$json_options = JSON_UNESCAPED_UNICODE;
	}

	$json = json_encode( $obj, $json_options );
	if ( false === $json ) {
		fatal_error( COMMS_ERROR, 'JSON error: ' . json_last_error() );
	}

	return $json;
}

function send_json_with_check( $obj, $with_newline = true ) {
	$json = encode_json_with_check( $obj );

	echo $json;
	if ( $with_newline ) {
		echo "\n";
	}
}

function action_test( $args ) {
	success_header();
	echo json_encode( array( 'ok' => true ) );
	exit;
}

function action_flush( $args ) {
	load_wp();

	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();

		success_header();
		echo json_encode( array( 'ok' => true ) );
		exit;
	}

	fatal_error( COMMS_ERROR, 'wp_cache_flush() not loaded' );
}

function action_trigger_jp_sync( $args ) {
	load_wp( true ); // need plugins so we get the JP functions

	if ( is_callable( array( 'Jetpack_Sync_Actions', 'do_full_sync' ) ) ) {
		Jetpack_Sync_Actions::do_full_sync();

		success_header();
		echo json_encode( array( 'ok' => true ) );
		exit;
	}

	fatal_error( COMMS_ERROR, 'Jetpack_Sync_Actions::do_full_sync() not loaded' );
}

function action_info( $args ) {
	load_wp();
	global $wpdb, $wp_version;

	// get installed themes.
	$themes = array();
	$current_theme = wp_get_theme();
	foreach ( wp_get_themes() as $key => $theme ) {
		$themes[ $key ] = array(
			'Name' => $theme['Name'],
			'Version' => $theme['Version'],
			'Author' => $theme->get( 'Author' ), // use get() to get the raw value; array access uses display() not get()
			'AuthorURI' => $theme->get( 'AuthorURI'),
			'path' => $theme->get_stylesheet_directory() . '/style.css',
			'status' => $theme['Name'] === $current_theme['Name'] ? 'active': 'inactive',
		);
	}

	// get installed plugins.
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugins = get_plugins();

	// post-process so these are by slug too, like themes.
	$plugins_by_slug = array();
	foreach ( $plugins as $path => $plugin ) {
		if ( false === strpos( $path, '/' ) ) {
			$slug = explode( '.php', $path );
			$slug = $slug[0];
		} else {
			$slug = explode( '/', $path );
			$slug = $slug[0];
		}
		$plugins_by_slug[ $slug ] = $plugin;
		$plugins_by_slug[ $slug ]['path'] = WP_PLUGIN_DIR . '/' . $path;
		$plugins_by_slug[ $slug ]['status'] = is_plugin_active( $path ) ? 'active' : 'inactive';
	}

	success_header();
	echo send_json_with_check( array(
		'wp_version' => $wp_version,
		'php_version' => phpversion(),
		'locale' => get_locale(),
		'table_prefix' => $wpdb->prefix,
		'themes' => $themes,
		'plugins' => $plugins_by_slug,
	), false );
}

function db_query( $sql ) {
	global $wpdb;
	
	if( ! is_object( $wpdb ) ) {
		load_wpdb();
	}

	if ( ! $wpdb->dbh ) {
		fatal_error( MYSQL_INIT_ERROR, 'MySQL not initialized' );
	}

	if ( $wpdb->use_mysqli ) {
		$result = mysqli_query( $wpdb->dbh, $sql, MYSQLI_USE_RESULT );
		if ( ! $result ) {
			fatal_error( MYSQLI_ERROR, mysqli_error( $wpdb->dbh ) );
		}
	} else {
		$result = mysql_unbuffered_query( $sql, $wpdb->dbh );
		if ( ! $result ) {
			fatal_error( MYSQL_ERROR, mysql_error( $wpdb->dbh ) );
		}
	}

	return $result;
}

function action_db_results( $args ) {
	global $wpdb;

	$args = array_merge( array(
		'query' => null,
	), $args );

	$result = db_query( $args['query'] );

	$fields = array();
	$field_count = ( $wpdb->use_mysqli ? mysqli_num_fields( $result ) : mysql_num_fields( $result ) );
	for ( $i = 0; $i < $field_count; $i++ ) {
		$field = ( $wpdb->use_mysqli ? mysqli_fetch_field( $result ) : mysql_fetch_field( $result, $i ) );
		$fields []= base64_encode( $field->name );
	}
	success_header();
	send_json_with_check( $fields );

	do {
		$row = ( $wpdb->use_mysqli ? mysqli_fetch_array( $result, MYSQLI_NUM ) : mysql_fetch_array( $result ) );
		if ( is_array( $row ) ) {
			$encoded_row = array();
			for ( $i = 0; $i < count( $row ); $i++ ) {
				$encoded_row []= base64_encode( $row[ $i ] );
			}
			send_json_with_check( $encoded_row );
		}
	} while ( is_array( $row ) );

	if ( $wpdb->use_mysqli ) {
		@mysqli_free_result( $result );
	} else {
		@mysql_free_result( $result );
	}
}

function action_db_upload( $args ) {
	$args = array_merge( array(
		'sql' => null,
	), $args );

	foreach ( explode( ";\n", $args['sql'] ) as $line ) {
		$line = trim( $line );
		if ( empty( $line ) ) {
			continue;
		}

		db_query( $line );
	}

	success_header();
	echo "Success\n";
}

function action_db_import( $args ) {
	if ( empty( $args['importPath'] ) ) {
		fatal_error( COMMS_ERROR, 'Invalid path' );
	}

	$import_path = localize_path( $args['importPath'] );

	if ( ! file_exists( $import_path ) ) {
		fatal_error( NOT_FOUND_ERROR, "File not found: {$import_path}" );
	}

	$handle = fopen( $import_path, 'rb' );
	if ( false === $handle ) {
		fatal_error( READ_ERROR, "Failed to open file {$import_path} for import." );
	}
	$buf = '';

	while ( true ) {
		$read = fread( $handle, 1024 );
		if ( false == $read ) {
			break;
		}
		$buf .= $read;

		while ( false !== strpos( $buf, ";\n" ) ) {
			$split = explode( ";\n", $buf, 2 );
			$line = trim( $split[0] );
			$buf = $split[1];
			if ( empty( $line ) ) {
				continue;
			}

			db_query( $line );
		}
	}

	$buf = trim( $buf );
	if ( strlen( $buf ) > 0 ) {
		db_query( $buf );
	}

	fclose( $handle );
	success_header();
	echo "Success\n";
}

function clean_pathname_string( $path ) {
	// paths are arbitrary bytes, send them in base-64 so JSON doesn't choke on them
	return base64_encode( $path );
}

function get_ls_entry( &$args, $path, $file ) {
	$full_path = $path . '/' . $file;
	$entry = array(
		'name' => clean_pathname_string( $file ),
	);

	if ( is_link( $full_path ) ) {
		$entry['is_link'] = 1;
		$entry['canonical'] = clean_pathname_string( readlink( $full_path ) );
	} else {
		$entry['canonical'] = clean_pathname_string( realpath( $full_path ) );
	}

	if ( ! is_readable( $full_path ) ) {
		$entry['unreadable'] = true;
	}

	if ( $args['stat'] ) {
		$entry['stat'] = stat( $full_path );
	}

	if ( $args['window'] && floatval( $args['window'] > 1 ) ) {
		// if the caller is windowing the hashes, let them know that the file is unchanged in that window
		// thus, they will not expect the hash to be set for it
		$entry['unchanged'] = ( $entry['stat']['mtime'] < floatval( $args['window'] ) );
	}

	if ( is_dir( $full_path ) ) {
		$entry['is_dir'] = 1;
	} else {
		if ( ! is_array( $args['hashes'] ) ) {
			if ( ! empty( $args['hashes'] ) ) {
				$args['hashes'] = array( $args['hashes'] );
			} else {
				$args['hashes'] = array();
			}
		}

		if ( ! $args['window'] || ! $entry['unchanged'] ) {
			// only hash files if the caller didn't specify a window to do that in, or if the file changed in the window
			foreach ( $args['hashes'] as $algo ) {
				if ( in_array( $algo, hash_algos(), true ) ) {
					$entry[ $algo ] = hash_file( $algo, $full_path );
				}
			}
		}
	}

	return $entry;
}

function locale_safe_basename( $path ) {
	return end( explode( '/', $path ) );
}

function action_stat( $args ) {
	$args = array_merge( array(
		'path'   => '/',
		'hashes' => array(),
		'window' => false,
	), $args );

	$path = localize_path( $args['path'] );

	if ( ! file_exists( $path ) ) {
		fatal_error( NOT_FOUND_ERROR, "File not found: {$path}" );
	}

	$args['stat'] = true;
	$entry = get_ls_entry( $args, dirname( $path ), locale_safe_basename( $path ) );

	success_header();
	send_json_with_check( $entry, false );
	exit;
}

function delete_tree( $path ) {
	$entries_deleted = 1;

	if ( ! is_dir( $path ) ) {
		fatal_error( INVALID_TYPE_ERROR, 'Not a directory: ' . $path );
	}

	foreach ( scandir( $path ) as $name ) {
		if ( $name == '.' || $name == '..' ) {
			continue;
		}

		$child = $path . '/' . $name;
		if ( is_dir( $child ) ) {
			$entries_deleted += delete_tree( $child );
		} else {
			if ( ! @unlink( $child ) ) {
				fatal_error( WRITE_ERROR, "Failed to delete file: {$child}" );
			}
			$entries_deleted++;
		}
	}

	if ( ! @rmdir( $path ) ) {
		fatal_error( WRITE_ERROR, "Failed to delete folder: {$path}" );
	}

	return $entries_deleted;
}

function action_delete_tree( $args ) {
	if ( empty( $args['path'] ) ) {
		fatal_error( INVALID_TYPE_ERROR, 'Invalid path' );
	}

	$path = localize_path( $args['path'] );
	$entries_deleted = delete_tree( $path );

	success_header();
	send_json_with_check( array(
		'entries' => $entries_deleted,
	) );
	exit;
}

function action_ls( $args ) {
	$args = array_merge( array(
		'path'   => '/',
		'hashes' => array(),
		'stat'   => false,
		'window' => false,
	), $args );

	$path = localize_path( $args['path'] );

	if ( ! is_dir( $path ) ) {
		fatal_error( INVALID_TYPE_ERROR, "Not a directory: {$path}" );
	}

	$dh = opendir( $path );
	if ( ! $dh ) {
		fatal_error( READ_ERROR, "Failed to read directory: {$path}" );
	}

	success_header();
	while ( ( $file = readdir( $dh ) ) !== false ) {
		if ( '.' === $file || '..' === $file ) {
			continue;
		}

		$entry = get_ls_entry( $args, $path, $file );

		send_json_with_check( $entry );
	}

	closedir( $dh );
	exit;
}

function action_walk( $args ) {
	$args = array_merge( array(
		'root'   => '/',
		'paths'  => array(),
		'hashes' => array(),
		'stat'   => false,
		'window' => false,
	), $args );

	$paths = $args['paths'];
	$root = localize_path( $args['root'] );
	$soft_limit = 3000;
	$entries = 0;
	$first_path = true;
	success_header();

	if ( substr( $root, -1 ) != '/' ) {
		$root .= '/';
	}

	while ( count( $paths ) > 0 ) {
		$relative_path = array_pop( $paths );
		$absolute_path = $root . $relative_path;

		$path_details = get_ls_entry( $args, dirname( $absolute_path ), locale_safe_basename( $absolute_path ) );
		$path_details['ls'] = $relative_path;
		$path_header = encode_json_with_check( $path_details ) . "\n";
		if ( $first_path ) {
			// First path is always output with no buffering, as soft limits don't apply.
			echo $path_header;
			$path_header = '';
		}

		$dh = opendir( $absolute_path );
		if ( ! $dh ) {
			echo $path_header . json_encode( array( 'error' => 'Failed to read ' . $absolute_path ) ) . "\n";
			continue;
		}

		while ( ( $file = readdir( $dh ) ) !== false ) {
			if ( '.' === $file || '..' === $file ) {
				continue;
			}

			// Apply soft-limits on all but the first path.
			$entries++;
			if ( ! $first_path && $entries > $soft_limit ) {
				closedir( $dh );
				$entry_buffer = array();
				return;
			}

			$entry = get_ls_entry( $args, $absolute_path, $file );

			// Keep track of paths to auto-recurse into
			// not symlinks or unreadable dirs
			$is_readable = ! ( isset( $entry['unreadable'] ) && $entry['unreadable'] );
			if ( $entry['is_dir'] && $is_readable && ! $entry['is_link'] && count( $paths ) < 1000 && $entries < $soft_limit ) {
				$explore_path = empty( $relative_path ) ? $file : $relative_path . '/' . $file;
				if ( ! in_array( $explore_path, $paths ) ) {
					array_push( $paths, $explore_path );
				}
			}

			// Buffer all but the first path, to help enforce soft-limits
			if ( $first_path ) {
				send_json_with_check( $entry );
			} else{
				array_push( $entry_buffer, $entry );
			}
		}

		closedir( $dh );

		// If buffering, output entry buffer; finished a directory before hitting a soft-limit.
		if ( ! $first_path ) {
			echo $path_header;
			foreach ( $entry_buffer as $entry ) {
				send_json_with_check( $entry );
			}
		}

		$first_path = false;
		$entry_buffer = array();

		if ( $entries > $soft_limit ) {
			exit;
		}
	}
}

function action_cleanup_restore( $args ) {
	$files   = glob( localize_path( '{$ABSPATH}/vp-sql-upload-*.sql' ) );
	$deleted = 0;

	foreach ( $files as $file ) {
		if ( @unlink( $file ) ) {
			$deleted++;
		}
	}

	success_header();
	echo json_encode( array(
		'found'   => count( $files ),
		'deleted' => $deleted,
	) );
}

function action_cleanup_helpers( $args ) {
	$args = array_merge( array(
		'ageThreshold' => 86400, // one day
	), $args );

	$dir = opendir( __DIR__ );
	if ( ! is_resource( $dir ) ) {
		fatal_error( READ_ERROR, 'Failed to open directory: ' . __DIR__ );
	}

	$self = realpath( __FILE__ );

	// Find leftover old helpers and delete them.
	$helpers_deleted = 0;
	$helpers_found = 0;
	while ( false !== ( $entry = readdir( $dir ) ) ) {
		// Skip files that don't look like helpers.
		if ( 0 != strncmp( $entry, 'jp-helper-', 10 ) ) {
			continue;
		}

		$helpers_found++;
		$full_path = realpath( implode( '/', array( __DIR__, $entry ) ) );

		// Skip entries that aren't files, or are myself.
		if ( $full_path == $self || ! is_file( $full_path ) ) {
			continue;
		}

		// Only delete helpers over the threshold
		$age = time() - filemtime( $full_path );
		if ( $age < $args['ageThreshold'] ) {
			continue;
		}

		// Check file header
		if ( file_get_contents( $full_path, false, NULL, 0, 29 ) !== '<?php /* JPR Helper Script */' ) {
			continue;
		}

		// Finally delete.
		$helpers_deleted++;
		unlink( $full_path );
	}

	success_header();
	echo json_encode( array(
		'found'   => $helpers_found,
		'deleted' => $helpers_deleted,
	 ) );
}

function action_get_active_theme() {
	load_wp();

	$theme = wp_get_theme();

	if ( $theme ) {
		success_header();
		echo json_encode( array(
			'slug' => $theme->get_template(),
			'path' => $theme->get_theme_root(),
		) );
	} else {
		fatal_error( READ_ERROR, 'wp_get_theme() failed' );
	}
}
