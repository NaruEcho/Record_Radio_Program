@echo off
rem -----------------------------------------
rem Rfriends
rem rfriends (radiko radiru�^���c�[��)
rem 
rem 2017/11/05 by mapi
rem -----------------------------------------
cd /d %~dp0
set base=%CD%\..\
set ver=%base%_Rfriends2
if exist %ver% goto st

echo %ver% �t�@�C��������܂���
echo �f�B���N�g���\�����Ԉ���Ă��܂��B
echo.
pause
exit

:st
type %ver%
echo �x�[�X�f�B���N�g���́@%base% �ł��B

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
