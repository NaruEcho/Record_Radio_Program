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
type %ver%
echo ベースディレクトリは　%base% です。

title %ver%

rem
rem color 1F

rem cls
rem echo %base%bin\php\php
rem echo %base%script\rf_future.php
%base%bin\php\php %base%script\rf_future.php

echo done
pause
exit
