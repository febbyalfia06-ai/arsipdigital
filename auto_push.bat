@echo off
echo ==================================================
echo   AUTO PUSH KE GITHUB - ARSIP DIGITAL KELURAHAN
echo ==================================================
echo.

cd /d "%~dp0"
set GIT_EXE="C:\Program Files\Git\cmd\git.exe"

echo [1/3] Menambahkan semua file yang berubah...
%GIT_EXE% add .

set /p msg="Masukkan pesan update (atau tekan Enter untuk 'Auto update'): "
if "%msg%"=="" set msg="Auto update"

echo.
echo [2/3] Menyimpan perubahan (Commit)...
%GIT_EXE% commit -m "%msg%"

echo.
echo [3/3] Mengirim ke GitHub (Push)...
%GIT_EXE% push origin master

echo.
echo ==================================================
echo   SELESAI! Webhook GitHub akan otomatis me-refresh
echo   hosting Anda dalam beberapa detik.
echo ==================================================
pause
