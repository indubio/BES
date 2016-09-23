#==================
# 9-690
# Kriseninterventionelle Behandlung bei KJ

#==================
# seit 2012

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.0", "DauerMinutes.min"] <- 1.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.0", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.0", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.0", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.1", "DauerMinutes.min"] <- 1.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.1", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.1", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.1", "Profession"] <- "Pflegefachpersonen"

#==================
# seit 2014

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.00", "DauerMinutes.min"] <- 1 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.00", "DauerMinutes.max"] <- 1.5 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.00", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.00", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.01", "DauerMinutes.min"] <- 1.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.01", "DauerMinutes.max"] <- 3 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.01", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.01", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.02", "DauerMinutes.min"] <- 3 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.02", "DauerMinutes.max"] <- 4.5 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.02", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.02", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.03", "DauerMinutes.min"] <- 4.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.03", "DauerMinutes.max"] <- 6 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.03", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.03", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.04", "DauerMinutes.min"] <- 6 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.04", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.04", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.04", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.10", "DauerMinutes.min"] <- 1 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.10", "DauerMinutes.max"] <- 1.5 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.10", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.10", "Profession"] <- "Pflegefachpersonen"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.11", "DauerMinutes.min"] <- 1.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.11", "DauerMinutes.max"] <- 3 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.11", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.11", "Profession"] <- "Pflegefachpersonen"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.12", "DauerMinutes.min"] <- 3 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.12", "DauerMinutes.max"] <- 4.5 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.12", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.12", "Profession"] <- "Pflegefachpersonen"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.13", "DauerMinutes.min"] <- 4.5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.13", "DauerMinutes.max"] <- 6 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.13", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.13", "Profession"] <- "Pflegefachpersonen"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.14", "DauerMinutes.min"] <- 6 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.14", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.14", "DauerMinutes.typ"] <- "Krise_KJ"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-690.14", "Profession"] <- "Pflegefachpersonen"
