from faster_whisper import WhisperModel
import ffmpeg
import srt
import sys
from pydub import AudioSegment
from pydub.silence import split_on_silence
from datetime import timedelta

def transcribe_audio_with_silence_handling(audio_file, silence_thresh=-40, min_silence_len=500):
    # 音声ファイルを読み込み
    audio = AudioSegment.from_file(audio_file)
	#無音区間で音声を分割
    chunks = split_on_silence(audio, min_silence_len=min_silence_len, silence_thresh=silence_thresh)
	# 各チャンクの開始時間を追跡
	current_time = 0
	srt_entries = []
	model = WhisperModel("large-v3", device="cpu", compute_type="int8")
	for i, chunk in enumerate(chunks):
		chunk.export(f"{audio_file}_chunk_{i}.mp3", format="mp3")
		segments, info = model.transcribe(f"{audio_file}_chunk_{i}.mp3", beam_size=5, vad_filter=True, without_timestamps=False)
		print(f"Detected language '{info.language}' with probability {info.language_probability}")
		for segment in segments:
			start_time = timedelta(seconds=segment.start) + timedelta(milliseconds=current_time)
			end_time = timedelta(seconds=segment.end) + timedelta(milliseconds=current_time)
			srt_entry = srt.Subtitle(index=len(srt_entries) + 1, start=start_time, end=end_time, content=segment.text)
    		srt_entries.append(srt_entry)
		current_time += len(chunk)
	with open(f"{audio_file}.srt", "w", encoding='utf-8') as srt_file:
		srt_file.write(srt.compose(srt_entries))

# スクリプトの実行
if __name__ == "__main__":
    transcribe_audio_with_silence_handling(sys.argv[1])
