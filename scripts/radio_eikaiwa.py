from datetime import datetime, timedelta
import pytz

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
    weekdays.append(last_week)
    for i in range(4):
        # もし一週間前の最終平日が金曜日なら次の月曜日の日付を取得する
        if last_week.weekday() == 4:
            weekdays.append(last_week + timedelta(days=3))
        # 一週間前の最終平日が金曜日以外ならその翌日の日付を取得する
        else:
            weekdays.append(last_week + timedelta(days=1))
    return weekdays

for date in get_last_weekdays(now):
    print(f"放送日は{date}")
