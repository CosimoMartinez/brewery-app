## README

# Le credenziali sono quelle di default create durante la prima migrazione:

# email: test@example.com
# password: password

# In alternativa ho lasciato la registrazione attiva e il sistema funziona anche 
tramite la registrazione di un utente.

# Il token si puo creare manualmente nella sezione Api Tokens

# In alternativa si puo ottenere a memorizzare automaticamente nella pagina ./index.html al di fuori del framework laravel
# Se non è gia presente nello storage un token valido si viene reindirizzati alla pagina login.html dove sono richieste le credenziali dell'utente
# una volta fatto il login tramite la pagina web che utilizza le API viene creato su laravel un token per l'utente loggato 
# una volta creato viene restituito alla pagina web che lo memorizza nel local storage per i futuri utilizzi

# Grazie, Cosimo Martinez.