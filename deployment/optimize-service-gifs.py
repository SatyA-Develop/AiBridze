"""Create homepage video/poster companions for category GIFs (requires imageio-ffmpeg)."""
import argparse
import subprocess
from pathlib import Path
import imageio_ffmpeg

parser = argparse.ArgumentParser(description=__doc__)
parser.add_argument('gifs', nargs='+', type=Path)
args = parser.parse_args()
ffmpeg = imageio_ffmpeg.get_ffmpeg_exe()
for gif in args.gifs:
    if gif.suffix.lower() != '.gif' or not gif.is_file():
        parser.error(f'Not a GIF file: {gif}')
    video = gif.with_suffix('.card.mp4')
    poster = gif.with_suffix('.card.jpg')
    subprocess.run([ffmpeg, '-hide_banner', '-loglevel', 'error', '-y', '-i', str(gif),
                    '-vf', r'scale=trunc(min(854\,iw)/2)*2:-2,fps=25', '-an',
                    '-c:v', 'libx264', '-preset', 'medium', '-crf', '23',
                    '-pix_fmt', 'yuv420p', '-movflags', '+faststart', str(video)], check=True)
    subprocess.run([ffmpeg, '-hide_banner', '-loglevel', 'error', '-y', '-i', str(video),
                    '-frames:v', '1', '-q:v', '3', str(poster)], check=True)
    print(f'{gif.name}: {gif.stat().st_size} -> {video.stat().st_size} bytes', flush=True)

