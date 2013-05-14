# Regelbehandlung bei psychischen und psychosomatischen Störungen und
# Verhaltensstörungen bei Erwachsenen ohne Therapieeinheiten pro Woche
ops_codes <- c(
    "9-604"
    )
# Regelbehandlung bei psychischen und psychosomatischen Störungen und
# Verhaltensstörungen bei Erwachsenen mit durch Ärzte und/oder Psychologen
# erbrachten Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-605.0", "9-605.1", "9-605.2", "9-605.3", "9-605.4", "9-605.5",
    "9-605.6", "9-605.7", "9-605.8", "9-605.9", "9-605.a"
    )
# Regelbehandlung bei psychischen und psychosomatischen Störungen und
# Verhaltensstörungen bei Erwachsenen mit durch Spezialtherapeuten und/oder
# Pflegefachpersonen erbrachten Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-606.0", "9-606.1", "9-606.2", "9-606.3", "9-606.4", "9-606.5",
    "9-606.6", "9-606.7", "9-606.8", "9-606.9", "9-606.a", "9-606.b",
    "9-606.c", "9-606.d", "9-606.e", "9-606.f", "9-606.g", "9-606.h",
    "9-606.j", "9-606.k", "9-606.m", "9-606.n", "9-606.p", "9-606.q",
    "9-606.r"
    )
# Intensivbehandlung bei psychischen und psychosomatischen Störungen und
# Verhaltensstörungen bei Erwachsenen, bei Patienten mit 1 bis 2 Merkmalen
ops_codes <- c(ops_codes,
    "9-614.0"
    )
# Intensivbehandlung mit durch Ärzte und/oder Psychologen erbrachten
# Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-614.10", "9-614.11", "9-614.12", "9-614.13", "9-614.14",
    "9-614.15", "9-614.16", "9-614.17", "9-614.18", "9-614.19",
    "9-614.1a"
)
# Intensivbehandlung mit durch Spezialtherapeuten und/oder Pflegefachpersonen
# erbrachten Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-614.20", "9-614.21", "9-614.22", "9-614.23", "9-614.24",
    "9-614.25", "9-614.26", "9-614.27", "9-614.28", "9-614.29",
    "9-614.2a", "9-614.2b", "9-614.2c", "9-614.2d", "9-614.2e",
    "9-614.2f", "9-614.2g", "9-614.2h", "9-614.2j", "9-614.2k",
    "9-614.2m", "9-614.2n", "9-614.2p", "9-614.2q", "9-614.2r"
    )

# Intensivbehandlung bei psychischen und psychosomatischen Störungen
# und Verhaltensstörungen bei Erwachsenen, bei Patienten
# mit 3 bis 4 Merkmalen
ops_codes <- c(ops_codes,
    "9-615.0"
    )
# Intensivbehandlung mit durch Ärzte und/oder Psychologen
# erbrachten Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-615.10", "9-615.11", "9-615.12", "9-615.13", "9-615.14",
    "9-615.15", "9-615.16", "9-615.17", "9-615.18", "9-615.19",
    "9-615.1a"
    )
# Intensivbehandlung mit durch Spezialtherapeuten und/oder
# Pflegefachpersonen erbrachten Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-615.20", "9-615.21", "9-615.22", "9-615.23", "9-615.24",
    "9-615.25", "9-615.26", "9-615.27", "9-615.28", "9-615.29",
    "9-615.2a", "9-615.2b", "9-615.2c", "9-615.2d", "9-615.2e",
    "9-615.2f", "9-615.2g", "9-615.2h", "9-615.2j", "9-615.2k",
    "9-615.2m", "9-615.2n", "9-615.2p", "9-615.2q", "9-615.2r"
    )
# Intensivbehandlung bei psychischen und psychosomatischen Störungen
# und Verhaltensstörungen bei Erwachsenen, bei Patienten
# mit 5 und mehr Merkmalen
ops_codes <- c(ops_codes,
    "9-616.0"
    )
# Intensivbehandlung mit durch Ärzte und/oder Psychologen
# erbrachten Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-616.10", "9-616.11", "9-616.12", "9-616.13", "9-616.14",
    "9-616.15", "9-616.16", "9-616.17", "9-616.18", "9-616.19",
    "9-616.1a"
    )
# Intensivbehandlung mit durch Spezialtherapeuten und/oder
# Pflegefachpersonen erbrachten Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-616.20", "9-616.21", "9-616.22", "9-616.23", "9-616.24",
    "9-616.25", "9-616.26", "9-616.27", "9-616.28", "9-616.29",
    "9-616.2a", "9-616.2b", "9-616.2c", "9-616.2d", "9-616.2e",
    "9-616.2f", "9-616.2g", "9-616.2h", "9-616.2j", "9-616.2k",
    "9-616.2m", "9-616.2n", "9-616.2p", "9-616.2q", "9-616.2r"
    )

# Psychotherapeutische Komplexbehandlung bei psychischen und
# psychosomatischen Störungen und Verhaltensstörungen bei Erwachsenen
# mit durch Ärzte und/oder Psychologen erbrachten Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-624.0", "9-624.1", "9-624.2", "9-624.3", "9-624.4", "9-624.5",
    "9-624.6", "9-624.7", "9-624.8", "9-624.9", "9-624.a", "9-624.b",
    "9-624.c", "9-624.d", "9-624.e", "9-624.f"
    )
