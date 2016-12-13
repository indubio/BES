
missing_to_skip <- c(
  # "Fallnummer", "Fallnummer" usw.
  ""
)

miss_comments <- c(
  #  "Fallnummer", "Kommentar"
)
miss_commentsDT <- data.frame(comment = miss_comments[seq(2, length(miss_comments), by = 2)])
miss_commentsDT$comment <- as.character(miss_commentsDT$comment)
# Fallnummer als Rowname zuweisen
rownames(miss_commentsDT) <- miss_comments[seq(1, length(miss_comments), by = 2)]
