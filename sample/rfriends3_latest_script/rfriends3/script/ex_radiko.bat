@echo off
rem
rem rfriends (radiko radiruò^âπÉcÅ[Éã)
rem
set scrdir=%~dp0
set base=%scrdir%..\
set php=%base%bin\php\php.exe
rem ------------------------------------ exec
set ex=rfriends_exec
set exno=2
set opt=""
set exnam="reserve-radiko"

echo %ex% %exno% %opt% Executing...
%php% %scrdir%%ex%.php %exno% %opt% %exnam%
rem pause
exit
