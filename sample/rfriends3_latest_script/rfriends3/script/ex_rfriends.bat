@echo off
rem  Rfriends (radiko radiruò^âπÉcÅ[Éã)
set scrdir=%~dp0
set base=%scrdir%..\
set php=%base%bin\php\php.exe
rem ------------------------------------ exec
set ex=rfriends_exec
set exno=%1
set opt=%2

echo %ex% %exno% %opt% Executing...
%php% %scrdir%%ex%.php %exno% %opt%
exit
