#!/usr/bin/env python
# -*- coding: UTF-8 -*-
"""
Programm name : Report_PNA.py
Date          : 2016-11-25
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
import tarfile

def main(progoptions):
    conf = BESsystem.BESConfig()

    REPORT_FILES_DIR_PREFIX = 'report_pna'
    SCRIPT_DIR = os.path.dirname(os.path.realpath(__file__))
    FILES_DIR = os.path.join(SCRIPT_DIR, REPORT_FILES_DIR_PREFIX)
    TEMP_DIR = utils.TempDir()

    ## copy files to temp
    rename_files = (
        {'src': '_report_pna.Rnw', 'dest': 'PNA_Report.Rnw'}, # , am Ende wichtig
    )
    shutil.copytree(FILES_DIR, os.path.join(TEMP_DIR.name, REPORT_FILES_DIR_PREFIX))
    for rename_file in rename_files:
        shutil.move(
            os.path.join(TEMP_DIR.name,REPORT_FILES_DIR_PREFIX, rename_file['src']),
            os.path.join(TEMP_DIR.name,REPORT_FILES_DIR_PREFIX, rename_file['dest'])
        )

    ## run R + PDFLatex
    os.chdir(os.path.join(TEMP_DIR.name,REPORT_FILES_DIR_PREFIX))
    cmd_steps = {
        "Run_R"       : "Rscript --quiet --no-save --no-restore -e \"require(knitr); knit('PNA_Report.Rnw')\"",
        "gen_PDF"     : "pdflatex -interaction=nonstopmode %s" % ("PNA_Report.tex"),
        "gen_draftPDF": "pdflatex -interaction=nonstopmode -draftmode %s" % ("PNA_Report.tex")
    }

    FNULL = open(os.devnull, 'w')
    for step in ["Run_R", "gen_draftPDF", "gen_PDF"]:
            subprocess.call(
                cmd_steps[step],
                stdout = FNULL,
                stderr = FNULL,
                shell = True
            )
    FNULL.close()

##    tar = tarfile.open(os.path.join("/tmp", "pnareport.tar"), 'w:gz')
##    os.chdir(TEMP_DIR.name)
##    tar.add(".")
##    tar.close()

    ## mail report
    repprop  = BESsystem.ReportProperties('PNA-Report')
    TO = repprop.recipient()
    CC = repprop.recipients_CC()
#    CC = []   # cc leer zum Testen
    FROM = repprop.sender()
    msg = email.MIMEMultipart.MIMEMultipart()
    msg['Content-Type'] = "text; charset=utf-8"
    msg["From"] = FROM
    msg["To"] = TO
    msg["Cc"] = ",".join(CC)
    msg["Subject"] = "%s %s" % (repprop.mailsubject(), str(datetime.now().date()))
    msg['Date'] = email.Utils.formatdate(localtime=True)
    text = MIMEText(repprop.mailbody())
    msg.attach(text)
    part = email.MIMEMultipart.MIMEBase('application', "octet-stream")
    part.set_payload(open(os.path.join(TEMP_DIR.name, REPORT_FILES_DIR_PREFIX, 'PNA_Report.pdf'), "rb").read())
    email.Encoders.encode_base64(part)
    part.add_header(
        'Content-Disposition',
        'attachment; filename="PNA_Report_%s.pdf"' % (str(datetime.now().date()))
        )
    msg.attach(part)
    server = smtplib.SMTP('localhost')
    TO = [TO] + CC
    server.sendmail(FROM, TO, msg.as_string())
    server.quit()

    del(TEMP_DIR)

if __name__ == '__main__':
    parser = OptionParser("ops-report.py [Optionen]")
    parser.add_option("-f","--foo", help="foo", dest="foo")
    #parser.add_option("-t","--to", help="enddate [YYYY-MM-DD]", dest="todate")
    (progoptions, progargs) = parser.parse_args()
    main(progoptions)
