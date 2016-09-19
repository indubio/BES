#!/usr/bin/env python
# -*- coding: UTF-8 -*-
"""
Programm name : get_id_ops.py
Version       : 0.1
Date          : 2016-09-19
Author        : Steffen Eichhorn [mail@indubio.org]
License       : GPL
Description   : CSV export der OPS-Codes aus der ID DB
Changelog     :
"""

import os
from optparse import OptionParser
import BESsystem
import IDDB


def main(args):
    conf = BESsystem.BESConfig()
    iddbconnection = IDDB.connection(
        user = conf.get('idsql', 'user'),
        dc = conf.get('idsql', 'dc'),
        host = conf.get('idsql', 'host'),
        session = conf.get('idsql', 'session'),
        passwd = conf.get('idsql', 'pass')
    )
    outfile = ""
    if len(args) != 1:
        curr_path = os.path.dirname(os.path.realpath(__file__))
        outfile = (os.path.join(curr_path,'psych_ops_codes.csv'))
    else:
        outfile=args[0]
    iddbconnection.Export_OPS_Codes(outfile)
    print "output ->", outfile

if __name__ == '__main__':
    parser = OptionParser("%prog csvfile [options]")
    (progoptionen, progargs) = parser.parse_args()
    main(progargs)
