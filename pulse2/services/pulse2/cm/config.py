# -*- test-case-name: pulse2.msc.client.tests._config -*-
# -*- coding: utf-8; -*-
#
# (c) 2014 Mandriva, http://www.mandriva.com/
#
# This file is part of Pulse 2, http://pulse2.mandriva.org
#
# Pulse 2 is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Pulse 2 is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Pulse 2; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
# MA 02110-1301, USA.

""" Declaration of config defaults """


from pulse2.cm._config import ConfigReader


class Config(object):
    __metaclass__ = ConfigReader

    class main(object):

        serializer = "json"

    class server(object):

        port = 443
        ssl_key_file = None
        ssl_crt_file = None
        ssl_method = "SSLv3_METHOD"

        endpoints = ["packages", "inventory",]

    class package_api(object):

        mserver = "127.0.0.1"
        mport = 9990
        mmountpoint = "/rpc"
        enablessl = 1
        verifypeer = 0
        localcert = None
        cacert = None

    class scheduler(object):

        db_host = "localhost"
        db_name = "msc"
        db_port = "3306"
        db_user = "msc"
        db_passwd = "msc"

    class inventory(object):
        enablessl = False
        host = "localhost"
        port = 9999

        inscription_lag = 12

    class mmc(object):
        enablessl = True
        host = "localhost"
        port = 7080
        user = "mmc"
        passwd = "s3cr3t"
        ldap_user = "root"
        ldap_passwd = "ldap"




