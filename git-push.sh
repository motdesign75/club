#!/bin/bash

echo "🔍 Git-Status prüfen..."
git status

echo "📦 Änderungen hinzufügen..."
git add .

echo "✍️ Commit-Nachricht eingeben:"
read -p "Nachricht: " msg

echo "📤 Änderungen werden committed und gepusht..."
git commit -m "$msg"
git push origin main

echo "✅ Fertig! Änderungen sind auf GitHub."
