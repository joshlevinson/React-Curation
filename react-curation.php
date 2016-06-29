<?php
/**
 * Plugin Name: Curation
 * Plugin URI:  http://10up.com
 * Description: Allows curation of posts!
 * Version:     0.1.0
 * Author:      10up
 * Author URI:  http://10up.com
 * License:     GPLv2+
 */

/**
 * Copyright (c) 2015 10up (email : info@10up.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Useful global constants
define( 'TENUP_CURATION_VERSION', '1.0.0' );
define( 'TENUP_CURATION_PATH', dirname( __FILE__ ) . '/' );
define( 'TENUP_CURATION_INC', TENUP_CURATION_PATH . 'includes/' );

define( 'TENUP_CURATION_DEV_MODE', 1 );
define( 'TENUP_CURATION_URL', plugin_dir_url( __FILE__ ) );
define( 'TENUP_CURATION_ASSET_URL', TENUP_CURATION_DEV_MODE ? 'http://localhost:8081' : TENUP_CURATION_URL . '/build' );

require_once TENUP_CURATION_INC . 'Sections/Sections.php';
require_once TENUP_CURATION_INC . 'Sections/Section.php';
require_once TENUP_CURATION_INC . 'admin/admin.php';

require_once TENUP_CURATION_INC . 'helpers.php';
require_once TENUP_CURATION_INC . 'REST/register.php';

\TenUp\Curation\Sections\Sections::add( new \TenUp\Curation\Sections\Section( 'homepage-featured-articles', 4, 'Homepage Featured Articles' ) );
\TenUp\Curation\Sections\Sections::add( new \TenUp\Curation\Sections\Section( 'homepage-latest-news', 4, 'Homepage Latest News' ) );

