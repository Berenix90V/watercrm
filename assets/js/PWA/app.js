if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(base_url+'sw.js', {scope: base_url})
    .then((reg) => {
        console.log('Registrazione web app avvenuta con successo. Lo Scope è '+reg.scope);
    }).catch((error) => {
        console.log('Errore di registrazione: '+error);
    });
}