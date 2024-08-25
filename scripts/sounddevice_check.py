import pyaudio

def list_audio_devices():
    audio = pyaudio.PyAudio()
    device_count = audio.get_device_count()
    for i in range(device_count):
        device_info = audio.get_device_info_by_index(i)
        print(f"Index: {i}, Name: {device_info['name']}, Input Channels: {device_info['maxInputChannels']}")

if __name__ == "__main__":
    list_audio_devices()
