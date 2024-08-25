#!/usr/bin/env python
from datetime import datetime, timedelta
import ffmpeg
import requests
import argparse
import time
import pytz
import os
from get_chrome_driver import GetChromeDriver
from selenium import webdriver
import sounddevice as sd
import soundfile as sf
import subprocess

def driver_init():
    options = webdriver.ChromeOptions()
    options.add_argument('--headless')
    return webdriver.Chrome(options=options)

def record_audio(url, length, save_file_path, record_type):
    try:
        #sample_rate = 44100  # サンプルレート
        save_file_path = rename_audio_filename(save_file_path, record_type)
        command = [
            "ffmpeg",
            "-y", # 既存ファイルの上書き
            "-i", url, # 入力URL
            "-t", str(length), # 録音時間
            "-c:a", "copy", # オーディオコーデックをコピー
            save_file_path
        ]
        #driver = driver_init()
        #driver.get(url)
        #print("Recording...")
        JST = pytz.timezone('Asia/Tokyo')
        nowRecord = datetime.now(JST)
        print(f"Start recording in {nowRecord}")
        subprocess.run(command, check=True)
        #recording = sd.rec(int(length * sample_rate), samplerate=sample_rate, channels=2, dtype='int16')
        #sd.wait()  # 録音完了まで待機
        #sf.write(save_file_path, recording, sample_rate)
        print("Recording saved to", save_file_path)
        #driver.quit()
    except Exception as e:
        print(f"Error: {e}")
        raise
    """
    try:
        # ヘッダーを設定
        headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Referer': 'https://radio-stream.nhk.jp/hls/live/2023501/nhkradiruakr2/master.m3u8'
        }
        # ストリートを取得
        response = requests.get(url, headers=headers, stream=True)
        #print(response.json())
        if response.status_code == 200:
            with open(save_file_path, 'wb') as file:
                start_time = time.time()
                for chunk in response.iter_content(chunk_size=8192):
                    current_time = time.time()
                    elapsed_time = current_time - start_time
                    if elapsed_time >= length:
                        break
                    file.write(chunk)
            print(f"Recording saved to {save_file_path}")
        else:
            print(f"Failed to retrieve the content: {response.status_code}")
        # 一時ファイルにストリームを書き込む
        #temp_file = 'temp_stream.ts'
        #with open(temp_file, 'wb') as f:
        #    for chunk in response.iter_content(chunk_size=8192):
        #        f.write(chunk)
        # ffmpegで録音する
        #stream = ffmpeg.input(temp_file, t=length)
        #stream = ffmpeg.output(stream, save_file_path, format=record_type)
        #ffmpeg.run(stream)
        #os.remove(temp_file)
    except ffmpeg.Error as e:
        print(f"Error occurred during recording: {e}")
        raise
    """

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
    # selenium ChromeDriver設定
    get_driver = GetChromeDriver()
    get_driver.install()
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
                        default=50,
                        help="Length of the recording in seconds (default: 50)."
                        )
    parser.add_argument("-s",
                        "--save_file_path",
                        type=str,
                        nargs='?',
                        default='test_record',
                        help="File path (except extension) to save the recording (default: 'test_record')."
                        )
    parser.add_argument("-rt",
                        "--record_type",
                        type=str,
                        nargs='?',
                        default='mp3',
                        help="Select audio format."
                       )
    # コマンドライン引数の解析
    args = parser.parse_args()
    # 録音を実行
    record_audio(args.url, args.length, args.save_file_path, args.record_type)
