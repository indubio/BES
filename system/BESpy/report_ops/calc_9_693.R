#==================
# 9-693
# 1:1 od. Kleinstgruppe-Betreuung bei KJ

#==================
# seit 2015

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.00", "DauerMinutes.min"] <- 1 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.00", "DauerMinutes.max"] <- 2 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.00", "DauerMinutes.typ"] <- "KleinG_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.01", "DauerMinutes.min"] <- 2 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.01", "DauerMinutes.max"] <- 4 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.01", "DauerMinutes.typ"] <- "KleinG_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.02", "DauerMinutes.min"] <- 4 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.02", "DauerMinutes.max"] <- 8 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.02", "DauerMinutes.typ"] <- "KleinG_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.03", "DauerMinutes.min"] <- 8 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.03", "DauerMinutes.max"] <- 12 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.03", "DauerMinutes.typ"] <- "KleinG_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.04", "DauerMinutes.min"] <- 12 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.04", "DauerMinutes.max"] <- 18 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.04", "DauerMinutes.typ"] <- "KleinG_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.05", "DauerMinutes.min"] <- 18 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.05", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.05", "DauerMinutes.typ"] <- "KleinG_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.10", "DauerMinutes.min"] <- 1 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.10", "DauerMinutes.max"] <- 2 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.10", "DauerMinutes.typ"] <- "1on1_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.11", "DauerMinutes.min"] <- 2 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.11", "DauerMinutes.max"] <- 4 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.11", "DauerMinutes.typ"] <- "1on1_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.12", "DauerMinutes.min"] <- 4 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.12", "DauerMinutes.max"] <- 8 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.12", "DauerMinutes.typ"] <- "1on1_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.13", "DauerMinutes.min"] <- 8 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.13", "DauerMinutes.max"] <- 12 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.13", "DauerMinutes.typ"] <- "1on1_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.14", "DauerMinutes.min"] <- 12 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.14", "DauerMinutes.max"] <- 18 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.14", "DauerMinutes.typ"] <- "1on1_KJ"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.15", "DauerMinutes.min"] <- 18 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.15", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-693.15", "DauerMinutes.typ"] <- "1on1_KJ"
