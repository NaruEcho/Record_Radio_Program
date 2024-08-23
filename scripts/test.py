from selenium import webdriver
from get_chrome_driver import GetChromeDriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
import time
import pyaudio
import wave
import os

get_driver = GetChromeDriver()
get_driver.install()

def driver_init():
    options = webdriver.ChromeOptions()
    options.add_argument('--headless')
    return webdriver.Chrome(options=options)

def record_audio(duration, output_file):
    # PyAudio初期化
    p = pyaudio.PyAudio()

    # 録音の設定
    stream = p.open(format=pyaudio.paInt16,
                    channels=1,
                    rate=44100,
                    input=True,
                    frames_per_buffer=1024)

    frames = []

    print("Recording...")
    for i in range(0, int(44100 / 1024 * duration)):
        data = stream.read(1024)
        frames.append(data)
    print("Recording finished.")

    # ストリーム停止
    stream.stop_stream()
    stream.close()
    p.terminate()

    # 録音したデータを保存
    wf = wave.open(output_file, 'wb')
    wf.setnchannels(1)
    wf.setsampwidth(p.get_sample_size(pyaudio.paInt16))
    wf.setframerate(44100)
    wf.writeframes(b''.join(frames))
    wf.close()

def main(url, duration, output_file):
    # ChromeDriverのセットアップ
    driver = driver_init()

    try:
        # サイトへアクセス
        driver.get(url)
        time.sleep(5)  # サイトが完全に読み込まれるまで待つ

        # 録音開始
        record_audio(duration, output_file)
        
    finally:
        driver.quit()

if __name__ == "__main__":
    URL = "https://radio-stream.nhk.jp/hls/live/2023501/nhkradiruakr2/master.m3u8"  # 音声が配信されているURL
    DURATION = 30  # 録音時間（秒）
    OUTPUT_PATH = os.path.join(os.getcwd(), "recorded_audio.wav")  # 保存先のパス

    main(URL, DURATION, OUTPUT_PATH)
