<?php
/**
 * markergooglemaps
 *
 * Copyright 2011-12 by SCHERP Ontwikkeling <info@scherpontwikkeling.nl>
 *
 * This file is part of markergooglemaps.
 *
 * markergooglemaps is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * markergooglemaps is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * markergooglemaps; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package markergooglemaps
 */
/**
 * @package markergooglemaps
 * @subpackage controllers
 */
require_once dirname(dirname(__FILE__)) . '/markergooglemaps.class.php';
$mgmaps = new MarkerGoogleMaps($modx);
return $mgmaps->initialize('mgr');