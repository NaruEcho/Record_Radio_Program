@echo off
rem -----------------------------------------
rem Rfriends
rem rfriends (radiko radiru録音ツール)
rem 
rem 2017/11/05 by mapi
rem -----------------------------------------
cd /d %~dp0
set base=%CD%\..\
set ver=%base%_Rfriends2
if exist %ver% goto st

echo %ver% ファイルがありません
echo ディレクトリ構成が間違っています。
echo.
pause
exit

:st
rem type %ver%
rem echo ベースディレクトリは　%base% です。
rem 
rem title %ver%
rem
rem %base%bin\php\php %base%script\rfriends_check.php
rem
rem echo test

