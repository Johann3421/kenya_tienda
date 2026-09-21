@echo off
setlocal
chcp 65001 >nul
title KENYA Technology - Desinstalador OEM

echo ========================================================
echo     KENYA TECHNOLOGY - DESINSTALADOR OEM
echo ========================================================
echo.

reg delete "HKCR\kenya" /f >nul 2>&1
reg delete "HKCU\Software\Classes\kenya" /f >nul 2>&1

if exist "%ProgramData%\KenyaTech" rmdir /s /q "%ProgramData%\KenyaTech" >nul 2>&1
if exist "C:\Users\Public\Desktop\Soporte y Drivers KENYA.lnk" del /f /q "C:\Users\Public\Desktop\Soporte y Drivers KENYA.lnk" >nul 2>&1

echo [OK] Protocolo y archivos de soporte Kenya eliminados.
timeout /t 3 >nul
exit
