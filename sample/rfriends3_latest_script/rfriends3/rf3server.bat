@echo off
rem -----------------------------------------
rem rfriends3 録音ツール
rem 
rem ポートを指定する場合は、rf3server.bat 8000
rem 
rem 2023/07/19 by mapi
rem -----------------------------------------
echo -----------------------------------------------
echo ipv4を取得しrfriends3_serverを実行します。
echo 複数実行すると、serverが複数起動します。
echo ctrl-c で1つ以外は終了させてください。
echo -----------------------------------------------

set port=8000
if not "%1"=="" (
  set port=%1
)

for /F "usebackq delims=: tokens=2" %%a in (`ipconfig ^| findstr "IPv4"`) do set server=%%a

rem %base%bin\php\php -S %server%:%port% -t %base%script\html\ %base%script\html\router.php
rfriends3_server.bat %server%:%port%
