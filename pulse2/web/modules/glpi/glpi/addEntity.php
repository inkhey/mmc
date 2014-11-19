<?php
/**
 * (c) 2004-2007 Linbox / Free&ALter Soft, http://linbox.com
 * (c) 2007 Mandriva, http://www.mandriva.com
 *
 * $Id$
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

require("modules/base/computers/localSidebar.php");
require("graph/navbar.inc.php");
require_once("modules/pulse2/includes/locations_xmlrpc.inc.php");

// Receiving form data
if (isset($_POST['name'], $_POST['parent'], $_POST['description'])){
    addEntity($_POST['name'], $_POST['parent'], $_POST['description']);
    if (!isXMLRPCError()) new NotifyWidgetSuccess(_T("The entity has been added successfully.", "glpi"));
}

$p = new PageGenerator(_T("Add entity", 'glpi'));
$p->setSideMenu($sidemenu);
$p->display();

$f = new ValidatingForm();
$f->push(new Table());

$f->add(
    new TrFormElement(_T('Name','glpi'), new InputTpl('name')),
    array("value" => $profile['profilename'],"required" => True)
);

$entities_select = new SelectItem("parent");
$entities = getUserLocations();
    
$entity_list = array();
foreach ($entities as $entity){
    $id = str_replace('UUID', '', $entity['uuid']);
    $entity_list[$id] = $entity['name'];
}
    
$entities_select->setElements(array_values($entity_list));
$entities_select->setElementsVal(array_keys($entity_list));

$f->add(
    new TrFormElement(_T('Parent entity','glpi'), $entities_select),
    array("value" => 1,"required" => True)
);

$f->add(
    new TrFormElement(_T('Description','glpi'), new InputTpl('description')),
    array("value" => $profile['profilename'],"required" => True)
);

$f->pop();
$f->addValidateButton("bconfirm");
$f->display();

?>
