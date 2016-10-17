@echo off
rem -------------------------------------------------------------
rem  Joomla extensions make file for Windows
rem -------------------------------------------------------------

@setlocal

set EXT_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=php.exe

"%PHP_COMMAND%" "%EXT_PATH%make" %*

@endlocal