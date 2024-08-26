import pyaudio

def list_audio_devices():
    p = pyaudio.PyAudio()
    print("Available audio devices:")
    for i in range(p.get_device_count()):
        device_info = p.get_device_info_by_index(i)
        print(f"Index: {i}, Name: {device_info['name']}, Channels: {device_info['maxInputChannels']}, Sample Rate: {device_info['defaultSampleRate']}")
    p.terminate()

if __name__ == "__main__":
    list_audio_devices()
