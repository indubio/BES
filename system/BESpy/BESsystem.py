#!/usr/bin/env python
# -*- coding: UTF-8 -*-

"""
BES system connector
"""

import os
import ConfigParser
import email
import MySQLdb

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

class ReportProperties (object):
    def __init__(self, reportname):
        self.__reportname = reportname
        conf = BESConfig()

        # connect to BES DB
        mysql  = MySQLdb.connect(
            conf.get('mysql', 'dbhost'),
            conf.get('mysql', 'user'),
            conf.get('mysql', 'pass'),
            conf.get('mysql', 'dbname'),
            cursorclass=MySQLdb.cursors.DictCursor
            )
        cursor = mysql.cursor()
        sql = "set character set utf8"
        cursor.execute(sql)
        sqlquery = """
            select * from reports
            where name = '%s'
        """ % (self.__reportname)
        cursor.execute(sqlquery)
        self.__propertiesdata = cursor.fetchone()
        self.__cc_recipient = []
        sqlquery = """
            select report_recipients.*,
                user.familienname as lastname,
                user.vorname as firstname,
                user.email as email
            from report_recipients
            left outer join user on (user.ID = report_recipients.userID)
            where reportID = '%s' and inactive = '0'
        """ % (self.__propertiesdata['ID'])
        cursor.execute(sqlquery)
        for recipient in cursor.fetchall():
            self.__cc_recipient.append(email.utils.formataddr((
                ", ".join((
                    recipient['lastname'],
                    recipient['firstname']
                    )),
                recipient['email']
                )))
        cursor.close()
        mysql.close()

    def recipient(self):
        return self.__propertiesdata['mail_to']

    def recipients_CC(self):
        return self.__cc_recipient

    def sender(self):
        return self.__propertiesdata['mail_from']

    def mailsubject(self):
        return self.__propertiesdata['mail_head']

    def mailbody(self, bodytype='plain'):
        if bodytype == 'plain':
            return self.__propertiesdata['mail_body_plain']
        elif bodytype == 'html':
            return self.__propertiesdata['mail_body_html']
        else:
            self.__propertiesdata['mail_body_plain']

def getBES_SQLConnection():
    pass

if __name__ == '__main__':
    pass
