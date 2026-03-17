# Laravel Reverb – diffusion en temps réel

## Configuration REVERB_HOST

- **Avec Docker** : dans `src/api/.env`, garder `REVERB_HOST=reverb` pour que l’API envoie les broadcasts au service Reverb. Le frontend (pharma) utilise son propre `.env` avec `VITE_REVERB_HOST=localhost` pour que le navigateur se connecte au port 6001 exposé sur l’hôte.
- **Sans Docker** (API en local) : dans `src/api/.env`, mettre `REVERB_HOST=localhost` pour que Laravel joigne Reverb sur la même machine.

## Démarrer le serveur Reverb

Le frontend (navigateur) doit pouvoir ouvrir une connexion WebSocket vers le serveur Reverb. Sans cela, les messages broadcast ne s’affichent pas.

### Avec Docker

```bash
docker-compose up -d reverb
```

Le port **6001** est exposé sur l’hôte. Le frontend doit utiliser **le même hôte** que celui où tu ouvres l’app (souvent `localhost`).

Dans `src/pharma/.env` :

- `VITE_REVERB_HOST=localhost`
- `VITE_REVERB_PORT=6001`

Redémarre le dev Vite après modification du `.env`.

### Sans Docker (API en local)

Dans un terminal dédié :

```bash
cd src/api
php artisan reverb:start
```

Laisse la commande tourner. Vérifie qu’aucun autre processus n’utilise le port 6001.

## Vérifier que Reverb écoute

- **Avec Docker** : `docker-compose ps` doit afficher le service `reverb` en cours d’exécution.
- **En local** : une fenêtre de terminal doit rester ouverte avec `php artisan reverb:start`.

Si la connexion WebSocket échoue dans la console du navigateur (`ws://localhost:6001/... failed`), c’est en général que Reverb n’est pas démarré ou que le port 6001 n’est pas atteignable depuis la machine où tourne le navigateur.
