<?

/*
 * (c) 2004-2007 Linbox / Free&ALter Soft, http://linbox.com
 * (c) 2007 Mandriva, http://www.mandriva.com
 *
 * $Id: general.php 26 2007-10-17 14:48:41Z nrueff $
 *
 * This file is part of Mandriva Management Console (MMC).
 *
 * MMC is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * MMC is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MMC; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

#require("../../../graph/navbartools.inc.php");
require("../../../includes/PageGenerator.php");
require("../../../includes/config.inc.php");
require("../../../includes/i18n.inc.php");
require("../../../includes/acl.inc.php");
require("../../../includes/session.inc.php");

require_once('../../../modules/msc/includes/qactions.inc.php');
require_once('../../../modules/msc/includes/mirror_api.php');
require_once('../../../modules/msc/includes/machines.inc.php');
require_once('../../../modules/msc/includes/widgets.inc.php');
$machine = null;
$group = null;
if ($_GET['name']) {
    $machine = getMachine(array('hostname'=>$_GET['name'])); // should be changed in uuid
} elseif ($_GET['gid']) {
    require_once("../../../modules/dyngroup/includes/data_access.php");
    require_once("../../../modules/dyngroup/includes/utilities.php");
    require_once("../../../modules/dyngroup/includes/xmlrpc.php");
    require_once("../../../modules/dyngroup/includes/request.php");
    require_once("../../../modules/dyngroup/includes/result.php");
    require_once("../../../modules/dyngroup/includes/dyngroup.php");
    
    $group = new Stagroup($_GET['gid']);
}

require_once("../../../modules/msc/includes/package_api.php");
if ($machine) {
    $label = new RenderedLabel(3, sprintf(_T('These packages can by installed on %s', 'msc'), $machine->hostname));
} else {
    $label = new RenderedLabel(3, sprintf(_T('These packages can by installed on %s', 'msc'), $group->getName()));
}
$label->display();

$a_packages = array();
$a_pversions = array();
$a_css = array();
$params = array();

if (!$_GET["start"]) { $_GET["start"] = 0; }
if (!$_GET["end"]) { $_GET["end"] = 10; }

$filter = $_GET["filter"];

# TODO : decide what we want to do with groups : do we only get the first machine local packages
foreach (advGetAllPackages($machine->hostname, $filter, $_GET["start"], $_GET["end"]) as $c_package) {
    $package = to_packageApi($c_package[0]);
    $type = $c_package[1];
    $a_packages[] = $package->label;
    $a_pversions[] = $package->version;
    if ($machine) {
        $params[] = array('pid'=>$package->id, 'name'=>$machine->hostname, 'from'=>'base|computers|msctabs|tablogs');
    } else {
        $params[] = array('pid'=>$package->id, 'gid'=>$group->id, 'from'=>'base|computers|msctabs|tablogs');
    }
    if ($type==0) {
        $a_css[] = 'primary_list';
    } else {
        $a_css[] = 'secondary_list';
    }
}

$count = advCountAllPackages($machine->hostname, $filter);

$n = new OptimizedListInfos($a_packages, _T("Package"));
$n->addExtraInfo($a_pversions, _T("Version"));
$n->setCssClasses($a_css);
$n->setParamInfo($params);
$n->setItemCount($count);
$n->setNavBar(new AjaxNavBar($count, $filter));
$n->start = 0;
$n->end = $count;
                        

$n->addActionItem(new ActionPopupItem(_T("Launch", "msc"),"start_tele_diff", "start", "msc", "base", "computers"));
$n->addActionItem(new ActionPopupItem(_T("Details", "msc"),"package_detail", "detail", "msc", "base", "computers"));

$n->display();
       

?>
<style>
.primary_list { }
.secondary_list {
    background-color: #e1e5e6 !important;
}
li.detail a {
        padding: 3px 0px 5px 20px;
        margin: 0 0px 0 0px;
        background-image: url("modules/msc/graph/images/actions/info.png");
        background-repeat: no-repeat;
        background-position: left top;
        line-height: 18px;
        text-decoration: none;
        color: #FFF;
}

</style>


