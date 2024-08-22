from datetime import datetime, timedelta
import pytz

# 日本時間のタイムゾーンを設定
JST = pytz.timezone('Asia/Tokyo')

# 現在の日本時間の時刻、日付、年を取得
now = datetime.now(JST)
current_time = now.strftime('%H:%M:%S')
current_date = now.strftime('%Y-%m-%d')
current_year = now.year

# 一週間前の最終平日を取得する関数
def get_last_week(now_date):
    time_delta = 0
    if( now_date.weekday > 4 ):
        time_delta = now_date.weekday - 4
    last_week_weekday = now - timedelta(days=7) - timedelta(days=time_delta)
    weekdays = []
    weekdays.append(last_week_weekday)
    for i in range(3):
        if( last_week_weekday.weekday == 4 ):
            next_weekday = last_week_day + timedelta(days=3)
        else:
            next_weekday = last_week_day + timedelta(days=1)
        weekdays.append(next_weekday)
    return weekdays
