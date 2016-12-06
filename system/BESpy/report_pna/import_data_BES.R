#===================================================================
library(RMySQL)
# Parameter
# aus ~/my.cnf

## alle alten DB Connections lösen
for (con in dbListConnections(MySQL())) {dbDisconnect(con);}
rm(con)

## DB Connection herstellen
con_db = dbConnect(MySQL(),group="bes_db")

## UTF8 Encoding
dev_null <- dbGetQuery(con_db,"set character set utf8")

sqlquery <- paste(
  "SELECT * "
  , "FROM pna_rts"
  , sep = " "
)

BES_pna_rts <- dbGetQuery(con_db,sqlquery)
BES_pna_rts$datum_zeit <- as.POSIXlt (BES_pna_rts$datum_zeit, format="%Y-%m-%d %H:%M:%S")

BES_pna_rts$ambulanz <- NA
BES_pna_rts[BES_pna_rts$ambulanz_id == 1,"ambulanz"] <- "PNA"
BES_pna_rts[BES_pna_rts$ambulanz_id == 2,"ambulanz"] <- "RTS"
BES_pna_rts$ambulanz <- factor(BES_pna_rts$ambulanz)

BES_pna <- subset(BES_pna_rts, ambulanz == "PNA")
rm(BES_pna_rts)

sqlquery <- paste(
  "SELECT aufnahmenummer as anr, geburtsdatum, geschlecht, station_a "
  , "FROM fall"
  , "WHERE cancelled = 0"
  , sep = " "
)

BES_fall <- dbGetQuery(con_db,sqlquery)

rownames(BES_fall) <- BES_fall$anr
BES_pna <- cbind(BES_pna, BES_fall[BES_pna$aufnahmenummer,])

rm (con_db, dev_null, sqlquery, BES_fall)
