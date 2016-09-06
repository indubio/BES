#!/usr/bin/env python
# -*- coding: UTF-8 -*-
"""
Programm name : IDDB.py
Date          : 2012-10-25
Author        : Steffen Eichhorn [mail@indubio.org]
License       : GPL
Description   : abstrahiert den Zugriff auf Daten aus der SQL-Datenbank vom ID Scorer
"""

import os.path
import pyodbc
import datetime
import csv

class PsychPV(object):
    """"PsychPV Object"""
    def __init__(self):
        self.StatusStr = ''
        self.Date = ''
        self.Finished = False
    def __str__(self):
        return " - ".join((str(self.Date), self.StatusStr))

class PsychStatus(object):
    """PsychStatus Object"""
    def __init__(self):
        self.Date = ''
        self.Status = ''
        self.StatusStr = ''
        self.IntensiveCare = False
        self.IntensiveCareReasons = []
        self.IntegratedCare = False
        self.ParentsSettingCare = False
        self.Hardship = False
        self.HardshipReasons = []
        self.NoStation = False
        self.HalfDay = False
        self.QE = False
        self.Finished = False
    def __str__(self):
        return " - ".join((str(self.Date), self.StatusStr))

class BI_Score(object):
    """Behandlungsintensität Object"""
    def __init__(self):
        self.Date = ''
        self.Finished = False
        self.SomaScore = 0
        self.PsyScore = 0
        self.SocialScore = 0
        self.TotalScore = 0
    def __str__(self):
        return " - ".join((str(self.Date), str(self.TotalScore)))

