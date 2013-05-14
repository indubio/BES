#!/usr/bin/env python
# -*- coding: UTF-8 -*-

"""
allgemeine Hilfetools und Klassen
"""

import tempfile
import shutil

class TempDir(object):
    """
    Klasse zur Verwaltung Temporärer Verzeichnisse
    https://bitbucket.org/kfekete/tempdir/
    """
    def __init__(self, suffix="", prefix="bestemp_", basedir=None):
        self.name = tempfile.mkdtemp(suffix=suffix, prefix=prefix, dir=basedir)

    def __del__(self):
        if "name" in self.__dict__:
            self.__exit__(None, None, None)

    def __enter__(self):
        return self

    def __exit__(self, *errstuff):
        return self.dissolve()

    def dissolve(self):
        if self.name:
            shutil.rmtree(self.name)
            self.name = ""

if __name__ == '__main__':
    pass
