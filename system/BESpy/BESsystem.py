#!/usr/bin/env python
# -*- coding: UTF-8 -*-

"""
BES system connector
"""

import os
import ConfigParser

class BESConfig:
    def __init__(self, defaults=None):
        # get config file path
        currentDir = os.path.dirname(os.path.realpath(__file__))
        parentDir = os.path.split(currentDir)[0]
        parentDir = os.path.split(parentDir)[0]
        # read config
        self.Config = ConfigParser.RawConfigParser()
        self.Config.read(os.path.join(parentDir,'config','system.ini'))

    def get(self, section, option):
        return self.Config.get(section, option)

def getBES_SQLConnection():
    pass

if __name__ == '__main__':
    pass
