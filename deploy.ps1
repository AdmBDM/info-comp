# Настройки подключения
$SSH_USER = "admbdm"
$SSH_HOST = "192.168.88.7"
$REMOTE_WEBROOT = "/var/www/infocomp"

# Локальные пути
$LOCAL_FRONTEND = "R:\www\it-web\info-comp\frontend"
$LOCAL_BACKEND  = "R:\www\it-web\info-comp\backend"
$LOCAL_COMMON   = "R:\www\it-web\info-comp\common"

# Опции rsync
$rsyncOpts = "-avz --delete --exclude='.git' --exclude='node_modules'"

# Синхронизация фронтенда
Write-Host "Синхронизация frontend..."
rsync $rsyncOpts $LOCAL_FRONTEND/ $SSH_USER@$SSH_HOST:$REMOTE_WEBROOT/frontend/

# Синхронизация бэкенда
Write-Host "Синхронизация backend..."
rsync $rsyncOpts $LOCAL_BACKEND/ $SSH_USER@$SSH_HOST:$REMOTE_WEBROOT/backend/

# Синхронизация common
Write-Host "Синхронизация common..."
rsync $rsyncOpts $LOCAL_COMMON/ $SSH_USER@$SSH_HOST:$REMOTE_WEBROOT/common/

Write-Host "Деплой завершён."
