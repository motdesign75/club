#!/bin/bash

################################################################################
# 🚀 Clubano Deployment-Skript
# Dieses Skript aktualisiert die Anwendung sicher auf dem Server.
# Nur zur Ausführung durch berechtigte Admins unter root oder sudo.
################################################################################

APP_DIR="/var/www/vhosts/clubano.de/app.clubano.de"
WEBUSER="www-data"

echo ""
echo "🚀 Starte Deployment für Clubano..."
echo "========================================"

# 📁 In das Projektverzeichnis wechseln
if [ -d "$APP_DIR" ]; then
    echo "📂 Wechsel in Verzeichnis: $APP_DIR"
    cd "$APP_DIR"
else
    echo "❌ Fehler: Verzeichnis $APP_DIR nicht gefunden!"
    exit 1
fi

# 🔄 Git aktualisieren
echo ""
echo "🔄 Hole aktuelle Version aus origin/main..."
git fetch origin
git reset --hard origin/main || {
    echo "❌ Fehler beim Git-Reset!"
    exit 1
}

# 🧰 Composer-Abhängigkeiten installieren (ohne dev)
echo ""
echo "📦 Installiere Composer-Abhängigkeiten..."
composer install --no-dev --optimize-autoloader || {
    echo "❌ Composer-Fehler!"
    exit 1
}

# 🔐 Rechte & Besitzer setzen
echo ""
echo "🔐 Setze korrekte Besitzer- und Dateirechte..."
chown -R root:www-data "$APP_DIR"
chmod -R 775 "$APP_DIR"

chown -R $WEBUSER:$WEBUSER "$APP_DIR/storage"
chown -R $WEBUSER:$WEBUSER "$APP_DIR/bootstrap/cache"
chmod -R 775 "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/bootstrap/cache"

# 🔗 storage:link prüfen/erstellen
if [ ! -L "public/storage" ]; then
    echo ""
    echo "🔗 Erstelle storage:link..."
    php artisan storage:link
else
    echo ""
    echo "🔗 storage:link bereits vorhanden."
fi

# 🧹 Laravel-Caches bereinigen
echo ""
echo "🧹 Leere alte Caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# ⚙️ Neue Caches erzeugen
echo ""
echo "⚙️ Erzeuge neue Caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 🧬 Migrationen ausführen
echo ""
echo "🧬 Führe Migrationen durch..."
php artisan migrate --force || {
    echo "❌ Migration fehlgeschlagen!"
    exit 1
}

echo ""
echo "✅ Deployment erfolgreich abgeschlossen!"
echo "========================================"
