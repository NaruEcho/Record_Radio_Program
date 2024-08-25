from datetime import datetime, timedelta
import pytz 
import os
import argparse
import subprocess
import math
import time

def run_recording(url, length, save_file_path, record_type):
    command = [
        'python',
        'scripts/record_stream_radio_gogaku.py',
        '-u',
        url,
        '-l',
        str(length),
        '-s',
        save_file_path,
        '-rt',
        record_type
    ]
    subprocess.run(command, check=True)

def process_time(tar_time_str, now_time):
    if tar_time_str == "now":
        return 0
    else:
        try:
            datetime_format = "%Y-%m-%d %H:%M:%S"
            tar_time = datetime.strptime(tar_time_str, datetime_format)
            nowTime = datetime.strptime(now_time, datetime_format)
            # 目標開始時刻の少なくとも10分前の時刻を計算
            ten_minutes_before = tar_time - timedelta(minutes=10)
            # 現在の時刻が10分間の時刻よりも前であるか確認
            if nowTime < ten_minutes_before:
                print(f"Execute at least 10 minutes before {tar_time_str}.")
                return False
            else:
                # 時間差計算
                time_diff = tar_time - nowTime
                # 差を秒単位に変換
                total_seconds = time_diff.total_seconds()
                return total_seconds
        except Exception as e:
            print(f"Invalid time format: {tar_time_str}, {e}.")
            return False


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Record audio from a stream URL.")
    
    # コマンドライン引数の定義
    parser.add_argument("-c",
                        action='store_true',
                        help="If set, change the default save file path."
                       )
    
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
                        help="File path (except extension) to save the recording (default: 'test_record')."
                        )
    
    parser.add_argument("-rt",
                        "--record_type",
                        type=str,
                        nargs='?',
                        default='mp3',
                        help="Select audio format (default: mp3)."
                        )
    
    parser.add_argument("-et",
                        "--execution_time",
                        type=str,
                        nargs='?',
                        default="now", # 即時実行
                        help="Time to execute the program in 24h format in JST (h:m:s). Default is now."
                       )
    
    parser.add_argument("-bt",
                        "--buffer_time",
                        type=int,
                        nargs='?',
                        default=20,
                        help="Buffer time in seconds. Default is 20 seconds."
                       )
    
    # 日本時間のタイムゾーンを設定
    JST = pytz.timezone('Asia/Tokyo')
    
    # 現在の日本時間の年、月、日、曜日を取得
    now = datetime.now(JST)
    
    # コマンドライン引数の解析
    args = parser.parse_args()
    if args.c:
        args.save_file_path = f"contents/radio_eikaiwa/{now.year}/{now.month}/{now.day}"
        if now.weekday() < 5:
            # 待機時間計算
            delta_result = process_time(args.execution_time, now)
            if delta_result == 0:
                # 録音を即時実行
                run_recording(args.url, args.length + args.buffer_time, args.save_file_path, args.record_type)
            elif delta_result:
                delay = delta_result - args.buffer_time
                if delay <= 0:
                    # 録音を即時実行
                    run_recording(args.url, args.length + args.buffer_time, args.save_file_path, args.record_type)
                else:
                    # 録音をdelay秒待ってから録音を実行
                    time.sleep(delay)
                    run_recording(args.url, args.length + args.buffer_time, args.save_file_path, args.record_type)
            else:
                print("Execute at least 10 minutes before.")
                raise
        else:
            print("Radio Eikaiwa is broadcast only on weekdays.")
            raise
    else:
        run_recording(args.url, args.length + args.buffer_time, args.save_file_path, args.record_type)