class Case(object):
    def __init__(self, connection_str, soarian_nr):
        self.PsychPV = ''
        self.__connection_str = connection_str
        self.setCaseNr(soarian_nr)

    def setCaseNr(self, soarian_nr):
        """
        initiert einen neuen Fall zur Abfrage
        """
        SQLConn = pyodbc.connect(self.__connection_str)
        SQLCursor = SQLConn.cursor()

        self.__Soarian_Nr = soarian_nr
        self.__CaseID = ''
        self.__CaseNodeID = ''
        self.__CaseEHRID = ''
        self.__CaseNodeChildID = ''

        ## get CaseID
        sqlquery = """
            select ID from CASES
            where CID=?
            """
        SQLCursor.execute(sqlquery, self.__Soarian_Nr)
        try:
            self.__CaseID = SQLCursor.fetchone().ID
        except:
            pass

        ## get CaseNodeID by CaseID
        if self.__CaseID != '':
            sqlquery = """
                select nodes_ID from CASES_NODES
                where CASES_ID=?
                """
            SQLCursor.execute(sqlquery, self.__CaseID)
            try:
                self.__CaseNodeID = SQLCursor.fetchone().nodes_ID
            except:
                pass

        if self.__CaseNodeID != '':
            ## get CaseEHRID by CaseNodeID
            sqlquery = """
                select ehrid from NODES
                where ID=?
                """
            SQLCursor.execute(sqlquery, self.__CaseNodeID)
            try:
                self.__CaseEHRID = SQLCursor.fetchone().ehrid
            except:
                pass

            ## get CaseNodeChildID by CaseNodeID
            sqlquery = """
                select ID from NODES
                where PARENTID=?
                """
            SQLCursor.execute(sqlquery, self.__CaseNodeID)
            try:
                self.__CaseNodeChildID = SQLCursor.fetchone().ID
            except:
                pass
        SQLConn.close()

    def getPsychPV(self):
        """
        Liefert eine Liste der PsychPV Einträge zurück,
        die nach Datum sortiert ist
        """
        PsychPVList = []
        if self.__CaseNodeChildID != '':
            # init SQL connections and cursors
            NodesSQLConn = pyodbc.connect(self.__connection_str)
            NodesSQLCursor = NodesSQLConn.cursor()
            NodeChildsSQLConn = pyodbc.connect(self.__connection_str)
            NodeChildsSQLCursor = NodeChildsSQLConn.cursor()
            PropertiesSQLConn = pyodbc.connect(self.__connection_str)
            PropertiesSQLCursor = PropertiesSQLConn.cursor()
            # fetch nodes
            sqlquery = """
                select * from NODES
                where NODETYPEID='3' and PARENTID=?
                """
            for node in NodesSQLCursor.execute(sqlquery, self.__CaseNodeChildID):
                newPsychPV = PsychPV()
                sqlquery = """
                    select * from PROPERTIES
                    where NodeID=?
                    """
                for property in PropertiesSQLCursor.execute(sqlquery, node.ID):
                    if property.PROPERTYNAME == 'Finished':
                        if property.PROPERTYVALUE == 'true':
                            newPsychPV.Finished = True
                    if property.PROPERTYNAME == 'Date':
                        newPsychPV.Date = datetime.datetime.strptime(
                            property.PROPERTYVALUE.split('T')[0],
                            "%Y-%m-%d").date()
                sqlquery = """
                    select * from NODES
                    where ParentID=?
                    and NODETYPEID='7'
                    """
                for ChildNode in NodeChildsSQLCursor.execute(sqlquery, node.ID):
                    sqlquery = """
                        select * from PROPERTIES
                        where NodeID=?
                        """
                    for ChildNodeProperty in PropertiesSQLCursor.execute(sqlquery, ChildNode.ID):
                        if ChildNodeProperty.PROPERTYNAME == 'Value':
                            newPsychPV.StatusStr = ChildNodeProperty.PROPERTYVALUE
                PsychPVList.append(newPsychPV)
                del newPsychPV
            # close SQL connections and cursors
            NodesSQLCursor.close()
            NodesSQLConn.close()
            NodeChildsSQLCursor.close()
            NodeChildsSQLConn.close()
            PropertiesSQLCursor.close()
            PropertiesSQLConn.close()
            PsychPVList.sort(key = lambda x: x.Date)
        return PsychPVList

    def getLastPsychPVCode(self):
        """
        Liefert letzten PsychPVCode als string zurück
        """
        returnCode = ''
        PsychPVList = self.getPsychPV()
        if len(PsychPVList) > 0:
            returnCode = PsychPVList[-1].StatusStr
        return returnCode

    def getPsychStatus(self):
        """
        Liefert eine Liste aller PsychStatus Einträge zurück,
        die nach Datum sortiert ist
        """
        PsychStatusList = []
        if self.__CaseNodeChildID != '':
            # init SQL connections and cursors
            NodesSQLConn = pyodbc.connect(self.__connection_str)
            NodesSQLCursor = NodesSQLConn.cursor()
            NodeChildsSQLConn = pyodbc.connect(self.__connection_str)
            NodeChildsSQLCursor = NodeChildsSQLConn.cursor()
            PropertiesSQLConn = pyodbc.connect(self.__connection_str)
            PropertiesSQLCursor = PropertiesSQLConn.cursor()
            ## fetch all Status Nodes
            sqlquery = """
                select ID from NODES
                where NODETYPEID='8'
                and PARENTID=?
                """
            for node in NodesSQLCursor.execute(sqlquery, self.__CaseNodeChildID):
                newPsychStatus = PsychStatus()
                sqlquery = """
                    select * from PROPERTIES
                    where NODEID=?
                    """
                for row in PropertiesSQLCursor.execute(sqlquery, node.ID):
                    if row.PROPERTYNAME == 'Date':
                        newPsychStatus.Date = datetime.datetime.strptime(
                            row.PROPERTYVALUE.split('T')[0],
                            "%Y-%m-%d").date()
                    if row.PROPERTYNAME =='Finished':
                        if row.PROPERTYVALUE == 'false':
                            newPsychStatus.Finished = False
                        elif row.PROPERTYVALUE == 'true':
                            newPsychStatus.Finished = True
                ## get Child Nodes and Data
                sqlquery = """
                    select * from NODES
                    where PARENTID=?
                    """
                IntensiveCareNode = ''
                Hardship = ''
                for node_row in NodeChildsSQLCursor.execute(sqlquery, node.ID):
                    sqlquery = """
                        select * from PROPERTIES
                        where NODEID=?
                        """
                    for nodeprop in PropertiesSQLCursor.execute(sqlquery, node_row.ID):
                        if nodeprop.PROPERTYNAME == 'IntensiveCare':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.IntensiveCare = True
                                IntensiveCareNode = node_row.ID
                        if nodeprop.PROPERTYNAME == 'Integrated':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.IntegratedCare = True
                        if nodeprop.PROPERTYNAME == 'ParentsSetting':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.ParentsSettingCare = True
                        if nodeprop.PROPERTYNAME == 'NoStation':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.NoStation = True
                        if nodeprop.PROPERTYNAME == 'HalfDay':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.HalfDay = True
                        if nodeprop.PROPERTYNAME == 'ExtraWithdrawlTreatment':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.QE = True
                        if nodeprop.PROPERTYNAME == 'Hardship':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.Hardship = True
                        if nodeprop.PROPERTYNAME == 'Hardship1':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.HardshipReasons.append('1')
                        if nodeprop.PROPERTYNAME == 'Hardship2':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.HardshipReasons.append('2')
                        if nodeprop.PROPERTYNAME == 'Hardship3':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.HardshipReasons.append('3')
                        if nodeprop.PROPERTYNAME == 'Hardship4':
                            if nodeprop.PROPERTYVALUE == 'true':
                                newPsychStatus.HardshipReasons.append('4')

                        if nodeprop.PROPERTYNAME == 'Value':
                            newPsychStatus.Status = nodeprop.PROPERTYVALUE

                if IntensiveCareNode != '':
                    sqlquery = """
                        select * from NODES
                        where PARENTID=?
                        """
                    for childnode in NodeChildsSQLCursor.execute(sqlquery, IntensiveCareNode):
                        sqlquery = """
                            select * from PROPERTIES
                            where NODEID=?
                            """
                        for childnodeproperty in PropertiesSQLCursor.execute(sqlquery, childnode.ID):
                            if childnodeproperty.PROPERTYNAME[:6] == 'Reason':
                                if childnodeproperty.PROPERTYVALUE != '':
                                    newPsychStatus.IntensiveCareReasons.append(childnodeproperty.PROPERTYNAME[-1:])

                if newPsychStatus.IntensiveCare:
                    newPsychStatus.StatusStr  = "I" + str(len(newPsychStatus.IntensiveCareReasons))
                if newPsychStatus.Status == "0":
                    newPsychStatus.StatusStr = "R"
                if newPsychStatus.Status == "1":
                    newPsychStatus.StatusStr = "PSYK"
                if newPsychStatus.Status == "2":
                    newPsychStatus.StatusStr = "PSOK"
                if newPsychStatus.Status == "5":
                    newPsychStatus.StatusStr = "QE"
                if newPsychStatus.IntegratedCare:
                    newPsychStatus.StatusStr = "".join((newPsychStatus.StatusStr,"+"))
                if newPsychStatus.ParentsSettingCare:
                    newPsychStatus.StatusStr = "".join((newPsychStatus.StatusStr,"*"))
                if newPsychStatus.QE:
                    newPsychStatus.StatusStr = "".join((newPsychStatus.StatusStr,"%"))
                if newPsychStatus.NoStation:
                    newPsychStatus.StatusStr = "".join((newPsychStatus.StatusStr,"?"))
                if newPsychStatus.HalfDay:
                    newPsychStatus.StatusStr = "".join((newPsychStatus.StatusStr,"?"))
                PsychStatusList.append(newPsychStatus)
                if newPsychStatus.Hardship:
                    newPsychStatus.StatusStr = "".join((
                        newPsychStatus.StatusStr,
                        "!",
                        str(len(newPsychStatus.HardshipReasons))
                    ))
                del newPsychStatus
            # close SQL connections and cursors
            PropertiesSQLCursor.close()
            PropertiesSQLConn.close()
            NodeChildsSQLCursor.close()
            NodeChildsSQLConn.close()
            NodesSQLCursor.close()
            NodesSQLConn.close()
            PsychStatusList.sort(key = lambda x: x.Date)
        return PsychStatusList

    def getLastPsychStatusCode(self):
        """
        Liefert den letzten PsychStatus Code als string zurück
        """
        returnCode = ''
        PsychStatusList = self.getPsychStatus()
        if len(PsychStatusList) > 0:
            returnCode = PsychStatusList[-1].StatusStr
        return returnCode

    def getProcedures(self):
        """
        Liefert die Prozeduren Codes als array zurück
        """
        ProceduresList = []
        if self.__CaseID != '':
            # init SQL connections and cursors
            ProceduresSQLConn = pyodbc.connect(self.__connection_str)
            ProceduresSQLCursor = ProceduresSQLConn.cursor()
            Procedures_CodesSQLConn = pyodbc.connect(self.__connection_str)
            Procedures_CodesSQLCursor = Procedures_CodesSQLConn.cursor()
            CodesSQLConn = pyodbc.connect(self.__connection_str)
            CodesSQLCursor = CodesSQLConn.cursor()
            ## fetch all Procedures
            sqlquery = """
                select * from PROCEDURES
                where CID=?
                order by PDATE asc
                """
            for procedure in ProceduresSQLCursor.execute(sqlquery, self.__CaseID):
                # get Code ID
                sqlquery = """
                    select codes_ID from PROCEDURES_CODES
                    where PROCEDURES_ID=?
                    """
                Procedures_CodesSQLCursor.execute(sqlquery, procedure.ID)
                CodesID = Procedures_CodesSQLCursor.fetchone().codes_ID
                # get Code
                sqlquery = """
                    select * from CODES
                    where ID=?
                """
                CodesSQLCursor.execute(sqlquery, CodesID)
                code = CodesSQLCursor.fetchone()
                ProceduresList.append({
                    'cdate': procedure.PDATE,
                    'code': code.VALUE
                    })
            # close SQL connections and cursors
            ProceduresSQLCursor.close()
            ProceduresSQLConn.close()
            Procedures_CodesSQLCursor.close()
            Procedures_CodesSQLConn.close()
            CodesSQLCursor.close()
            CodesSQLConn.close()
        return ProceduresList

    def getBIScore(self):
        """
        Liefert eine Liste aller Einträge zur Behandlungsintensität zurück,
        die nach Datum sortiert ist
        """
        BIScoreList = []
        if self.__CaseNodeChildID != '':
            # init SQL connections and cursors
            NodesSQLConn = pyodbc.connect(self.__connection_str)
            NodesSQLCursor = NodesSQLConn.cursor()
            NodeChildsSQLConn = pyodbc.connect(self.__connection_str)
            NodeChildsSQLCursor = NodeChildsSQLConn.cursor()
            PropertiesSQLConn = pyodbc.connect(self.__connection_str)
            PropertiesSQLCursor = PropertiesSQLConn.cursor()
            ## fetch all CareIntensityERW2013 Nodes
            sqlquery = """
                select ID from NODES
                where NODETYPEID='16'
                and PARENTID=?
                """
            for node in NodesSQLCursor.execute(sqlquery, self.__CaseNodeChildID):
                sqlquery = """
                    select * from PROPERTIES
                    where NODEID=?
                    """
                newBIScore = BI_Score()
                for property in PropertiesSQLCursor.execute(sqlquery, node.ID):
                    if property.PROPERTYNAME == "SomaScore":
                        newBIScore.SomaScore = int(property.PROPERTYVALUE)
                    if property.PROPERTYNAME == 'PsyScore':
                        newBIScore.PsyScore = int(property.PROPERTYVALUE)
                    if property.PROPERTYNAME == 'SocialScore':
                        newBIScore.SocialScore = int(property.PROPERTYVALUE)
                    if property.PROPERTYNAME == 'totalScore':
                        newBIScore.TotalScore = int(property.PROPERTYVALUE)
                    if property.PROPERTYNAME == 'Finished':
                        if property.PROPERTYVALUE == 'true':
                            newBIScore.Finished = True
                    if property.PROPERTYNAME == 'Date':
                        newBIScore.Date = datetime.datetime.strptime(
                            property.PROPERTYVALUE.split('T')[0],
                            "%Y-%m-%d").date()
                BIScoreList.append(newBIScore)
                del newBIScore
            # close SQL connections and cursors
            NodesSQLCursor.close()
            NodesSQLConn.close()
            NodeChildsSQLCursor.close()
            NodeChildsSQLConn.close()
            PropertiesSQLCursor.close()
            PropertiesSQLConn.close()
            BIScoreList.sort(key = lambda x: x.Date)
        return BIScoreList

    def getLastBIScore(self):
        """
        Liefert den letzten Score der Betreuungsintensität als string zurück
        """
        returnCode = ''
        BIScoreList = self.getBIScore()
        if len(BIScoreList) > 0:
            returnCode = str(BIScoreList[-1].TotalScore)
        return returnCode

