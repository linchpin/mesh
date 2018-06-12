<?php
/**
 * Class Mesh_FileSystem
 *
 * Since 1.3
 */

namespace Mesh;

/**
 * Class FileSystem
 * @package Mesh
 */
class FileSystem {

	/**
	 * Scan directory for files.
	 *
	 * @param string $path          Path to file.
	 * @param null   $extensions    Allow file extensions.
	 * @param int    $depth         Depth to search within directory structure.
	 * @param string $relative_path Use relative paths.
	 *
	 * @return array|bool
	 */
	public static function scandir( $path, $extensions = null, $depth = 0, $relative_path = '' ) {
		if ( ! is_dir( $path ) ) {
			return false;
		}

		$_extensions = '';

		if ( $extensions ) {
			$extensions  = (array) $extensions;
			$_extensions = implode( '|', $extensions );
		}

		$relative_path = trailingslashit( $relative_path );

		if ( '/' === $relative_path ) {
			$relative_path = '';
		}

		$results = scandir( $path );
		$files   = array();

		foreach ( $results as $result ) {
			if ( '.' === $result[0] ) {
				continue;
			}
			if ( is_dir( $path . '/' . $result ) ) {
				if ( ! $depth || 'CVS' === $result ) {
					continue;
				}
				$found = self::scandir( $path . '/' . $result, $extensions, $depth - 1, $relative_path . $result );
				$files = array_merge_recursive( $files, $found );
			} elseif ( ! $extensions || preg_match( '~\.(' . $_extensions . ')$~', $result ) ) {
				$files[ $relative_path . $result ] = $path . '/' . $result;
			}
		}

		return $files;
	}
}
