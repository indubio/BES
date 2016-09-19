#!/usr/bin/env python
# -*- coding: UTF-8 -*-
"""
Programm name : SOADB.py
Date          : 2016-09-08
Author        : Steffen Eichhorn [mail@indubio.org]
License       : GPL
Description   : abstrahiert den Zugriff auf Daten aus der SQL-Datenbank von Soarian
"""

import os.path
import pyodbc
import datetime
import csv

class connection(object):
    """ID Datenbank Objekt"""
    def __init__(self, *args, **kwargs):
        """
        Stellt die Verbindung zur ID Datenbank her
        host
          string, Host der Datenbank
        database
          string, Datenbank Name
        port
          string,Datenbank Port
        user
          string, Benutzername
        passwd
          string, Benutzerpasswort
        """
        self.__connection_str = ';'.join([
            'DRIVER={FreeTDS}',
            'SERVER=%s' % kwargs['host'],
            'PORT=%s' % kwargs['port'],
            'DATABASE=%s' % kwargs['database'],
            'UID=%s' % kwargs['user'],
            'PWD=%s' % kwargs['passwd'],
            'CLIENTCHARSET=UTF-8',
            'TDS_Version=8.0',
            ''
        ])

    def con(self):
        return pyodbc.connect(self.__connection_str)

    def Export_SP_Result(self, filename, sp_name):
        """
        exportiert als CSV das Ergebnis der übergebenen Stored Procedures
        """
        returnstatus = False
        SQLConn = pyodbc.connect(self.__connection_str)
        SQLCursor = SQLConn.cursor()
        sqlquery = """
            exec ?
            """
        SQLCursor.execute(sqlquery, sp_name)
        # columns = ['AssessmentID', 'CreationTime', 'CaseID', 'FirstName', 'LastName', 'ContactTime', 'FindingAbbr', 'FindingName', 'Value']
        columns = [column[0] for column in SQLCursor.description]

        f_csv = open(filename, 'wt')
        csvwriter = csv.writer(f_csv, delimiter = ';', doublequote = True, quoting=csv.QUOTE_ALL)
        csvwriter.writerow(columns)
        for i in SQLCursor.fetchall():
            csvwriter.writerow(i)
        f_csv.close()
        returnstatus = True

        SQLCursor.close()
        SQLConn.close()
        return returnstatus

if __name__ == '__main__':
    pass
