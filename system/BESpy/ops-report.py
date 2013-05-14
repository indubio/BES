#!/usr/bin/env python
# -*- coding: UTF-8 -*-
"""
Programm name : ops_report.py
Date          : 2012-10-25
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

import BESsystem
import utils
import IDDB

def gen_codes_csv(startdate, enddate, csv_file):
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
    # get patients in reporting time
    sql = """
        SELECT fall.*, f_psy_stationen.option as Ward FROM fall
        LEFT OUTER JOIN f_psy_stationen ON (f_psy_stationen.ID = fall.station_c)
        WHERE `cancelled` = '0' AND
        str_to_date(`aufnahmedatum`, '%%d.%%m.%%Y') <= '%s' AND
        (str_to_date(`entlassungsdatum`, '%%d.%%m.%%Y') >= '%s' OR `entlassungsdatum` = '')
        """ % (enddate, startdate)
    cursor.execute(sql)
    # write ops_csv
    csv_file_out = open(csv_file, 'w')
    csv_file_out.write(','.join(('CaseNr','Ward','Datum','Code'))+'\n')
    iddbconnection = IDDB.connection(
        user = conf.get('idsql', 'user'),
        dc = conf.get('idsql', 'dc'),
        host = conf.get('idsql', 'host'),
        session = conf.get('idsql', 'session'),
        passwd = conf.get('idsql', 'pass')
        )
    for row in cursor.fetchall():
        case = iddbconnection.case(row["aufnahmenummer"])
        for code in case.getProcedures():
            line = ",".join((
                str(row['aufnahmenummer']),
                str(row['Ward']),
                code['cdate'].strftime("%d.%m.%Y"),
                str(code['code'])
                ))
            csv_file_out.write(line + '\n')
    csv_file_out.close()

def main(progoptions):
    ## set cmd options
    if progoptions.fromdate and progoptions.todate:
        report_start_date = progoptions.fromdate
        report_end_date = progoptions.todate
    else:
        start_date = datetime.now() - timedelta(days=7)
        report_start_date = start_date.strftime("%Y-%m-%d")
        end_date = datetime.now() - timedelta(days=1)
        report_end_date = end_date.strftime("%Y-%m-%d")

    ## get directorys
    SCRIPT_DIR = os.path.dirname(os.path.realpath(__file__))
    FILES_DIR = os.path.join(SCRIPT_DIR, 'ops_report_files')
    TEMP_DIR = utils.TempDir()

    ## copy files to temp
    report_files = (
        {'src': 'ops_report.xls.custom', 'dest': 'ops_report.xls'},
        {'src': 'ops_codes.R', 'dest': 'ops_codes.R'},
        {'src': 'ops_report.R', 'dest': 'ops_report.R'}
    )
    for report_file in report_files:
        source_file = os.path.join(
            FILES_DIR,
            report_file['src']
            )
        destination_file = os.path.join(
            TEMP_DIR.name,
            report_file['dest']
        )
        try:
            shutil.copy2(source_file, destination_file)
        except:
            pass

    ## gen csv in temp
    gen_codes_csv(
        report_start_date,
        report_end_date,
        os.path.join(TEMP_DIR.name, "codes.csv")
        )

    ## run R
    os.chdir(TEMP_DIR.name)
    cmd = "Rscript --quiet --no-save --no-restore %s --args %s %s" %(
        'ops_report.R',
        report_start_date, report_end_date
        )
    FNULL = open(os.devnull, 'w')
    subprocess.call(
        cmd,
        stdout = FNULL,
        stderr = FNULL,
        shell = True
        )
    FNULL.close()

    ## mail report
    repprop  = BESsystem.ReportProperties('ops-report')
    TO = repprop.recipient()
    CC = repprop.recipients_CC()
    FROM = repprop.sender()
    msg = email.MIMEMultipart.MIMEMultipart()
    msg["From"] = FROM
    msg["To"] = TO
    msg["Cc"] = ",".join(CC)
    msg["Subject"] = "%s %s" % (repprop.mailsubject(), str(datetime.now().date()))
    msg['Date'] = email.Utils.formatdate(localtime=True)
    text = MIMEText(repprop.mailbody())
    msg.attach(text)
    part = email.MIMEMultipart.MIMEBase('application', "octet-stream")
    part.set_payload(open(os.path.join(TEMP_DIR.name, 'ops_report.xls'), "rb").read())
    email.Encoders.encode_base64(part)
    part.add_header(
        'Content-Disposition',
        'attachment; filename="ops_report_%s.xls"' % (str(datetime.now().date()))
        )
    msg.attach(part)
    server = smtplib.SMTP('localhost')
    TO = [TO] + CC
    server.sendmail(FROM, TO, msg.as_string())
    server.quit()
    del(TEMP_DIR)

if __name__ == '__main__':
    parser = OptionParser("ops-report.py [Optionen]")
    parser.add_option("-f","--from", help="startdate [YYYY-MM-DD]", dest="fromdate")
    parser.add_option("-t","--to", help="enddate [YYYY-MM-DD]", dest="todate")
    (progoptions, progargs) = parser.parse_args()
    main(progoptions)

