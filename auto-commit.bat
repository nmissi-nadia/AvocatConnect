@echo off
echo Lancement du script de commit automatique...

:: Chemin du projet (remplacez le chemin ci-dessous par le chemin complet de votre projet)
cd /d "C:\laragon\www\AvocatConnect"

:: Ajouter tous les fichiers modifiés
git add .

:: Commit avec un message automatique
git commit -m "Mise a jour de fichier "

:: Pousser les modifications sur la branche principale (main ou master)
git push origin main

echo Commit automatique terminé avec succès.
