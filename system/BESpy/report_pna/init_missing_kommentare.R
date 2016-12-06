###
kommentare <- c(
#  "Fallnummer", "Kommentar"
)
kommentarDT <- data.frame(kommentar = kommentare[seq(2, length(kommentare), by = 2)])
kommentarDT$kommentar <- as.character(kommentarDT$kommentar)
rownames(kommentarDT) <- kommentare[seq(1, length(kommentare), by = 2)]

