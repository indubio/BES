# source("initpart.R")
### OPS Data
file <- "bes_PsychOPS_Codes.csv"
PsychOPSCodes <- fread(file, encoding = "UTF-8")
rm(file)
### Datenaufbereitung
PsychOPSCodes$Datum <- as.Date(PsychOPSCodes$Datum, "%Y-%m-%d")
PsychOPSCodes$Station <- factor(trim(PsychOPSCodes$Station))
PsychOPSCodes$Aufnahmedatum <- as.Date(PsychOPSCodes$Aufnahmedatum, "%Y-%m-%d")
PsychOPSCodes$Entlassdatum <- as.Date(PsychOPSCodes$Entlassdatum, "%Y-%m-%d")

PsychOPSCodes$Kalenderwoche <- as.numeric(format(PsychOPSCodes$Datum, "%W"))
PsychOPSCodes[PsychOPSCodes$week == 0, "Kalenderwoche"] <- 1
PsychOPSCodes$Kalenderwoche <- factor(PsychOPSCodes$Kalenderwoche)

PsychOPSCodes[PsychOPSCodes$Station =="PSY6", "Station"] <- "PSYSO1"
PsychOPSCodes <- subset(PsychOPSCodes, PsychOPSCodes$Station %in% c(psy_wards, psy_tk, psy_kjpp))
PsychOPSCodes$Station <- factor((PsychOPSCodes$Station))
PsychOPSCodes$Fall.Nr <- as.factor(PsychOPSCodes$Fall.Nr)

#####
### OPS Code Splitting
dummy <- strsplit(PsychOPSCodes$OPS.Code, "\\.")
PsychOPSCodes <- data.frame(PsychOPSCodes, do.call(rbind, dummy))
rm(dummy)
names(PsychOPSCodes)[names(PsychOPSCodes)=="X1"] <- "OPS.Code.major"
names(PsychOPSCodes)[names(PsychOPSCodes)=="X2"] <- "OPS.Code.minor"

PsychOPSCodes$OPS.Code.3stellig <- substr(PsychOPSCodes$OPS.Code ,1,4)
PsychOPSCodes$OPS.Code.4stellig <- substr(PsychOPSCodes$OPS.Code ,1,5)
PsychOPSCodes$OPS.Code.5stellig <- substr(PsychOPSCodes$OPS.Code ,1,7)

#####
### Variablenvorbereitung
PsychOPSCodes$Profession <- NA
PsychOPSCodes$Profession <- factor(PsychOPSCodes$Profession, professions)
PsychOPSCodes$E.G <- NA
PsychOPSCodes$E.G <- factor(PsychOPSCodes$E.G, c("Einzeltherapie", "Gruppentherapie"))

source("import_csv_TECodes.R")
source("import_csv_OneonOneCodes.R")
