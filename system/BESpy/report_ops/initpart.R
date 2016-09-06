library(digest)
library(ggplot2)
library(xtable)
library(plyr)
library(lubridate)

###########
### function trim
trim <- function (x) gsub("^\\s+|\\s+$", "", x)

###########
### Konstanten
currentyear <- format(Sys.time(),format="%Y")
today <- as.Date(Sys.time())

monthlabel <- c("Januar", "Februar", "März", "April", "Mai", "Juni", "Juli",
                "August", "September", "Oktober", "November", "Dezember")

professions <- c("Ärzte", "Psychologen", "Pflegefachpersonen", "Spezialtherapeuten")


psy_wards <- c("PSY1", "PSY2", "PSY3", "PSY4",
               "PSY5", "PSYSO1")

psy_tk    <- c("TPS1", "TPS2", "TPS3", "TPSYSO1")

psy_kjpp  <- c("KJPSY1", "KJPSY2", "KJPSY3")

