@echo off
REM ===================================
REM Restore BacaSkuy Database
REM ===================================

SET MYSQL_PATH=C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin
SET DB_NAME=BacaSkuy
SET DB_USER=root
SET DB_PASS=
SET BACKUP_DIR=database\backups

echo Available backups:
dir /b "%BACKUP_DIR%\*.sql"
echo.

set /p BACKUP_FILE="Enter backup filename (e.g., backup_20251113_120000.sql): "

if not exist "%BACKUP_DIR%\%BACKUP_FILE%" (
    echo ✗ File not found!
    pause
    exit /b 1
)

echo.
echo ⚠️  WARNING: This will REPLACE current database with backup!
echo.
set /p CONFIRM="Type 'yes' to continue: "

if /i not "%CONFIRM%"=="yes" (
    echo Cancelled.
    pause
    exit /b 0
)

echo Restoring database...
"%MYSQL_PATH%\mysql" -u %DB_USER% %DB_NAME% < "%BACKUP_DIR%\%BACKUP_FILE%"

if %ERRORLEVEL% EQU 0 (
    echo ✓ Database restored successfully!
) else (
    echo ✗ Restore failed!
    pause
    exit /b 1
)

pause
