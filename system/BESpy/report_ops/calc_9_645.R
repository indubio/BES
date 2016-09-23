#==================
# 9-645
# komplexer Entlassungsaufwand bei Erwachsenen

#==================
# seit 2014

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.00", "DauerMinutes.min"] <- 5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.00", "DauerMinutes.max"] <- 10 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.00", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.00", "Profession"] <- "Spezialtherapeuten"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.01", "DauerMinutes.min"] <- 10 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.01", "DauerMinutes.max"] <- 15 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.01", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.01", "Profession"] <- "Spezialtherapeuten"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.02", "DauerMinutes.min"] <- 15 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.02", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.02", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.02", "Profession"] <- "Spezialtherapeuten"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.10", "DauerMinutes.min"] <- 5 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.10", "DauerMinutes.max"] <- 10 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.10", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.10", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.11", "DauerMinutes.min"] <- 10 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.11", "DauerMinutes.max"] <- 15 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.11", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.11", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.12", "DauerMinutes.min"] <- 15 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.12", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.12", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.12", "Profession"] <- "Ärzte"

#==================
# seit 2015

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.03", "DauerMinutes.min"] <- 1 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.03", "DauerMinutes.max"] <- 2 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.03", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.03", "Profession"] <- "Spezialtherapeuten"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.04", "DauerMinutes.min"] <- 2 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.04", "DauerMinutes.max"] <- 4 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.04", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.04", "Profession"] <- "Spezialtherapeuten"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.05", "DauerMinutes.min"] <- 4 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.05", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.05", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.05", "Profession"] <- "Spezialtherapeuten"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.13", "DauerMinutes.min"] <- 1 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.13", "DauerMinutes.max"] <- 2 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.13", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.13", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.14", "DauerMinutes.min"] <- 2 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.14", "DauerMinutes.max"] <- 4 * 60 - 1
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.14", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.14", "Profession"] <- "Ärzte"

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.15", "DauerMinutes.min"] <- 4 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.15", "DauerMinutes.max"] <- 24 * 60
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.15", "DauerMinutes.typ"] <- "EntlassA"
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-645.15", "Profession"] <- "Ärzte"
