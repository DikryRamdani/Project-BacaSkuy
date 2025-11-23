@echo off
REM ===================================
REM Restore Content Only (Manhwa & Chapters)
REM ===================================

SET MYSQL_PATH=C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin
SET DB_NAME=BacaSkuy
SET DB_USER=root
SET DB_PASS=
SET BACKUP_DIR=database\backups

echo ===================================
echo Restore Content (Manhwa & Chapters)
echo ===================================
echo.

echo Available content backups:
dir /b "%BACKUP_DIR%\content_backup_*.sql" 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo No content backups found!
    echo Run backup-content-only.bat first.
    pause
    exit /b 1
)
echo.

set /p BACKUP_FILE="Enter backup filename: "

if not exist "%BACKUP_DIR%\%BACKUP_FILE%" (
    echo ✗ File not found!
    pause
    exit /b 1
)

echo.
echo This will restore:
echo   ✓ manhwas
echo   ✓ chapters
echo   ✓ pages
echo   ✓ genres
echo   ✓ genre_manhwas relations
echo.
echo Make sure you already ran:
echo   1. php artisan migrate:fresh
echo   2. php artisan db:seed
echo.

set /p CONFIRM="Continue? (yes/no): "

if /i not "%CONFIRM%"=="yes" (
    echo Cancelled.
    pause
    exit /b 0
)

echo.
echo Restoring content...
"%MYSQL_PATH%\mysql" -u %DB_USER% %DB_NAME% < "%BACKUP_DIR%\%BACKUP_FILE%"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ======================================
    echo ✓ Content restored successfully!
    echo ======================================
    echo.
    echo Restored:
    echo   ✓ All manhwas
    echo   ✓ All chapters
    echo   ✓ All pages
    echo   ✓ Genre relations
    echo.
    echo Admin user already seeded!
    echo Email: admin@bacaskuy.com
    echo Password: admin123
    echo.
) else (
    echo ✗ Restore failed!
    pause
    exit /b 1
)

pause
