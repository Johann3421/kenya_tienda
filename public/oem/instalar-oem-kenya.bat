@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul
title KENYA Technology - Instalador de Integracion OEM de Fabrica

echo ========================================================
echo     KENYA TECHNOLOGY - CONFIGURACION OEM DE FABRICA
echo ========================================================
echo.
echo Instalando protocolo de soporte y acceso directo en esta PC...
echo.

:: 1. Crear carpeta del sistema KenyaTech
set "TARGET_DIR=%ProgramData%\KenyaTech"
if not exist "%TARGET_DIR%" mkdir "%TARGET_DIR%"

:: 2. Crear script detector interno
(
echo @echo off
echo setlocal enabledelayedexpansion
echo chcp 65001 ^>nul
echo for /f "usebackq tokens=*" %%%%A in ^(`powershell -NoProfile -ExecutionPolicy Bypass -Command "$s=(Get-CimInstance Win32_BIOS -ErrorAction SilentlyContinue).SerialNumber; if(-not $s -or $s -match 'Default|To be filled|None|0123456789'){$s=(Get-CimInstance Win32_ComputerSystemProduct -ErrorAction SilentlyContinue).IdentifyingNumber}; if(-not $s -or $s -match 'Default|To be filled|None|0123456789'){$s=(Get-ItemProperty 'HKLM:\HARDWARE\DESCRIPTION\System\BIOS' -ErrorAction SilentlyContinue).SystemSerialNumber}; if(-not $s -or $s -match 'Default|To be filled|None'){$c=$env:COMPUTERNAME; if($c -match '([A-Za-z0-9]{14})'){$s=$matches[1]}else{$s=$c}}; if($s){$s=($s -replace '[^A-Za-z0-9]','').Trim().ToUpper()}; Write-Output $s"^`^) do ^(
echo     set "SERIAL=%%%%A"
echo ^)
echo if "%%SERIAL%%"=="" set "SERIAL=%%COMPUTERNAME%%"
echo start "" "https://www.kenya.com.pe/consultar/garantia?serie=%%SERIAL%%&auto=1#controladores"
echo exit
) > "%TARGET_DIR%\detect.cmd"

:: 3. Crear ejecutor silencioso VBS
(
echo Set WshShell = CreateObject("WScript.Shell"^)
echo WshShell.Run chr(34^) ^& "%TARGET_DIR%\detect.cmd" ^& chr(34^), 0
echo Set WshShell = Nothing
) > "%TARGET_DIR%\run_silent.vbs"

:: 4. Registrar Protocolo personalizado kenya:// en el Registro de Windows
reg add "HKCR\kenya" /ve /d "URL:Kenya Support Protocol" /f >nul 2>&1
reg add "HKCR\kenya" /v "URL Protocol" /d "" /f >nul 2>&1
reg add "HKCR\kenya\shell\open\command" /ve /d "wscript.exe \"%TARGET_DIR%\run_silent.vbs\" \"%%1\"" /f >nul 2>&1

reg add "HKCU\Software\Classes\kenya" /ve /d "URL:Kenya Support Protocol" /f >nul 2>&1
reg add "HKCU\Software\Classes\kenya" /v "URL Protocol" /d "" /f >nul 2>&1
reg add "HKCU\Software\Classes\kenya\shell\open\command" /ve /d "wscript.exe \"%TARGET_DIR%\run_silent.vbs\" \"%%1\"" /f >nul 2>&1

:: 5. Crear acceso directo en el Escritorio público
powershell -NoProfile -ExecutionPolicy Bypass -Command "$ws = New-Object -ComObject WScript.Shell; $s = $ws.CreateShortcut('C:\Users\Public\Desktop\Soporte y Drivers KENYA.lnk'); $s.TargetPath = 'wscript.exe'; $s.Arguments = '\"%TARGET_DIR%\run_silent.vbs\"'; $s.Description = 'Acceso directo a Soporte y Controladores KENYA'; $s.Save()" >nul 2>&1

echo.
echo [OK] Protocolo 'kenya://' registrado con exito.
echo [OK] Script de deteccion copiado a %TARGET_DIR%\
echo [OK] Acceso directo 'Soporte y Drivers KENYA' creado en el Escritorio.
echo.
echo ========================================================
echo        INSTALACION OEM COMPLETADA SATISFACTORIAMENTE
echo ========================================================
timeout /t 4 >nul
exit
