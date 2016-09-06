#================================================
# Umwandlung von TE Codes in TherapieEinheiten

TECode.Basis <- c("9-649", "9-696")

dummy            <- subset(PsychOPSCodes, PsychOPSCodes$OPS.Code.major %in% TECode.Basis)
dummy$Code.Dauer <- substr(dummy$OPS.Code.minor, 2, 2)

dummy$Therapieeinheiten <- 0
dummy[dummy$Code.Dauer == "0", "Therapieeinheiten"] <- 1
dummy[dummy$Code.Dauer == "1", "Therapieeinheiten"] <- 2
dummy[dummy$Code.Dauer == "2", "Therapieeinheiten"] <- 3
dummy[dummy$Code.Dauer == "3", "Therapieeinheiten"] <- 4
dummy[dummy$Code.Dauer == "4", "Therapieeinheiten"] <- 5
dummy[dummy$Code.Dauer == "5", "Therapieeinheiten"] <- 6
dummy[dummy$Code.Dauer == "6", "Therapieeinheiten"] <- 7
dummy[dummy$Code.Dauer == "7", "Therapieeinheiten"] <- 8
dummy[dummy$Code.Dauer == "8", "Therapieeinheiten"] <- 9
dummy[dummy$Code.Dauer == "9", "Therapieeinheiten"] <- 10
dummy[dummy$Code.Dauer == "a", "Therapieeinheiten"] <- 11
dummy[dummy$Code.Dauer == "b", "Therapieeinheiten"] <- 12
dummy[dummy$Code.Dauer == "c", "Therapieeinheiten"] <- 13
dummy[dummy$Code.Dauer == "d", "Therapieeinheiten"] <- 14
dummy[dummy$Code.Dauer == "e", "Therapieeinheiten"] <- 15
dummy[dummy$Code.Dauer == "f", "Therapieeinheiten"] <- 16
dummy[dummy$Code.Dauer == "g", "Therapieeinheiten"] <- 17
dummy[dummy$Code.Dauer == "h", "Therapieeinheiten"] <- 18
dummy[dummy$Code.Dauer == "j", "Therapieeinheiten"] <- 19
dummy[dummy$Code.Dauer == "k", "Therapieeinheiten"] <- 20
dummy[dummy$Code.Dauer == "m", "Therapieeinheiten"] <- 21
dummy[dummy$Code.Dauer == "n", "Therapieeinheiten"] <- 22
dummy[dummy$Code.Dauer == "p", "Therapieeinheiten"] <- 23
dummy[dummy$Code.Dauer == "q", "Therapieeinheiten"] <- 24
dummy[dummy$Code.Dauer == "r", "Therapieeinheiten"] <- 25

dummy$Code.Prof <- substr(dummy$OPS.Code.minor, 1, 1)
dummy[dummy$Code.Prof == "1", "Profession"] <- "Ärzte"
dummy[dummy$Code.Prof == "1", "E.G"] <- "Einzeltherapie"
dummy[dummy$Code.Prof == "2", "Profession"] <- "Ärzte"
dummy[dummy$Code.Prof == "2", "E.G"] <- "Gruppentherapie"

dummy[dummy$Code.Prof == "3", "Profession"] <- "Psychologen"
dummy[dummy$Code.Prof == "3", "E.G"] <- "Einzeltherapie"
dummy[dummy$Code.Prof == "4", "Profession"] <- "Psychologen"
dummy[dummy$Code.Prof == "4", "E.G"] <- "Gruppentherapie"

dummy[dummy$Code.Prof == "5", "Profession"] <- "Spezialtherapeuten"
dummy[dummy$Code.Prof == "5", "E.G"] <- "Einzeltherapie"
dummy[dummy$Code.Prof == "6", "Profession"] <- "Spezialtherapeuten"
dummy[dummy$Code.Prof == "6", "E.G"] <- "Gruppentherapie"

dummy[dummy$Code.Prof == "7", "Profession"] <- "Pflegefachpersonen"
dummy[dummy$Code.Prof == "7", "E.G"] <- "Einzeltherapie"
dummy[dummy$Code.Prof == "8", "Profession"] <- "Pflegefachpersonen"
dummy[dummy$Code.Prof == "8", "E.G"] <- "Gruppentherapie"

PsychOPSCodes[PsychOPSCodes$OPS.Code.major %in% TECode.Basis, "Therapieeinheiten"] <- dummy$Therapieeinheiten
PsychOPSCodes[PsychOPSCodes$OPS.Code.major %in% TECode.Basis, "Profession"] <- dummy$Profession
PsychOPSCodes[PsychOPSCodes$OPS.Code.major %in% TECode.Basis, "E.G"] <- dummy$E.G
rm(dummy)

### Faktorisieren und gleichzeitige Sortieren
PsychOPSCodes$Profession <- factor(PsychOPSCodes$Profession, professions)
PsychOPSCodes$E.G <- factor(PsychOPSCodes$E.G, c("Einzeltherapie", "Gruppentherapie"))
