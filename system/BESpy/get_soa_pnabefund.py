#!/usr/bin/env python
# -*- coding: UTF-8 -*-
"""
Programm name : get_soa_pnabefund.py
Version       : 0.1
Date          : 2016-09-19
Author        : Steffen Eichhorn [mail@indubio.org]
License       : GPL
Description   : CSV export des PNA Befundes aus Cerner Soarian
Changelog     :
"""

import os
from optparse import OptionParser
import BESsystem
import SOADB


def main(args):
    conf = BESsystem.BESConfig()
    soaDBconnection = SOADB.connection(
        host = conf.get('SOASQLSP', 'host'),
        port = conf.get('SOASQLSP', 'port'),
        database = conf.get('SOASQLSP', 'database'),
        user = conf.get('SOASQLSP', 'user'),
        passwd = conf.get('SOASQLSP', 'pass')
    )
    outfile = ""
    if len(args) != 1:
        curr_path = os.path.dirname(os.path.realpath(__file__))
        outfile = (os.path.join(curr_path,'soa_pnabefund.csv'))
    else:
        outfile=args[0]

    soaDBconnection.Export_SP_Result(outfile, "KEvB_GetPNAInfo")
    print "output ->", outfile

if __name__ == '__main__':
    parser = OptionParser("%prog csvfile [options]")
    (progoptionen, progargs) = parser.parse_args()
    main(progargs)
