@echo off
setlocal EnableExtensions
cd /d "%~dp0"

set "PHP_EXE="
for /f "delims=" %%P in ('where php 2^>nul') do (
    set "PHP_EXE=%%P"
    goto php_found
)

:search_xampp
if exist "C:\xampp\php\php.exe" set "PHP_EXE=C:\xampp\php\php.exe"
if not defined PHP_EXE if exist "D:\xampp\php\php.exe" set "PHP_EXE=D:\xampp\php\php.exe"
if not defined PHP_EXE if exist "%ProgramFiles%\PHP\php.exe" set "PHP_EXE=%ProgramFiles%\PHP\php.exe"
if not defined PHP_EXE if exist "%ProgramFiles(x86)%\PHP\php.exe" set "PHP_EXE=%ProgramFiles(x86)%\PHP\php.exe"

:php_found
if not defined PHP_EXE (
    echo [ERROR] PHP CLI tidak ditemukan.
    echo Instal XAMPP atau PHP, lalu pastikan php.exe tersedia pada PATH.
    pause
    exit /b 1
)

if not exist "public\index.php" (
    echo [ERROR] Folder public tidak ditemukan. Pastikan file BAT berada di folder utama proyek.
    pause
    exit /b 1
)

"%PHP_EXE%" -v
if errorlevel 1 (
    echo [ERROR] PHP tidak dapat dijalankan.
    pause
    exit /b 1
)

echo.
echo =====================================================
echo  PORTAL SMA NEGERI 9 BANDAR LAMPUNG
echo  Alamat lokal : http://127.0.0.1:8099
echo  Installer    : http://127.0.0.1:8099/install
echo  Tekan CTRL+C untuk menghentikan server.
echo =====================================================
echo.
start "" "http://127.0.0.1:8099/install"
"%PHP_EXE%" -d upload_max_filesize=20M -d post_max_size=128M -d max_file_uploads=10 -d max_execution_time=120 -d max_input_time=120 -S 127.0.0.1:8099 -t public public\router.php

if errorlevel 1 (
    echo.
    echo [INFO] Server berhenti. Periksa apakah port 8099 sedang digunakan.
    pause
)
endlocal
