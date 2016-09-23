#================================================
# Intensive Betreuung

PsychOPSCodes$DauerMinutes.min <- NA
PsychOPSCodes$DauerMinutes.max <- NA
PsychOPSCodes$DauerMinutes.typ <- NA
PsychOPSCodes$DauerMinutes.typ <- factor(PsychOPSCodes$DauerMinutes.typ, 
    c("1on1",  "KleinG", "Krise", "EntlassA",
      "1on1_KJ", "KleinG_KJ", "Krise_KJ", "EntlassA_KJ"
      )
)

source("calc_9_640.R")
source("calc_9_641.R")
source("calc_9_645.R")
source("calc_9_690.R")
source("calc_9_692.R")
source("calc_9_693.R")
