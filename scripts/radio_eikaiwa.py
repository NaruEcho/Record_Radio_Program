from datetime import datetime, timedelta
import pytz 
import os
import argparse
import subprocess

# 日本時間のタイムゾーンを設定
JST = pytz.timezone('Asia/Tokyo')

# 現在の日本時間の年、月、日、曜日を取得
now = datetime.now(JST)

# らじるらじるの放送日取得
def get_last_weekdays(now_date):
    last_week = now_date - timedelta(days=7)
    if last_week.weekday() > 4:
        last_week -= timedelta(days=(last_week.weekday() - 4))
    weekdays = []
    for _ in range(5):
        weekdays.append([last_week.year, last_week.month, last_week.day])
        # もし一週間前の最終平日が金曜日なら次の月曜日の日付を取得する
        if last_week.weekday() == 4:
            last_week += timedelta(days=3)
        # 一週間前の最終平日が金曜日以外ならその翌日の日付を取得する
        else:
            last_week += timedelta(days=1)
    return weekdays


for index, date in enumerate(get_last_weekdays(now)):
    tar_path = f"content/radio_eikaiwa/date[0]/date[1]/date[2].mp3"
    if os.path.exists(tar_path):
        date.append(False)
    else:
        date.append(True)
    # ここでボタンのidタグ検索で取得した配列でTrueの場合のみ、index番目のボタンを押して再生する処理
    print(f"放送日は{date[0]}-{date[1]}-{date[2]}")


def run_recording(url, length, save_file_path, record_type):
    command = [
        'python',
        'record_audio.py',
        '-u',
        url,
        '-l',
        length,
        '-s',
        save_file_path,
        '-rt',
        record_type
    ]
    subprocess.run(command, check=True)


if __name__ == "__main__":
  parser = argparse.ArgumentParser(description="Record audio from a stream URL.")
  # コマンドライン引数の定義
  parser.add_argument("-u", 
                      "--url", 
                      type=str, 
                      default='https://radio-stream.nhk.jp/hls/live/2023501/nhkradiruakr2/master.m3u8', 
                      help="Stream URL to record from (default: NHK stream)."
                     )
  parser.add_argument("-l",
                      "--length",
                      type=int,
                      default=2,
                      help="Length of the recording in minutes (default: 2)."
                     )
  parser.add_argument("-s",
                      "--save_file_path",
                      type=str,
                      default='test_weather',
                      help="File path (except extension) to save the recording (default: 'test_weather')."
                     )
  parser.add_argument("-rt",
                      "--record_type",
                      type=str,
                      default='mp3',
                      help="Select audio format."
                     )

# コマンドライン引数の解析
args = parser.paese_args()

# 録音を実行
run_recording(args.url, args.length, args.save_file_path, args.record_type)

