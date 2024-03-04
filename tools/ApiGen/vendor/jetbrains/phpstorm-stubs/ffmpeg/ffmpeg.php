<?php
class ffmpeg_movie
{
    /**  Open a video or audio file and return it as an object.
     * @param string $path_to_media - File path of video or audio file to open.
     * @param bool $persistent - Whether to open this media as a persistent resource. See the PHP documentation for more info about persistent resources
     */
    public function __construct($path_to_media, $persistent) {}

    /** Return the duration of a movie or audio file in seconds.
     * @return int
     */
    public function getDuration() {}

    /** Return the number of frames in a movie or audio file.
     * @return int
     */
    public function getFrameCount() {}

    /** Return the frame rate of a movie in fps.
     * @return int
     */
    public function getFrameRate() {}

    /** Return the path and name of the movie file or audio file.
     * @return string
     */
    public function getFilename() {}

    /** Return the comment field from the movie or audio file.
     * @return string
     */
    public function getComment() {}

    /** Return the title field from the movie or audio file.
     * @return string
     */
    public function getTitle() {}

    /** Return the author field from the movie or the artist ID3 field from an mp3 file.
     * @return string
     */
    public function getAuthor() {}

    /** Return the author field from the movie or the artist ID3 field from an mp3 file.
     * @return string
     */
    public function getArtist() {}

    /** Return the copyright field from the movie or audio file.
     * @return string
     */
    public function getCopyright() {}

    /** Return the genre ID3 field from an mp3 file.
     * @return string
     */
    public function getGenre() {}

    /** Return the track ID3 field from an mp3 file.
     * @return string|int
     */
    public function getTrackNumber() {}

    /** Return the year ID3 field from an mp3 file.
     * @return string|int
     */
    public function getYear() {}

    /** Return the height of the movie in pixels.
     * @return int
     */
    public function getFrameHeight() {}

    /** Return the width of the movie in pixels.
     * @return int
     */
    public function getFrameWidth() {}

    /** Return the pixel format of the movie.*/
    public function getPixelFormat() {}

    /** Return the bit rate of the movie or audio file in bits per second.
     * @return int
     */
    public function getBitRate() {}

    /** Return the bit rate of the video in bits per second.
     * NOTE: This only works for files with constant bit rate.
     * @return int
     */
    public function getVideoBitRate() {}

    /** Return the audio bit rate of the media file in bits per second.
     * @return int
     */
    public function getAudioBitRate() {}

    /** Return the audio sample rate of the media file in bits per second.
     * @return int
     */
    public function getAudioSampleRate() {}

    /** Return the current frame index.
     * @return int
     */
    public function getFrameNumber() {}

    /** Return the name of the video codec used to encode this movie as a string.
     * @return string
     */
    public function getVideoCodec() {}

    /** Return the name of the audio codec used to encode this movie as a string.
     * @return string
     */
    public function getAudioCodec() {}

    /** Return the number of audio channels in this movie as an integer.
     * @return int
     */
    public function getAudioChannels() {}

    /** Return boolean value indicating whether the movie has an audio stream.
     * @return bool
     */
    public function hasAudio() {}

    /** Return boolean value indicating whether the movie has a video stream.
     * @return bool
     */
    public function hasVideo() {}

    /** Returns a frame from the movie as an ffmpeg_frame object. Returns false if the frame was not found.
     * @param int $framenumber - Frame from the movie to return. If no framenumber is specified, returns the next frame of the movie.
     * @return ffmpeg_frame
     */
    public function getFrame($framenumber) {}

    /** Returns the next key frame from the movie as an ffmpeg_frame object. Returns false if the frame was not found.
     * @return ffmpeg_frame
     */
    public function getNextKeyFrame() {}
}

class ffmpeg_frame
{
    /**
     * NOTE: This function will not be available if GD is not enabled.
     * @param resource $gd_image
     */
    public function __construct($gd_image) {}

    /** Return the width of the frame.
     * @return int
     */
    public function getWidth() {}

    /** Return the height of the frame.
     * @return int
     */
    public function getHeight() {}

    /** Return the presentation time stamp of the frame.
     * @return int
     */
    public function getPTS() {}

    /** Return the presentation time stamp of the frame.
     * @return int
     */
    public function getPresentationTimestamp() {}

    /** Resize and optionally crop the frame. (Cropping is built into ffmpeg resizing so I'm providing it here for completeness.)
     * @param int $width - New width of the frame (must be an even number).
     * @param int $height - New height of the frame (must be an even number).
     * @param int $crop_top - Remove [croptop] rows of pixels from the top of the frame.
     * @param int $crop_bottom - Remove [cropbottom] rows of pixels from the bottom of the frame.
     * @param int $crop_left - Remove [cropleft] rows of pixels from the left of the frame.
     * @param int $crop_right - Remove [cropright] rows of pixels from the right of the frame.
     * NOTE: Cropping is always applied to the frame before it is resized. Crop values must be even numbers.
     */
    public function resize($width, $height, $crop_top = 0, $crop_bottom = 0, $crop_left = 0, $crop_right = 0) {}

    /** Crop the frame.
     * @param int $crop_top - Remove [croptop] rows of pixels from the top of the frame.
     * @param int $crop_bottom - Remove [cropbottom] rows of pixels from the bottom of the frame.
     * @param int $crop_left - Remove [cropleft] rows of pixels from the left of the frame.
     * @param int $crop_right - Remove [cropright] rows of pixels from the right of the frame.
     * NOTE: Crop values must be even numbers.
     */
    public function crop($crop_top, $crop_bottom = 0, $crop_left = 0, $crop_right = 0) {}

    /** Returns a truecolor GD image of the frame.
     * NOTE: This function will not be available if GD is not enabled.
     * @return resource
     */
    public function toGDImage() {}
}

class ffmpeg_animated_gif
{
    /**
     * @param string $output_file_path - Location in the filesystem where the animated gif will be written.
     * @param int $width - Width of the animated gif.
     * @param int $height - Height of the animated gif.
     * @param int $frame_rate - Frame rate of the animated gif in frames per second.
     * @param int $loop_count - Number of times to loop the animation. Put a zero here to loop forever or omit this parameter to disable looping.
     */
    public function __construct($output_file_path, $width, $height, $frame_rate, $loop_count = 0) {}

    /** Add a frame to the end of the animated gif.
     * @param ffmpeg_frame $frame_to_add - The ffmpeg_frame object to add to the end of the animated gif.
     */
    public function addFrame(ffmpeg_frame $frame_to_add) {}
}
