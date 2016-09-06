library(zoo)
gradingcodes <- c()
# Erwachsenen Psychiatrie
gradingcodes <- cbind(gradingcodes,
    c("9-60", "9-61", "9-62", "9-63")
)
# KJP
gradingcodes <- cbind(gradingcodes,
    c("9-65", "9-66", "9-67", "9-68")
)
#====================================
# subset grading codes
dummy <- subset(PsychOPSCodes, 
                PsychOPSCodes$OPS.Code.3stellig %in% gradingcodes  
)

# extrahiere alle Fall.Nr
ucases <- subset(dummy, 
    !duplicated(dummy$Fall.Nr), 
    select=c(Fall.Nr, Aufnahmedatum, Entlassdatum, Station)
    )
# setze E-Datum wenn nicht vorhanden auf heute
ucases[is.na(ucases$Entlassdatum), "Entlassdatum"] <- as.Date(Sys.time())

# subset alle Einstufung Codes, der selektierten Fälle
dummy_einstufungen <- subset(PsychOPSCodes, 
    PsychOPSCodes$OPS.Code.3stellig %in% gradingcodes &
    PsychOPSCodes$Fall.Nr %in% ucases$Fall.Nr,
    select=c(Fall.Nr, Datum, OPS.Code, OPS.Code.3stellig)
    )
# Datumsdublikate entfernen
dummy_einstufungen <- dummy_einstufungen[!duplicated(dummy_einstufungen[c("Fall.Nr","Datum")]),]

# expand DT bei Sequenze von Aufnahme- / Entlassdatum
OPSBehandlungsstatus <- setDT(ucases)[, list(Fall.Nr=Fall.Nr, Station=Station, Datum=seq(Aufnahmedatum, Entlassdatum, by="day")), by=1:nrow(ucases)]
OPSBehandlungsstatus$nrow <- NULL

# expanded DF um OPS Codes anreichern
OPSBehandlungsstatus <- merge(
  y = OPSBehandlungsstatus,
  x = dummy_einstufungen,
  by = c("Fall.Nr", "Datum"),
  all.y = TRUE
)

# repeat Einstufung an den Folgetagen na.locf
OPSBehandlungsstatus$OPS.Code.3stellig <- na.locf(OPSBehandlungsstatus$OPS.Code.3stellig, na.rm = FALSE)
OPSBehandlungsstatus$OPS.Code <- na.locf(OPSBehandlungsstatus$OPS.Code, na.rm = FALSE)

OPSBehandlungsstatus[OPSBehandlungsstatus$Station=="PSY6", "Station"] <- "PSYSO1"
rm (dummy, dummy_einstufungen, ucases)
