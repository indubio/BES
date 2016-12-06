library(data.table)
library(RODBC)

datasource = "sql" # sql or csv

if (datasource == "csv") {
  ### from csv
  file <- "pna_export.csv"
  # "AssessmentID";"CreationTime";"CaseID";"FirstName";"LastName";"ContactTime";"FindingAbbr";"FindingName";"Value"
  PNAdata <- fread(file, encoding = "UTF-8")
  rm(file)
  PNAdata <- as.data.frame(PNAdata)
  # PNAdata_bck <- PNAdata
}

if (datasource == "sql") {
  ### from sql
  require(RODBC)
  ### get connection_str
  source("conf_connection_str.custom.R")
  channel <- odbcDriverConnect(connection = connection_str)
  querystr <- "exec KEvB_GetPNAInfo"
  PNAdata <- sqlQuery(channel, querystr)
  odbcClose(channel)
  rm(channel, querystr, connection_str)
}
rm(datasource)

PNAdata$CreationTime <- as.POSIXct(PNAdata$CreationTime)
PNAdata$ContactTime <- as.POSIXct(PNAdata$ContactTime)

PNAdata <- unique(PNAdata)
PNAdata <- PNAdata[with(PNAdata, rev(order(CreationTime))), ]
PNAdata$FindingAbbr <- as.factor(PNAdata$FindingAbbr)
PNAdata$FindingName <- as.factor(PNAdata$FindingName)

### rm testdata

PNAdata <- subset(PNAdata, !(PNAdata$AssessmentID %in% c("4972739","6064814","5977154","6173201","8290011","9407996")))
PNAdata <- subset(PNAdata, format(PNAdata$ContactTime, format="%Y") >= "2015")

PNAdata <- PNAdata[!duplicated(PNAdata[c('AssessmentID', 'FindingAbbr')]),]

PNAdata_dc <- dcast(PNAdata, AssessmentID ~ FindingAbbr, value.var = "Value")

additionaldata <- PNAdata[!duplicated(PNAdata[c('AssessmentID')]),]

PNAdata_dc <- merge(
  x = PNAdata_dc,
  y = subset(additionaldata, select=c('AssessmentID', 'CreationTime', 'CaseID', 'FirstName', 'LastName', 'BirthDate', 'Sex', 'ContactTime')),
  by.x = "AssessmentID",
  by.y = "AssessmentID",
  all.x= TRUE
)

rm(additionaldata)

PNAdata_dc$PNABef_v08 <- as.numeric(PNAdata_dc$PNABef_v08) # Dauer
PNAdata_dc$ContactTime.Mon <- format(PNAdata_dc$ContactTime, format="%m")
PNAdata_dc$ContactTime.Year <- format(PNAdata_dc$ContactTime, format="%Y")
