@echo off
REM ===================================
REM Backup BacaSkuy Content (Manhwa & Chapters Only)
REM Tidak backup users - bisa di-seed ulang
REM ===================================

SET MYSQL_PATH=C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin
SET DB_NAME=BacaSkuy
SET DB_USER=root
SET DB_PASS=
SET BACKUP_DIR=database\backups
SET TIMESTAMP=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
SET TIMESTAMP=%TIMESTAMP: =0%
SET BACKUP_FILE=%BACKUP_DIR%\content_backup_%TIMESTAMP%.sql

echo ===================================
echo Backup Content Only (No Users)
echo ===================================
echo.

echo Creating backup directory...
if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

echo Backing up content tables...
echo - manhwas
echo - genres
echo - chapters
echo - pages
echo - genre_manhwas (pivot)
echo.

"%MYSQL_PATH%\mysqldump" -u %DB_USER% %DB_NAME% manhwas genres chapters pages genre_manhwas > "%BACKUP_FILE%"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ======================================
    echo ✓ Content backup success!
    echo ======================================
    echo File: %BACKUP_FILE%
    echo.
    echo Backed up tables:
    echo   ✓ manhwas (semua manhwa)
    echo   ✓ chapters (semua chapters)
    echo   ✓ pages (semua gambar)
    echo   ✓ genres (semua genre)
    echo   ✓ genre_manhwa (relasi genre-manhwa)
    echo.
    echo NOT backed up:
    echo   ✗ users (akan di-seed ulang)
    echo.
) else (
    echo ✗ Backup failed!
    pause
    exit /b 1
)

echo To restore after migrate:fresh:
echo 1. php artisan migrate:fresh
echo 2. php artisan db:seed (genres + admin user)
echo 3. mysql -u root BacaSkuy ^< %BACKUP_FILE%
echo.
pause
