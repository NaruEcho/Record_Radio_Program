#!/usr/bin/env python
import ffmpeg
import argparse
import os

def record_audio(url, length, save_file_path, record_type):
    save_file_path = rename_audio_filename(save_file_path, record_type)
    stream = ffmpeg.input(url, t=length*60)
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


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Record audio from a stream URL.")
    # コマンドライン引数の定義
    parser.add_argument("-u", 
                        "--url", 
                        type=str, 
                        nargs='?',
                        default='https://radio-stream.nhk.jp/hls/live/2023501/nhkradiruakr2/master.m3u8', 
                        help="Stream URL to record from (default: NHK stream)."
                        )
    parser.add_argument("-l",
                        "--length",
                        type=int,
                        nargs='?',
                        default=2,
                        help="Length of the recording in minutes (default: 2)."
                        )
    parser.add_argument("-s",
                        "--save_file_path",
                        type=str,
                        nargs='?',
                        default='test_record',
                        help="File path (except extension) to save the recording (default: 'test_weather')."
                        )
    parser.add_argument("-rt",
                        "--record_type",
                        type=str,
                        nargs='?',
                        default='mp3',
                        help="Select audio format."
                       )
    # コマンドライン引数の解析
    args = parser.paese_args()
    # 録音を実行
    record_audio(args.url, args.length, args.save_file_path, args.record_type)
