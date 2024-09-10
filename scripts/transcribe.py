from faster_whisper import WhisperModel
import ffmpeg
import pysrt
import sys

model = WhisperModel("large-v3", device="cpu", compute_type="int8")

audio_source = sys.argv[1]

segments, info = model.transcribe(
	audio_source,
	beam_size=5,
	vad_filter=True,
	without_timestamps=False,)

subs = pysrt.SubRipFile()
sub_idx = 1
	
print("Detected language '%s' with probability %f" % (info.language, info.language_probability))
for segment in segments:
    start_time = segment.start
    end_time = segment.end
    duration = end_time - start_time
    timestamp = f"{start_time:.3f} - {end_time:.3f}"
    text = segment.text
    sub = pysrt.SubRipItem(index=sub_idx, start=pysrt.SubRipTime(seconds=start_time), 
                           end=pysrt.SubRipTime(seconds=end_time), text=text)
    subs.append(sub)
    sub_idx += 1
    
subs.save(audio_source+".srt")
