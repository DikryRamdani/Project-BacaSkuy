@echo off
REM ===================================
REM Backup BacaSkuy Database
REM ===================================

SET MYSQL_PATH=C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin
SET DB_NAME=BacaSkuy
SET DB_USER=root
SET DB_PASS=
SET BACKUP_DIR=database\backups
SET TIMESTAMP=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
SET TIMESTAMP=%TIMESTAMP: =0%
SET BACKUP_FILE=%BACKUP_DIR%\backup_%TIMESTAMP%.sql

echo Creating backup directory...
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

echo Backing up database...
"%MYSQL_PATH%\mysqldump" -u %DB_USER% %DB_NAME% > "%BACKUP_FILE%"

if %ERRORLEVEL% EQU 0 (
    echo ✓ Backup success: %BACKUP_FILE%
) else (
    echo ✗ Backup failed!
    pause
    exit /b 1
)

echo.
echo To restore:
echo mysql -u root BacaSkuy ^< %BACKUP_FILE%
echo.
pause
