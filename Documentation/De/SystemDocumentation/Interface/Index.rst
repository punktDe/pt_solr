^^^^^^^^^^^^^^
Schnittstellen
^^^^^^^^^^^^^^

Die Extension greift auf den Index der SOLR-Server zu, die sich auf den gleichen Systemen wie die TYPO3-Anwendungen befinden.
Beide Systeme (Intranet und Internet) haben jeweils einen eigenen Server und damit einen eigenen Index, damit nur Inhalte
gefunden werden, die auch wirklich sichtbar sein dürfen. Die Indexierung passiert über Schedulertasks der Extension *solr*.