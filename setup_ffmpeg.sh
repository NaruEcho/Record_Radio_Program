#!/bin/bash

# Set up a log file with a timestamp
LOG_FILE="$HOME/ffmpeg_build_$(date +'%Y%m%d_%H%M%S').log"

# Error handling with timestamp
exec > >(tee -a "$LOG_FILE") 2>&1
set -e
trap 'echo "$(date +'%Y-%m-%d %H:%M:%S') - Error occurred at line $LINENO. Check the log file: $LOG_FILE" | tee -a "$LOG_FILE"; exit 1' ERR

echo "Log file: $LOG_FILE"

# Log start time
echo "Build started at: $(date +'%Y-%m-%d %H:%M:%S')" | tee -a "$LOG_FILE"

# Update and install dependencies
echo "Updating and installing dependencies..."
apt-get update -qq && apt-get -y install \
  autoconf automake build-essential cmake git-core libass-dev \
  libfreetype6-dev libtool pkg-config texinfo wget zlib1g-dev \
  nasm libx264-dev libnuma-dev libx265-dev libvpx-dev libfdk-aac-dev libmp3lame-dev libopus-dev libaom-dev \
  libssl-dev libogg-dev libvorbis-dev libtheora-dev

# Create directories
echo "Creating directories..."
mkdir -p ~/ffmpeg_sources ~/bin

# Clone and build SVT-AV1
echo "Cloning and building SVT-AV1..."
cd ~/ffmpeg_sources
if [ ! -d "SVT-AV1" ]; then
  git clone https://github.com/AOMediaCodec/SVT-AV1.git
else
  cd SVT-AV1
  git pull
  cd ..
fi
mkdir -p SVT-AV1/build
cd SVT-AV1/build
cmake -G "Unix Makefiles" -DCMAKE_INSTALL_PREFIX="$HOME/ffmpeg_build" -DCMAKE_BUILD_TYPE=Release -DBUILD_DEC=OFF -DBUILD_SHARED_LIBS=OFF ..
make -j$(nproc)
make install

# Download and extract FFmpeg source
echo "Downloading and extracting FFmpeg source..."
cd ~/ffmpeg_sources
wget -O ffmpeg-snapshot.tar.bz2 https://ffmpeg.org/releases/ffmpeg-snapshot.tar.bz2
tar xjvf ffmpeg-snapshot.tar.bz2

# Configure and build FFmpeg
echo "Configuring and building FFmpeg..."
cd ffmpeg
./configure \
  --prefix="$HOME/ffmpeg_build" \
  --pkg-config-flags="--static" \
  --disable-ffplay \
  --extra-cflags="-I$HOME/ffmpeg_build/include" \
  --extra-ldflags="-L$HOME/ffmpeg_build/lib" \
  --extra-libs="-lpthread -lm" \
  --bindir="$HOME/bin" \
  --enable-gpl \
  --enable-libaom \
  --enable-libass \
  --enable-libfdk-aac \
  --enable-libfreetype \
  --enable-libmp3lame \
  --enable-libopus \
  --enable-libsvtav1 \
  --enable-libvorbis \
  --enable-libvpx \
  --enable-libtheora \
  --enable-libx264 \
  --enable-libx265 \
  --enable-openssl \
  --enable-nonfree
make -j$(nproc)
make install

# Log end time
echo "Build completed successfully at: $(date +'%Y-%m-%d %H:%M:%S'). Check the log file: $LOG_FILE" | tee -a "$LOG_FILE"
