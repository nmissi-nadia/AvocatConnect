param (
    [string]$message = "Mise à jour des fichiers"
)

# Naviguer dans le répertoire du projet
Set-Location -Path "C:\chemin\vers\votre\projet"

# Ajouter les fichiers et créer le commit
git add .
git commit -m $message
git push origin main

Write-Host "✅ Commit et push réussis !"
