@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul
title KENYA Technology - Detector de Equipo

echo ========================================================
echo         KENYA TECHNOLOGY - SOPORTE TECNICO OFICIAL
echo ========================================================
echo.
echo Identificando hardware de su equipo KENYA...
echo.

:: 1. Consultar WMI BIOS, ComputerSystemProduct, Registro OEM y ComputerName
for /f "usebackq tokens=*" %%A in (`powershell -NoProfile -ExecutionPolicy Bypass -Command "$s=(Get-CimInstance Win32_BIOS -ErrorAction SilentlyContinue).SerialNumber; if(-not $s -or $s -match 'Default|To be filled|None|0123456789'){$s=(Get-CimInstance Win32_ComputerSystemProduct -ErrorAction SilentlyContinue).IdentifyingNumber}; if(-not $s -or $s -match 'Default|To be filled|None|0123456789'){$s=(Get-ItemProperty 'HKLM:\HARDWARE\DESCRIPTION\System\BIOS' -ErrorAction SilentlyContinue).SystemSerialNumber}; if(-not $s -or $s -match 'Default|To be filled|None'){$c=$env:COMPUTERNAME; if($c -match '([A-Za-z0-9]{14})'){$s=$matches[1]}else{$s=$c}}; if($s){$s=($s -replace '[^A-Za-z0-9]','').Trim().ToUpper()}; Write-Output $s"`) do (
    set "SERIAL=%%A"
)

if "%SERIAL%"=="" (
    set "SERIAL=%COMPUTERNAME%"
)

echo --------------------------------------------------------
echo Equipo detectado : %COMPUTERNAME%
echo Numero de serie  : %SERIAL%
echo --------------------------------------------------------
echo.
echo Abriendo centro de soporte y controladores en su navegador...
echo.

start "" "https://www.kenya.com.pe/consultar/garantia?serie=%SERIAL%&auto=1#controladores"

timeout /t 3 >nul
exit
