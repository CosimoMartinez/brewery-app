# README

## Credenziali Predefinite

Le credenziali predefinite create durante la prima migrazione sono:

- **Email:** test@example.com
- **Password:** password

## Registrazione Utente

La registrazione utente è attiva. È possibile registrare un nuovo utente tramite l'interfaccia utente per testare l'applicazione.

## Creazione del Token API

Per motivi di sicurezza, il token API dovrebbe essere creato manualmente nella sezione **Api Tokens**. Tuttavia, per facilitare il test dell'API in questo ambiente non sensibile, il token può essere generato automaticamente.

## Flusso di Autenticazione

1. Se non è presente un token valido nel local storage del browser, l'utente viene reindirizzato a `login.html`.
2. Nella pagina di login, l'utente inserisce le credenziali.
3. Dopo l'autenticazione, viene creato un token per l'utente autenticato.
4. Il token viene restituito e memorizzato nel local storage del browser.
5. La pagina `index.html` può quindi utilizzare questo token per visualizzare la lista di birre tramite le API.

## Note sulla Sicurezza

In un'applicazione di produzione con dati sensibili, il token sarebbe gestito in modo più sicuro:

- **Cookie Sicuri:** Il token sarebbe memorizzato in un cookie con attributi `HttpOnly`, `Secure` e `SameSite`.
- **Trasmissione Sicura:** Il token sarebbe trasmesso solo tramite connessioni HTTPS.
- **Gestione Server-Side:** In alternativa, il token potrebbe essere memorizzato nel database e utilizzato solo per comunicazioni server-to-server, a seconda dell'architettura della SPA.

---

Grazie,  
Cosimo Martinez
