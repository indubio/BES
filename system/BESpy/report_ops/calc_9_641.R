#==================
# 9-641
# Kriseninterventionelle Behandlung bei Erwachsenen

#==================
# seit 2012

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.0",  "DauerMinutes.min"] <- 1.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.0",  "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.0",  "DauerMinutes.typ"] <- "Krise"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.1",  "DauerMinutes.min"] <- 1.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.1",  "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.1",  "DauerMinutes.typ"] <- "Krise"

#==================
# seit 2015
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.00",  "DauerMinutes.min"] <- 1 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.00",  "DauerMinutes.max"] <- 1.5 * 50 -1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.00",  "DauerMinutes.typ"] <- "Krise"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.10",  "DauerMinutes.min"] <- 1 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.10",  "DauerMinutes.max"] <- 1.5 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.10",  "DauerMinutes.typ"] <- "Krise"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.01",  "DauerMinutes.min"] <- 1.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.01",  "DauerMinutes.max"] <- 3 * 50 -1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.01",  "DauerMinutes.typ"] <- "Krise"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.11",  "DauerMinutes.min"] <- 1.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.11",  "DauerMinutes.max"] <- 3 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.11",  "DauerMinutes.typ"] <- "Krise"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.02",  "DauerMinutes.min"] <- 3 * 60 
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.02",  "DauerMinutes.max"] <- 4.5 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.02",  "DauerMinutes.typ"] <- "Krise"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.12",  "DauerMinutes.min"] <- 3 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.12",  "DauerMinutes.max"] <- 4.5 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.12",  "DauerMinutes.typ"] <- "Krise"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.03",  "DauerMinutes.min"] <- 4.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.03",  "DauerMinutes.max"] <- 6 * 60 -1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.03",  "DauerMinutes.typ"] <- "Krise"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.13",  "DauerMinutes.min"] <- 4.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.13",  "DauerMinutes.max"] <- 6 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.13",  "DauerMinutes.typ"] <- "Krise"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.04",  "DauerMinutes.min"] <- 6 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.04",  "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.04",  "DauerMinutes.typ"] <- "Krise"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.14",  "DauerMinutes.min"] <- 6 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.14",  "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-641.14",  "DauerMinutes.typ"] <- "Krise"