class connection(object):
    """ID Datenbank Objekt"""
    def __init__(self, *args, **kwargs):
        """
        Stellt die Verbindung zur ID Datenbank her
        host
          string, Host der Datenbank
        session
          string, Session der Datenbank
        dc
          string, DomainController
        user
          string, Benutzername
        passwd
          string, Benutzerpasswort
        """
        user_str = kwargs['user']
        if kwargs['dc'] != '':
            user_str = "\\".join([
                kwargs['dc'],
                kwargs['user']
            ])
        server_str = kwargs['host']
        if kwargs['session'] != '':
            server_str = "\\".join([
                kwargs['host'],
                kwargs['session']
            ])
        self.__connection_str = ';'.join([
            'DRIVER={FreeTDS}',
            'SERVER=%s' % server_str,
            'UID=%s' % user_str,
            'PWD=%s' % kwargs['passwd'],
            'CLIENTCHARSET=UTF-8',
            'TDS_Version=8.0',
            ''
        ])

    def con(self):
        return pyodbc.connect(self.__connection_str)

    def case(self, soarian_nr):
        """
        generiert ein Case Objekt, über das fallbezogene Daten abgefragt
        werden können
        """
        return Case(self.__connection_str, soarian_nr)

    def getCases(self):
        """
        liefert die Case-Tabelle zurück
        """
        SQLConn = pyodbc.connect(self.__connection_str)
        SQLCursor = SQLConn.cursor()
        sqlquery = """
            SELECT CID, WARD, ADMHOSPITAL, SEPHOSPITAL from CASES
            WHERE APSYCH = 1
            """
        SQLCursor.execute(sqlquery)
        try:
            casesID = SQLCursor.fetchall()
        except:
            pass
        SQLCursor.close()
        SQLConn.close()
        return casesID

    def Export_OPS_Codes(self, filename):
        """
        exportiert alle OPS-Codes, gleich der Daten-Exportfunktion im SpezialPsych Programm
        """
        returnstatus = False
        SQLConn = pyodbc.connect(self.__connection_str)
        SQLCursor = SQLConn.cursor()
        sqlquery = """
            SELECT
                Procedures.PDATE as 'Datum',
                Codes.VALUE as 'OPS_Code',
                Procedures.NAME as 'OPS_Name',
                Cases.CID as 'Fall_Nr',
                Patients.PID as 'Patienten_Nr',
                Cases.WARD as 'Station',
                Cases.ADMHOSPITAL as 'Aufnahmedatum',
                CASES.SEPHOSPITAL as 'Entlassdatum'
            FROM
                "id_scorer"."dbo"."PROCEDURES" Procedures
                JOIN "id_scorer"."dbo"."PROCEDURES_CODES" Procedures2Codes ON Procedures2Codes.PROCEDURES_ID = Procedures.ID
                JOIN "id_scorer"."dbo"."CODES" Codes ON Codes.ID = Procedures2Codes.codes_ID
                JOIN "id_scorer"."dbo"."CASES" Cases ON Cases.ID = Procedures.CID
                JOIN "id_scorer"."dbo"."PATIENTS" Patients ON Patients.ID = CASES.PID
            WHERE Cases.APSYCH = 1
            ORDER BY Procedures.PDATE
            """
        SQLCursor.execute(sqlquery)
        try:
            f_csv = open(filename, 'wt')
            csvwriter = csv.writer(f_csv, delimiter = ';', doublequote = True, quoting=csv.QUOTE_ALL)
            csvwriter.writerow([
                "Patienten.Nr", "Fall.Nr", "Datum", "Zeit", "OPS.Code",
                "Aufnahmedatum", "Entlassdatum", "Station"
            ])

            for i in SQLCursor.fetchall():
                codedatum = ""
                aufnahmedatum = ""
                entlassdatum = ""

                if i.Datum.date() == datetime.date(1899, 12, 30):
                    codedatum = ""
                else:
                    codedatum = str(i.Datum.date())

                if i.Aufnahmedatum.date() == datetime.date(1899, 12, 30):
                    aufnahmedatum = ""
                else:
                    aufnahmedatum = str(i.Aufnahmedatum.date())

                if i.Entlassdatum.date() == datetime.date(1899, 12, 30):
                    entlassdatum = ""
                else:
                    entlassdatum = str(i.Entlassdatum.date())

                csvwriter.writerow([
                    i.Patienten_Nr, i.Fall_Nr, codedatum, i.Datum.time(),
                    i.OPS_Code, aufnahmedatum, entlassdatum, i.Station
                ])

            f_csv.close()
            returnstatus = True
        except:
            print sys.exc_info()[0]
        SQLCursor.close()
        SQLConn.close()
        return returnstatus

if __name__ == '__main__':
    pass
