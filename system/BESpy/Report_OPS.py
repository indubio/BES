#!/usr/bin/env python
# -*- coding: UTF-8 -*-
"""
Programm name : Report_OPS.py
Date          : 2016-08-24
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
import tarfile

def main(progoptions):
    conf = BESsystem.BESConfig()

    iddbconnection = IDDB.connection(
        user = conf.get('idsql', 'user'),
        dc = conf.get('idsql', 'dc'),
        host = conf.get('idsql', 'host'),
        session = conf.get('idsql', 'session'),
        passwd = conf.get('idsql', 'pass')
    )

    ### export OPS data from ID DB
    SCRIPT_DIR = os.path.dirname(os.path.realpath(__file__))
    FILES_DIR = os.path.join(SCRIPT_DIR, 'report_ops')
    TEMP_DIR = utils.TempDir()

    ## copy files to temp
    report_files = (
        {'src': 'Behandlungsstatus_init.R', 'dest': 'Behandlungsstatus_init.R'},
        {'src': 'evb_logo.pdf', 'dest': 'evb_logo.pdf'},
        {'src': 'import_bescsv.R', 'dest': 'import_bescsv.R'},
        {'src': 'import_csv_OneonOneCodes.R', 'dest': 'import_csv_OneonOneCodes.R'},
        {'src': 'import_csv.R', 'dest': 'import_csv.R'},
        {'src': 'import_csv_TECodes.R', 'dest': 'import_csv_TECodes.R'},
        {'src': 'initpart.R', 'dest': 'initpart.R'},
        {'src': '_main.Rnw', 'dest': 'OPS_Report.Rnw'},
        {'src': 'chap_titlepage.Rnw', 'dest': 'chap_titlepage.Rnw'},
        {'src': 'chap_overview_ops.Rnw', 'dest': 'chap_overview_ops.Rnw'},
        {'src': 'chap_vorwort.tex', 'dest': 'chap_vorwort.tex'},
        {'src': 'chap_zusatzcodes.Rnw', 'dest': 'chap_zusatzcodes.Rnw'},
        {'src': 'ward_page.Rnw', 'dest': 'ward_page.Rnw'}
    )

    for report_file in report_files:
        source_file = os.path.join(FILES_DIR, report_file['src'])
        destination_file = os.path.join(TEMP_DIR.name, report_file['dest'])
        try:
            shutil.copy2(source_file, destination_file)
        except:
            pass

    ## gen csv in temp
    iddbconnection.Export_OPS_Codes(os.path.join(TEMP_DIR.name, "bes_PsychOPS_Codes.csv"))

    ## run R + PDFLatex
    os.chdir(TEMP_DIR.name)
    cmd_steps = {
        "Run_R"       : "Rscript --quiet --no-save --no-restore -e \"require(knitr); knit('OPS_Report.Rnw')\"",
        "gen_PDF"     : "pdflatex -interaction=nonstopmode %s" % ("OPS_Report.tex"),
        "gen_draftPDF": "pdflatex -interaction=nonstopmode -draftmode %s" % ("OPS_Report.tex")
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

#    tar = tarfile.open(os.path.join("/tmp", "opsreport.tar"), 'w:gz')
#    os.chdir(TEMP_DIR.name)
#    tar.add(".")
#    tar.close()

    ## mail report
    repprop  = BESsystem.ReportProperties('OPS-Report v2')
    TO = repprop.recipient()
    CC = repprop.recipients_CC()
    CC = []
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
    part.set_payload(open(os.path.join(TEMP_DIR.name, 'OPS_Report.pdf'), "rb").read())
    email.Encoders.encode_base64(part)
    part.add_header(
        'Content-Disposition',
        'attachment; filename="OPS_Report_%s.pdf"' % (str(datetime.now().date()))
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
