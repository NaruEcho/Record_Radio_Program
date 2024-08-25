#!/usr/bin/env python
from datetime import datetime, timedelta
import argparse
import time
import pytz
import os
from get_chrome_driver import GetChromeDriver
from selenium import webdriver
import wave
import pyaudio
from pydub import AudioSegment

def driver_init():
    options = webdriver.ChromeOptions()
    options.add_argument('--headless')
    return webdriver.Chrome(options=options)

def record_audio(length, save_file_path):
    try:
        CHUNK = 1024
        FORMAT = pyaudio.paInt16
        CHANNELS = 2
        RATE = 44100
        JST = pytz.timezone('Asia/Tokyo')
        nowRecord = datetime.now(JST)
        print(f"Start recording in {nowRecord}")
        p = pyaudio.PyAudio()
        stream = p.open(format=FORMAT,
                        channels=CHANNELS,
                        rate=RATE,
                        input=True,
                        frames_per_buffer=CHUNK
                       )
        print("* recording")
        frames = []
        for _ in range(0, int(RATE / CHUNK * length)):
            data = stream.read(CHUNK)
            frames.append(data)
        print("* done recording")
        stream.stop_stream()
        stream.close()
        p.terminate()
        wav_output = save_file_path + ".wav"
        wf = wave.open(wav_output, 'wb')
        wf.setnchannels(CHANNELS)
        wf.setsampwidth(p.get_sample_size(FORMAT))
        wf.setframerate(RATE)
        wf.writeframes(b''.join(frames))
        wf.close()
        print("Recording saved to", save_file_path)
        return wav_output
    except Exception as e:
        print(f"Error: {e}")
        raise

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

def convert_audio_format(wav_file, format):
    audio = AudioSegment.from_wav(wav_file)
    output_file_with_ext = rename_audio_filename(wav_file, format)
    audio.export(output_file_with_ext, format=format)
    print(f"Converted to {output_file_with_ext}")
    return output_file_with_ext

def main(url, record_seconds, output_filename, output_format):
    driver = driver_init
    driver.get(url)
    time.sleep(5) # ブラウザがストリーミングを読み込むまでの予備待機時間
    wav_file = record_audio(record_seconds, output_filename)
    driver.quit()
    if output_format != "wav":
        convert_file = convert_audio_format(wav_file, output_format)
        os.remove(wav_file) # コンバート後にwavファイルを削除
        print(f"Deleted the original WAV file: {wav_file}")

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
    main(args.url, args.length, args.save_file_path, args.record_type)
