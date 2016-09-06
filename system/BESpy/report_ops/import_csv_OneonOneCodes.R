#================================================
# 1:1 Betreuung Erwachsene

PsychOPSCodes$Dauer1on1.min <- NA
PsychOPSCodes$Dauer1on1.max <- NA

#==================
# 2010-2015
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.00",  "Dauer1on1.min"] <- 2
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.00",  "Dauer1on1.max"] <- 5

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.01",  "Dauer1on1.min"] <- 6
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.01",  "Dauer1on1.max"] <- 11

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.02",  "Dauer1on1.min"] <- 12
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.02",  "Dauer1on1.max"] <- 17

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.03",  "Dauer1on1.min"] <- 18
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.03",  "Dauer1on1.max"] <- 24

#==================
# 2016-
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.04",  "Dauer1on1.min"] <- 2
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.04",  "Dauer1on1.max"] <- 3

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.05",  "Dauer1on1.min"] <- 4
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.05",  "Dauer1on1.max"] <- 5

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.06",  "Dauer1on1.min"] <- 6
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.06",  "Dauer1on1.max"] <- 11

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.07",  "Dauer1on1.min"] <- 12
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.07",  "Dauer1on1.max"] <- 17

PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.08",  "Dauer1on1.min"] <- 18
PsychOPSCodes[PsychOPSCodes$OPS.Code == "9-640.08",  "Dauer1on1.max"] <- 24