# Psychotherapeutische Komplexbehandlung bei psychischen und
# psychosomatischen Störungen und Verhaltensstörungen bei Erwachsenen
# mit durch Spezialtherapeuten und/oder Pflegefachpersonen erbrachten
# Therapieeinheiten
ops_codes <- c(ops_codes,
    "9-625.0", "9-625.1", "9-625.2", "9-625.3", "9-625.4", "9-625.5",
    "9-625.6", "9-625.7", "9-625.8", "9-625.9", "9-625.a", "9-625.b",
    "9-625.c", "9-625.d", "9-625.e", "9-625.f", "9-625.g", "9-625.h",
    "9-625.j", "9-625.k", "9-625.m", "9-625.n", "9-625.p", "9-625.q",
    "9-625.r"
    )
# Anzahl der durch Ärzte erbrachten Therapieeinheiten im Rahmen der
# psychosomatisch-psychotherapeutischen Komplexbehandlung bei
# psychischen und psychosomatischen Störungen und Verhaltensstörungen
# bei Erwachsenen
ops_codes <- c(ops_codes,
    "9-630.0", "9-630.1", "9-630.2", "9-630.3", "9-630.4", "9-630.5",
    "9-630.6", "9-630.7", "9-630.8"
    )
# Anzahl der durch Psychologen erbrachten Therapieeinheiten im Rahmen
# der psychosomatisch-psychotherapeutischen Komplexbehandlung bei
# psychischen und psychosomatischen Störungen und Verhaltensstörungen
# bei Erwachsenen
ops_codes <- c(ops_codes,
    "9-631.0", "9-631.1", "9-631.2", "9-631.3", "9-631.4", "9-631.5",
    "9-631.6", "9-631.7", "9-631.8"
    )
# Anzahl der durch Spezialtherapeuten erbrachten Therapieeinheiten im
# Rahmen der psychosomatisch-psychotherapeutischen Komplexbehandlung
# bei psychischen und psychosomatischen Störungen und Verhaltensstörungen
# bei Erwachsenen
ops_codes <- c(ops_codes,
    "9-632.0", "9-632.1", "9-632.2", "9-632.3", "9-632.4", "9-632.5",
    "9-632.6", "9-632.7", "9-632.8", "9-632.9", "9-632.a", "9-632.b",
    "9-632.c"
    )
# Anzahl der durch Pflegefachpersonen erbrachten Therapieeinheiten im
# Rahmen der psychosomatisch-psychotherapeutischen Komplexbehandlung
# bei psychischen und psychosomatischen Störungen und Verhaltensstörungen
# bei Erwachsenen
ops_codes <- c(ops_codes,
    "9-633.0", "9-633.1", "9-633.2", "9-633.3", "9-633.4", "9-633.5",
    "9-633.6", "9-633.7", "9-633.8", "9-633.9", "9-633.a", "9-633.b",
    "9-633.c"
    )
# 1:1-Betreuung
ops_codes <- c(ops_codes,
    "9-640.00", "9-640.01", "9-640.02", "9-640.03"
    )
# Betreuung in Kleinstgruppen
ops_codes <- c(ops_codes,
    "9-640.10", "9-640.11", "9-640.12", "9-640.13"
    )
# Kriseninterventionelle Behandlung von mehr als 1,5 Stunden pro Tag
# durch Ärzte und/oder Psychologen
ops_codes <- c(ops_codes,
    "9-641.0"
    )
# Kriseninterventionelle Behandlung von mehr als 1,5 Stunden pro Tag
# durch Spezialtherapeuten und/oder Pflegefachpersonen
ops_codes <- c(ops_codes,
    "9-641.1"
    )
# Integrierte klinisch-psychosomatisch-psychotherapeutische
# Komplexbehandlung bei psychischen und psychosomatischen Störungen
# und Verhaltensstörungen bei Erwachsenen
ops_codes <- c(ops_codes,
    "9-642"
    )
# Psychiatrisch-psychotherapeutische Behandlung im besonderen Setting
# (Mutter/Vater-Kind-Setting)
ops_codes <- c(ops_codes,
    "9-643.0", "9-643.1", "9-643.2", "9-643.3", "9-643.4", "9-643.5",
    "9-643.6", "9-643.7"
    )
## Pseudo OPS
ops_codes <- c(ops_codes,
    "9-980.0", "9-980.1", "9-980.2", "9-980.3", "9-980.4", "9-980.5"
    )
ops_codes <- c(ops_codes,
    "9-981.0", "9-981.1", "9-981.2", "9-981.3", "9-981.4", "9-981.5"
    )
ops_codes <- c(ops_codes,
    "9-982.0", "9-982.1", "9-982.2", "9-982.3", "9-982.4", "9-982.5"
    )
ops_codes <- c(ops_codes,
    "9-983.0", "9-983.1", "9-983.2", "9-983.3", "9-983.4", "9-983.5",
    "9-983.6"
    )
