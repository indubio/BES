library("XLConnect")
opscode_file <- "codes.csv"
xlsfile <- "ops_report.xls"

args = commandArgs(TRUE)

first_date <- strptime(args[2], format="%Y-%m-%d")
 last_date <- strptime(args[3], format="%Y-%m-%d")
     today <- strptime(Sys.time(), format="%Y-%m-%d")

source("ops_codes.R")

## ops file
df_ops <- read.table(opscode_file, sep=",", header=TRUE)
df_ops$Date <- strptime(df_ops$Datum, format="%d.%m.%Y")
df_ops$Date_c <- as.character(df_ops$Date)
df_ops <- subset(df_ops, df_ops$Date >= first_date & df_ops$Date <= last_date)

## nur ops_codes
df_ops <- subset(df_ops, df_ops$Code %in% ops_codes)
df_ops$Datum <- factor(df_ops$Datum)
df_ops$Code <- factor(df_ops$Code, levels=ops_codes)
df_ops$Date_c <- factor(df_ops$Date_c)

## Ausgabe
wb <- loadWorkbook(xlsfile, create = TRUE)

for (ward in levels(df_ops$Ward)) {
    dummy_subset <- subset(df_ops, df_ops$Ward == ward)
    sheetname <- paste("RAW", as.character(ward), sep="_")
    createSheet(wb, name = sheetname)
    writeWorksheet(wb,
        as.data.frame.matrix(table(dummy_subset$Code, dummy_subset$Date_c)),
        sheet = sheetname, rownames = "")
    rm(dummy_subset)
}

for (wbsheet in getSheets(wb)) {
    setForceFormulaRecalculation(wb, sheet=wbsheet, value=TRUE)
}
saveWorkbook(wb)
