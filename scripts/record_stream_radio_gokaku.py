#!/usr/bin/env python
import ffmpeg
import sys
import os

def record_audio(
  url='https://radio-stream.nhk.jp/hls/live/2023501/nhkradiruakr2/master.m3u8',
  length=10,
  save_file_path='test_weather',
  record_type='mp3'):
    stream = ffmpeg.input(url, t=length)
    stream = ffmpeg.output(stream, save_file_path, format=record_type)
    ffmpeg.run(stream)

def rename_audio_filename(original_file_path, file_format):
  # オーディオ形式と拡張子のマッピング
  format_to_extension = {
    'mp3': '.mp3',
    'wav': '.wav',
    'flac': '.flac',
    'aac': '.aac',
    'ogg': '.ogg'
  }
  # 指定された形式に対応する拡張子を取得
  extension = format_to_extension.get(file_format.lower())
  if not extension:
    raise ValueError("Unsupported file format.")
  # ファイルの拡張子を取り除く
  base_name, _ = os.path.splitext(original_file_path)
  # 新しいファイル名を生成
  new_file_name = f"{base_name}{extension}"
  return new_file_name
