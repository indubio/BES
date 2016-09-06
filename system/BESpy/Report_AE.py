#!/usr/bin/env python
# -*- coding: UTF-8 -*-
"""
Programm name : Report_AE.py
Date          : 2016-06-13
Author        : Steffen Eichhorn [mail@indubio.org]
"""
import os
import shutil
import MySQLdb
import MySQLdb.cursors
import subprocess
import email
import smtplib
from email.mime.text import MIMEText
from datetime import datetime, timedelta
from optparse import OptionParser
from datetime import date, datetime, timedelta
from dateutil.relativedelta import relativedelta

import BESsystem
import utils
import IDDB

def main(progoptions):
    conf = BESsystem.BESConfig()
    # connect to BES DB
    mysql  = MySQLdb.connect(
        conf.get('mysql', 'dbhost'),
        conf.get('mysql', 'user'),
        conf.get('mysql', 'pass'),
        conf.get('mysql', 'dbname'),
        cursorclass = MySQLdb.cursors.DictCursor
    )
    cursor = mysql.cursor()
    sql = "set character set utf8"
    cursor.execute(sql)

    #date_lastreport = datetime.strptime('2016-06-13', '%Y-%m-%d')
    get_date = str(date.today() + relativedelta(days=-1))

    # get admitted patients
    sql_admitted = """
        SELECT fall.*, user.username as behandler_char, f_psy_stationen.option as station_c_char FROM fall
        LEFT OUTER JOIN user ON ( user.ID = fall.behandler)
        LEFT OUTER JOIN f_psy_stationen ON (f_psy_stationen.ID = fall.station_c)
        WHERE `cancelled`=0 AND
        (str_to_date(concat(aufnahmedatum,' ',aufnahmezeit),'%%d.%%m.%%Y %%H:%%i') >= '%s 00:00')
        ORDER BY familienname ASC
        """ % (get_date)

    # get released patients
    sql_released = """
        SELECT fall.*, user.username as behandler_char, f_psy_stationen.option as station_c_char FROM fall
        LEFT OUTER JOIN user ON ( user.ID = fall.behandler)
        LEFT OUTER JOIN f_psy_stationen ON (f_psy_stationen.ID = fall.station_c)
        WHERE `cancelled`=0 AND
        (str_to_date(`entlassungsdatum`, '%%d.%%m.%%Y') >= '%s')
        ORDER BY familienname ASC
        """ % (get_date)

    html_msg = """
    <html>
        <head>
        <style>
        body {
            font-family:calibri;
            font-size:11pt;
        }
        table {
            padding: 2px;
        }
        </style>
        </head>
        <body>
    """
    cursor.execute(sql_admitted)
    html_msg = html_msg + "<p>Aufnahmen seit dem " + get_date + "</p>"
    html_msg = html_msg + "<table>"
    for row in cursor.fetchall():
        html_msg = html_msg + "<tr><td>"
        html_msg = html_msg + "</td><td>".join((row['familienname'], row['vorname'], row['geburtsdatum'], row['aufnahmenummer'], row['station_c_char'], str(row['behandler_char'])))
        html_msg = html_msg + "</td></tr>"
    html_msg = html_msg + "</table>"
    cursor.execute(sql_released)
    html_msg = html_msg + "<p>Entlassungen seit dem " + get_date + "</p>"
    html_msg = html_msg + "<table>"
    for row in cursor.fetchall():
        html_msg = html_msg + "<tr><td>"
        html_msg = html_msg + "</td><td>".join((row['familienname'], row['vorname'], row['geburtsdatum'], row['aufnahmenummer'], row['station_c_char'], str(row['behandler_char'])))
        html_msg = html_msg + "</td></tr>"
    html_msg = html_msg + "</table>"

    html_msg = html_msg + """
    </body>
    </html>
    """

    ## mail report
    repprop  = BESsystem.ReportProperties('PSY-AE-Report')
    TO = repprop.recipient()
    CC = repprop.recipients_CC()
    FROM = repprop.sender()
    msg = email.MIMEMultipart.MIMEMultipart()
    msg['Content-Type'] = "text/html; charset=utf-8"
    msg["From"] = FROM
    msg["To"] = TO
    msg["Cc"] = ",".join(CC)
    msg["Subject"] = "%s %s" % (repprop.mailsubject(), str(datetime.now().date()))
    msg['Date'] = email.Utils.formatdate(localtime=True)
#    text = MIMEText(repprop.mailbody(), 'plain')
    html = MIMEText(html_msg, 'html', "utf-8")
    msg.attach(html)
#    msg.attach(text)

    server = smtplib.SMTP('localhost')
    TO = [TO] + CC
    server.sendmail(FROM, TO, msg.as_string())
    server.quit()

if __name__ == '__main__':
    parser = OptionParser("ops-report.py [Optionen]")
    parser.add_option("-f","--foo", help="foo", dest="foo")
    #parser.add_option("-t","--to", help="enddate [YYYY-MM-DD]", dest="todate")
    (progoptions, progargs) = parser.parse_args()
    main(progoptions)
