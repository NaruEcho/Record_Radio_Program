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
rem type %ver%
rem echo �x�[�X�f�B���N�g���́@%base% �ł��B
rem 
rem title %ver%
rem
rem %base%bin\php\php %base%script\rfriends_check.php
rem
rem echo test

