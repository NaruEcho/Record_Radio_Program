@echo off
rem -----------------------------------------
rem rfriends3 �^���c�[��
rem 
rem �|�[�g���w�肷��ꍇ�́Arf3server.bat 8000
rem 
rem 2023/07/19 by mapi
rem -----------------------------------------
echo -----------------------------------------------
echo ipv4���擾��rfriends3_server�����s���܂��B
echo �������s����ƁAserver�������N�����܂��B
echo ctrl-c ��1�ȊO�͏I�������Ă��������B
echo -----------------------------------------------

set port=8000
if not "%1"=="" (
  set port=%1
)

for /F "usebackq delims=: tokens=2" %%a in (`ipconfig ^| findstr "IPv4"`) do set server=%%a

rem %base%bin\php\php -S %server%:%port% -t %base%script\html\ %base%script\html\router.php
rfriends3_server.bat %server%:%port%
