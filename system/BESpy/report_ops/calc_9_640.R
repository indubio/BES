#==================
# 9-640
# Erhöhter Betreuungsaufwand bei Erwachsenen
# 1:1 Betreuung und Kleinstgruppe

#==================
# seit 2010
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.00",  "DauerMinutes.min"] <- 2 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.00",  "DauerMinutes.max"] <- 6 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.00",  "DauerMinutes.typ"] <- "1on1"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.01",  "DauerMinutes.min"] <- 6 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.01",  "DauerMinutes.max"] <- 12 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.01",  "DauerMinutes.typ"] <- "1on1"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.02",  "DauerMinutes.min"] <- 12 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.02",  "DauerMinutes.max"] <- 18 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.02",  "DauerMinutes.typ"] <- "1on1"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.03",  "DauerMinutes.min"] <- 18 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.03",  "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.03",  "DauerMinutes.typ"] <- "1on1"

#PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.10",  "DauerMinutes.min"] <- 1 * 60
#PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.10",  "DauerMinutes.max"] <- 2 * 60 - 1
#PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.10",  "DauerMinutes.typ"] <- "3on1"

#PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.11",  "DauerMinutes.min"] <- 2 * 60
#PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.11",  "DauerMinutes.max"] <- 24 * 60
#PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.11",  "DauerMinutes.typ"] <- "3on1"

#==================
# seit 2012
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.10",  "DauerMinutes.min"] <- 2 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.10",  "DauerMinutes.max"] <- 6 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.10",  "DauerMinutes.typ"] <- "KleinG"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.11",  "DauerMinutes.min"] <- 6 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.11",  "DauerMinutes.max"] <- 12 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.11",  "DauerMinutes.typ"] <- "KleinG"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.12",  "DauerMinutes.min"] <- 12 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.12",  "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.12",  "DauerMinutes.typ"] <- "KleinG"

#==================
# seit 2016
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.04",  "DauerMinutes.min"] <- 2 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.04",  "DauerMinutes.max"] <- 4 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.04",  "DauerMinutes.typ"] <- "1on1"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.05",  "DauerMinutes.min"] <- 4 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.05",  "DauerMinutes.max"] <- 6 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.05",  "DauerMinutes.typ"] <- "1on1"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.06",  "DauerMinutes.min"] <- 6 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.06",  "DauerMinutes.max"] <- 12 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.06",  "DauerMinutes.typ"] <- "1on1"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.07",  "DauerMinutes.min"] <- 12 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.07",  "DauerMinutes.max"] <- 19 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.07",  "DauerMinutes.typ"] <- "1on1"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.08",  "DauerMinutes.min"] <- 18 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.08",  "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.08",  "DauerMinutes.typ"] <- "1on1"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.13",  "DauerMinutes.min"] <- 2 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.13",  "DauerMinutes.max"] <- 4 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.13",  "DauerMinutes.typ"] <- "KleinG"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.14",  "DauerMinutes.min"] <- 4 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.14",  "DauerMinutes.max"] <- 6 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.14",  "DauerMinutes.typ"] <- "KleinG"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.15",  "DauerMinutes.min"] <- 6 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.15",  "DauerMinutes.max"] <- 12 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.15",  "DauerMinutes.typ"] <- "KleinG"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.16",  "DauerMinutes.min"] <- 12 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.16",  "DauerMinutes.max"] <- 18 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.16",  "DauerMinutes.typ"] <- "KleinG"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.17",  "DauerMinutes.min"] <- 18 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.17",  "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.17",  "DauerMinutes.typ"] <- "KleinG"
