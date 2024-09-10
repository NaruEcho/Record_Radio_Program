from faster_whisper import WhisperModel
import ffmpeg
import os
import srt
import sys
from pydub import AudioSegment
from pydub.silence import split_on_silence
from datetime import timedelta

def transcribe_audio_with_silence_handling(audio_file, silence_thresh=-40, min_silence_len=500):
    # 音声ファイルを読み込み
    audio = AudioSegment.from_file(audio_file)

    # 無音区間で音声を分割
    chunks = split_on_silence(audio, min_silence_len=min_silence_len, silence_thresh=silence_thresh)

    # 各チャンクの開始時間を追跡
    current_time = 0  # current_timeはミリ秒単位で追跡する

    srt_entries = []
    model = WhisperModel("large-v3", device="cpu", compute_type="int8")

    for i, chunk in enumerate(chunks):
        chunk_file = f"temp_chunk_{i}.mp3"
        chunk.export(chunk_file, format="mp3")

        # チャンクごとに音声を転写
        segments, info = model.transcribe(chunk_file, beam_size=5, vad_filter=True, without_timestamps=False, multilingual=True)

        # 言語検出の確認
        print(f"Detected language '{info.language}' with probability {info.language_probability}")

        # 各セグメントをSRT形式に変換
        for segment in segments:
            start_time = timedelta(seconds=segment.start) + timedelta(milliseconds=current_time)
            end_time = timedelta(seconds=segment.end) + timedelta(milliseconds=current_time)
            srt_entry = srt.Subtitle(index=len(srt_entries) + 1, start=start_time, end=end_time, content=segment.text)
            srt_entries.append(srt_entry)

        # チャンクの再生時間を加算（len(chunk)はミリ秒なので）
        current_time += len(chunk)

    os.makedir(f"temp/{os.path.dirname(audio_file)}", exist_ok=True)

    # SRTファイルに書き出し
    with open(f"temp/{audio_file}.srt", "w", encoding='utf-8') as srt_file:
        srt_file.write(srt.compose(srt_entries))

# スクリプトの実行
if __name__ == "__main__":
    transcribe_audio_with_silence_handling(sys.argv[1])
