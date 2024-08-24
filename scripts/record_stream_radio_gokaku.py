#!/usr/bin/env python
import ffmpeg

url = 'https://radio-stream.nhk.jp/hls/live/2023501/nhkradiruakr2/master.m3u8'
length = 10
saveFile = 'test_weather.mp3'

stream = ffmpeg.input(url, t=length)
stream = ffmpeg.output(stream, saveFile, format='mp3')
ffmpeg.run(stream)
